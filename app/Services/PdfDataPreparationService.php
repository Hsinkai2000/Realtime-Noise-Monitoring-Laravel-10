<?php

namespace App\Services;

use App\Models\MeasurementPoint;
use App\Models\NoiseData;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\Fork\Fork;

class PdfDataPreparationService
{
    private MeasurementPoint $measurementPoint;
    private Collection $allNoiseData;
    private array $noiseDataByTimestamp;

    public function __construct(MeasurementPoint $measurementPoint)
    {
        $this->measurementPoint = $measurementPoint;
    }

    /**
     * Pre-fetch all noise data for the date range in a single query
     */
    public function loadNoiseData(Carbon $startDate, Carbon $endDate): void
    {
        // Extend the range to include the previous day (for 12h calculations that span midnight)
        $extendedStart = $startDate->copy()->subDay();
        $extendedEnd = $endDate->copy()->addDay();

        // Single query to fetch all noise data for the entire date range
        $this->allNoiseData = $this->measurementPoint
            ->noiseData()
            ->whereBetween('received_at', [$extendedStart, $extendedEnd])
            ->orderBy('received_at')
            ->get();

        // Index by timestamp for O(1) lookups
        $this->noiseDataByTimestamp = [];
        foreach ($this->allNoiseData as $data) {
            $key = $data->received_at->format('Y-m-d H:i:s');
            $this->noiseDataByTimestamp[$key] = $data;
        }

        Log::info("Loaded {$this->allNoiseData->count()} noise data records for PDF generation");
    }

    /**
     * Prepare all data for all days using multi-threading (with fallback)
     */
    public function prepareAllDaysData(Carbon $startDate, Carbon $endDate): array
    {
        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->copy();
        }

        // Check if multi-threading is available
        if ($this->isMultiThreadingAvailable()) {
            return $this->prepareAllDaysDataParallel($dates);
        } else {
            Log::info("Multi-threading not available, using sequential processing");
            return $this->prepareAllDaysDataSequential($dates);
        }
    }

    /**
     * Check if multi-threading is available
     */
    private function isMultiThreadingAvailable(): bool
    {
        // Check if pcntl extension is loaded
        if (!extension_loaded('pcntl')) {
            return false;
        }

        // Check if pcntl_fork function exists and is callable
        if (!function_exists('pcntl_fork')) {
            return false;
        }

        // Try to detect if we're in a web server context where pcntl might be disabled
        if (php_sapi_name() !== 'cli') {
            return false;
        }

        return true;
    }

    /**
     * Prepare data using multi-threading (parallel processing)
     */
    private function prepareAllDaysDataParallel(array $dates): array
    {
        // Split dates into chunks for parallel processing
        $chunks = array_chunk($dates, max(1, ceil(count($dates) / 4)));

        Log::info("Processing " . count($dates) . " days across " . count($chunks) . " threads (parallel)");

        try {
            // Process chunks in parallel using Spatie Fork
            $results = Fork::new()
                ->before(fn() => app('db')->reconnect())
                ->run(
                    ...array_map(function ($chunk) {
                        return function () use ($chunk) {
                            $chunkResults = [];
                            foreach ($chunk as $date) {
                                $chunkResults[$date->format('Y-m-d')] = $this->prepareDayData($date);
                            }
                            return $chunkResults;
                        };
                    }, $chunks)
                );

            // Merge all results
            $allData = [];
            foreach ($results as $result) {
                $allData = array_merge($allData, $result);
            }

            return $allData;
        } catch (\Exception $e) {
            Log::warning("Multi-threading failed, falling back to sequential: " . $e->getMessage());
            return $this->prepareAllDaysDataSequential($dates);
        }
    }

    /**
     * Prepare data sequentially (fallback when multi-threading not available)
     */
    private function prepareAllDaysDataSequential(array $dates): array
    {
        Log::info("Processing " . count($dates) . " days sequentially");

        $allData = [];
        foreach ($dates as $date) {
            $allData[$date->format('Y-m-d')] = $this->prepareDayData($date);
        }

        return $allData;
    }

    /**
     * Prepare data for a single day (all time slots)
     */
    public function prepareDayData(Carbon $date): array
    {
        $dayData = [
            'slots' => [],
            'hourly' => [],
            'dose' => [],
            'max' => [],
            '12h' => []
        ];

        // Generate all 5-minute slots for the day (288 slots)
        for ($hour = 0; $hour < 24; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 5) {
                $slotDate = new DateTime($date->format('Y-m-d') . sprintf(' %02d:%02d:00', $hour, $minute));
                $key = $slotDate->format('Y-m-d H:i:s');

                // Get 5-minute data
                $dayData['slots'][$key] = $this->get5MinuteData($slotDate);
            }

            // Calculate hourly Leq for this hour
            $hourDate = new DateTime($date->format('Y-m-d') . sprintf(' %02d:00:00', $hour));
            $dayData['hourly'][$hour] = $this->calculate1HourLeq($hourDate);
        }

        // Calculate 12-hour Leq for 7am and 7pm
        $morning = new DateTime($date->format('Y-m-d') . ' 07:00:00');
        $evening = new DateTime($date->format('Y-m-d') . ' 19:00:00');
        
        $dayData['12h']['morning'] = $this->calculate12HourLeq($morning);
        $dayData['12h']['evening'] = $this->calculate12HourLeq($evening);

        // Calculate dose percentages for EVERY hour (using XX:55:00 timestamp)
        // This matches the view logic where $slotDate is the last 5-min slot of each hour
        for ($hour = 0; $hour < 24; $hour++) {
            $hourTime = new DateTime($date->format('Y-m-d') . sprintf(' %02d:55:00', $hour));
            $dayData['dose'][$hour] = $this->calculateDose($hourTime);
        }

        // Calculate max values for EVERY hour (using XX:55:00 timestamp)
        for ($hour = 0; $hour < 24; $hour++) {
            $hourTime = new DateTime($date->format('Y-m-d') . sprintf(' %02d:55:00', $hour));
            $dayData['max'][$hour] = $this->calculateMax($hourTime);
        }

        return $dayData;
    }

    /**
     * Get 5-minute noise data from pre-fetched collection
     */
    private function get5MinuteData(DateTime $slotDate): array
    {
        $key = $slotDate->format('Y-m-d H:i:s');
        
        if (isset($this->noiseDataByTimestamp[$key])) {
            $noiseData = $this->noiseDataByTimestamp[$key];
            $limit = $this->measurementPoint->soundLimit->leq5_limit($slotDate);
            
            return [
                'leq_data' => number_format($noiseData->leq, 1),
                'should_alert' => $noiseData->leq >= $limit,
                'exists' => true
            ];
        }

        return [
            'leq_data' => '-',
            'should_alert' => false,
            'exists' => false
        ];
    }

    /**
     * Calculate 1-hour Leq from pre-fetched data
     */
    private function calculate1HourLeq(DateTime $time): array
    {
        $startTime = clone $time;
        $startTime->setTime((int)$startTime->format("H"), 0, 0);
        
        $endTime = clone $startTime;
        $endTime->modify('+1 hour')->modify('-1 minute');

        $hourData = $this->getDataBetween($startTime, $endTime);
        $num_blanks = 12 - count($hourData);
        $leq = $this->calculateLeq($hourData);
        
        $limit = $this->measurementPoint->soundLimit->leq1h_limit($time);

        return [
            'leq_data' => $leq > 0 ? number_format(round($leq, 1), 1) : '-',
            'should_alert' => round($leq, 1) > $limit,
            'num_blanks' => $num_blanks
        ];
    }

    /**
     * Calculate 12-hour Leq from pre-fetched data
     */
    private function calculate12HourLeq(DateTime $time): array
    {
        [$startDateTime, $endDateTime] = $this->getTimeslotStartEnd($time);
        
        $timeslotData = $this->getDataBetween($startDateTime, $endDateTime);
        $num_blanks = 144 - count($timeslotData);
        $leq = $this->calculateLeq($timeslotData);
        
        $limit = $this->measurementPoint->soundLimit->leq12h_limit($time);

        return [
            'leq_data' => $leq > 0 ? number_format(round($leq, 1), 1) : '-',
            'should_alert' => round($leq, 1) > $limit,
            'num_blanks' => $num_blanks
        ];
    }

    /**
     * Calculate dose percentage
     * OPTIMIZED: Uses pre-fetched data instead of making database queries
     */
    private function calculateDose(DateTime $time): array
    {
        [$decision, $last_data_datetime] = $this->measurementPoint->soundLimit->check_12_1_hour_limit_type($time);
        
        // Use pre-fetched data instead of querying database
        if ($decision == '12h') {
            // Calculate 12-hour Leq using pre-fetched data
            [$startDateTime, $endDateTime] = $this->getTimeslotStartEnd($time);
            $timeslotData = $this->getDataBetween($startDateTime, $endDateTime);
            $num_blanks = 144 - count($timeslotData);
            $calculatedLeq = $this->calculateLeq($timeslotData);
            $limit = $this->measurementPoint->soundLimit->leq12h_limit($last_data_datetime);
            
            $calculated_dose_percentage = $this->measurementPoint->soundLimit->calculate_dose_perc(
                $calculatedLeq, 
                $limit, 
                $num_blanks, 
                144
            );
        } else {
            // Calculate 1-hour Leq using pre-fetched data
            $startTime = clone $time;
            $startTime->setTime((int)$startTime->format("H"), 0, 0);
            $endTime = clone $startTime;
            $endTime->modify('+1 hour')->modify('-1 minute');
            
            $hourData = $this->getDataBetween($startTime, $endTime);
            $num_blanks = 12 - count($hourData);
            $calculatedLeq = $this->calculateLeq($hourData);
            $limit = $this->measurementPoint->soundLimit->leq1h_limit($last_data_datetime);
            
            $calculated_dose_percentage = $this->measurementPoint->soundLimit->calculate_dose_perc(
                $calculatedLeq, 
                $limit, 
                $num_blanks, 
                12
            );
        }

        return [
            'leq_data' => number_format($calculated_dose_percentage, 2),
            'should_alert' => $calculated_dose_percentage >= 70,
            'decision' => $decision
        ];
    }

    /**
     * Calculate max value
     */
    private function calculateMax(DateTime $time): array
    {
        $datenow = Carbon::now()->addHours(8)->subDays(2);
        $date = new Carbon($time);
        
        if ($date->hour == 7) {
            $date->hour = 18;
        }

        $doseData = $this->calculateDose($date);
        $calculated_dose_percentage = (float)str_replace(',', '', $doseData['leq_data']);
        $decision = $doseData['decision'];
        $num_blanks = $decision == '12h' ? 144 - $this->getDataBetween(
            ...$this->getTimeslotStartEnd($date)
        )->count() : 12 - $this->getDataBetween(
            (clone $date)->setTime((int)$date->format("H"), 0, 0),
            (clone $date)->setTime((int)$date->format("H"), 59, 0)
        )->count();

        if ($datenow > $date || $num_blanks == 0) {
            return ['leq_data' => 'FIN', 'should_alert' => false];
        }

        if ($calculated_dose_percentage < 100 && $num_blanks != 12 && $num_blanks != 144) {
            $key = $date->format('Y-m-d H:i:s');
            $noiseData = $this->noiseDataByTimestamp[$key] ?? new NoiseData(['received_at' => $date]);
            
            [$leq_5mins_should_alert, $leq5limit] = $this->measurementPoint->leq_5_mins_exceed_and_alert($noiseData);
            $limit = $decision == '12h' 
                ? $this->measurementPoint->soundLimit->leq12h_limit($date)
                : $this->measurementPoint->soundLimit->leq1h_limit($date);
            
            $missingVal = $decision == '12h' ? 144 : 12;
            $sum = round(convert_to_db((1 - ($calculated_dose_percentage / 100)) * ((linearise_leq($limit) * $missingVal) / $num_blanks)), 1);

            return [
                'leq_data' => $decision == '12h' ? min([$sum, $leq5limit]) : $sum,
                'should_alert' => false
            ];
        }

        return ['leq_data' => 'N.A.', 'should_alert' => false];
    }

    /**
     * Get data between two timestamps from pre-fetched collection
     * OPTIMIZED: Uses early termination since data is sorted
     */
    private function getDataBetween(DateTime $start, DateTime $end): Collection
    {
        $startKey = $start->format('Y-m-d H:i:s');
        $endKey = $end->format('Y-m-d H:i:s');

        // Optimize by using early termination - data is already sorted by received_at
        $result = [];
        foreach ($this->allNoiseData as $data) {
            $dataKey = $data->received_at->format('Y-m-d H:i:s');
            
            // Skip until we reach the start time
            if ($dataKey < $startKey) continue;
            
            // Stop once we pass the end time (early termination)
            if ($dataKey > $endKey) break;
            
            $result[] = $data;
        }

        return collect($result);
    }

    /**
     * Calculate Leq from collection
     */
    private function calculateLeq(Collection $data): float
    {
        if ($data->isEmpty()) {
            return 0;
        }

        $sum = 0.0;
        foreach ($data as $leqData) {
            $sum += round(linearise_leq($leqData->leq), 1);
        }

        $avgLeq = $sum / $data->count();
        return convert_to_db($avgLeq);
    }

    /**
     * Get timeslot start and end datetime
     */
    private function getTimeslotStartEnd(DateTime $time): array
    {
        [$day, $time_range] = $this->measurementPoint->soundLimit->getTimeRange($time);
        $last_noise_data_date = $time->format('Y-m-d');

        // This mirrors the logic from MeasurementPoint::get_final_time_start_stop
        $timeSlots = [
            '7am_7pm' => ['start' => '07:00', 'end' => '18:59'],
            '7pm_10pm' => ['start' => '19:00', 'end' => '06:59'],
            '10pm_12am' => ['start' => '19:00', 'end' => '06:59'],
            '12am_7am' => ['start' => '19:00', 'end' => '06:59'],
        ];

        if (in_array($time_range, ['7pm_10pm', '10pm_12am', '12am_7am'])) {
            $last_noise_data_start_date = $last_noise_data_date;
            $last_noise_data_end_date = (new DateTime($last_noise_data_date))->modify('+1 day')->format('Y-m-d');
        } else {
            $last_noise_data_start_date = $last_noise_data_date;
            $last_noise_data_end_date = $last_noise_data_date;
        }

        $start_time = new DateTime($last_noise_data_start_date . ' ' . $timeSlots[$time_range]['start']);
        $end_time = new DateTime($last_noise_data_end_date . ' ' . $timeSlots[$time_range]['end']);

        return [$start_time, $end_time];
    }
}

