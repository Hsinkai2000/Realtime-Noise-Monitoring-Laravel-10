<?php

namespace App\Services;

use App\Models\MeasurementPoint;
use App\Models\NoiseData;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class PdfDataPreparationService
{
    private MeasurementPoint $measurementPoint;
    private Collection $allNoiseData;
    private array $noiseDataByTimestamp;
    private array $noiseDataByDate;
    private array $noiseDataByDateHour; // Group by date AND hour for faster 1-hour queries

    public function __construct(MeasurementPoint $measurementPoint)
    {
        $this->measurementPoint = $measurementPoint;
    }

    /**
     * Pre-fetch all noise data for the date range in a single query
     */
    public function loadNoiseData(Carbon $startDate, Carbon $endDate): void
    {
        $startTime = microtime(true);
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
        $this->noiseDataByDate = [];
        $this->noiseDataByDateHour = [];
        
        foreach ($this->allNoiseData as $data) {
            $key = $data->received_at->format('Y-m-d H:i:s');
            $dateKey = $data->received_at->format('Y-m-d');
            $dateHourKey = $data->received_at->format('Y-m-d H');
            
            $this->noiseDataByTimestamp[$key] = $data;
            
            // Group by date for faster range queries
            if (!isset($this->noiseDataByDate[$dateKey])) {
                $this->noiseDataByDate[$dateKey] = [];
            }
            $this->noiseDataByDate[$dateKey][] = $data;
            
            // Group by date-hour for even faster 1-hour queries
            if (!isset($this->noiseDataByDateHour[$dateHourKey])) {
                $this->noiseDataByDateHour[$dateHourKey] = [];
            }
            $this->noiseDataByDateHour[$dateHourKey][] = $data;
        }

        Log::info("Loaded {$this->allNoiseData->count()} noise data records for PDF generation in " . round(microtime(true) - $startTime, 2) . " seconds");
    }

    /**
     * Prepare all data for all days (parallel or sequential processing)
     */
    public function prepareAllDaysData(Carbon $startDate, Carbon $endDate): array
    {
        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->copy();
        }

        // Use parallel processing if:
        // 1. Multiple days to process (>1)
        // 2. Parallel processing is enabled (env PARALLEL_PDF_PROCESSING=true)
        $useParallel = count($dates) > 1 && config('app.parallel_pdf_processing', true);
        
        if ($useParallel) {
            return $this->prepareAllDaysDataParallel($dates);
        }
        
        // Use sequential processing for single day or when parallel is disabled
        return $this->prepareAllDaysDataSequential($dates);
    }

    /**
     * Prepare data using parallel processes (Symfony Process)
     */
    private function prepareAllDaysDataParallel(array $dates): array
    {
        $totalDays = count($dates);
        Log::info("Processing {$totalDays} days in parallel using Symfony Process");
        
        $processes = [];
        $allData = [];
        
        // Limit concurrent processes to avoid overwhelming the system
        $maxConcurrent = min($totalDays, 4); // Max 4 parallel processes
        $chunks = array_chunk($dates, $maxConcurrent);
        
        foreach ($chunks as $chunkIndex => $dateChunk) {
            $chunkProcesses = [];
            
            // Start processes for this chunk
            foreach ($dateChunk as $date) {
                $dateString = $date->format('Y-m-d');
                
                // Create artisan command to process one day
                $command = [
                    'php',
                    base_path('artisan'),
                    'pdf:process-day',
                    (string)$this->measurementPoint->id,
                    $dateString
                ];
                
                $process = new Process($command);
                $process->setTimeout(120); // 2 minutes per day
                $process->setWorkingDirectory(base_path());
                
                $chunkProcesses[$dateString] = $process;
                
                // Start the process
                $process->start();
                Log::info("Started process for day {$dateString} (chunk " . ($chunkIndex + 1) . ")");
            }
            
            // Wait for all processes in this chunk to complete
            foreach ($chunkProcesses as $dateString => $process) {
                $process->wait();
                
                if ($process->isSuccessful()) {
                    // Parse the JSON output from the artisan command
                    $output = trim($process->getOutput());
                    
                    // Remove any debug output before JSON
                    $jsonStart = strpos($output, '{');
                    if ($jsonStart !== false) {
                        $output = substr($output, $jsonStart);
                    }
                    
                    $dayData = json_decode($output, true);
                    
                    if ($dayData && is_array($dayData)) {
                        $allData[$dateString] = $dayData;
                        Log::info("✓ Completed process for day {$dateString}");
                    } else {
                        Log::warning("Failed to parse JSON output for day {$dateString}, falling back to sequential");
                        // Fallback to sequential processing for this day
                        $allData[$dateString] = $this->prepareDayData(Carbon::parse($dateString));
                    }
                } else {
                    $errorOutput = $process->getErrorOutput();
                    Log::error("Process failed for day {$dateString}: {$errorOutput}");
                    Log::warning("Falling back to sequential processing for day {$dateString}");
                    // Fallback to sequential processing for this day
                    $allData[$dateString] = $this->prepareDayData(Carbon::parse($dateString));
                }
            }
        }
        
        Log::info("Parallel processing completed for {$totalDays} days");
        return $allData;
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
     * A "day" runs from 7am to 6:55am next day, so we need slots from current day + next day until 6:55am
     * 
     * Can be called standalone (will load its own data) or after loadNoiseData has been called
     */
    public function prepareDayData(Carbon $date): array
    {
        // If data hasn't been loaded yet, load it for this day
        if (empty($this->noiseDataByTimestamp)) {
            $startDate = $date->copy()->subDay();
            $endDate = $date->copy()->addDays(2);
            $this->loadNoiseData($startDate, $endDate);
        }
        
        $startTime = microtime(true);
        
        $dayData = [
            'slots' => [],
            'hourly' => [],
            'dose' => [],
            'max' => [],
            '12h' => []
        ];

        // Generate all 5-minute slots for the day (288 slots spanning two calendar dates)
        $slotStart = microtime(true);
        
        // Current day: 00:00 to 23:55
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
        
        // Next day: 00:00 to 06:55 (to complete the "day" which ends at 06:55)
        $nextDay = $date->copy()->addDay();
        for ($hour = 0; $hour < 7; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 5) {
                // Only go up to 06:55
                if ($hour == 6 && $minute == 55) {
                    $slotDate = new DateTime($nextDay->format('Y-m-d') . ' 06:55:00');
                    $key = $slotDate->format('Y-m-d H:i:s');
                    $dayData['slots'][$key] = $this->get5MinuteData($slotDate);
                    break;
                }
                
                $slotDate = new DateTime($nextDay->format('Y-m-d') . sprintf(' %02d:%02d:00', $hour, $minute));
                $key = $slotDate->format('Y-m-d H:i:s');
                $dayData['slots'][$key] = $this->get5MinuteData($slotDate);
            }
            
            // Calculate hourly Leq for next day hours (0-6)
            $hourDate = new DateTime($nextDay->format('Y-m-d') . sprintf(' %02d:00:00', $hour));
            $dayData['hourly'][24 + $hour] = $this->calculate1HourLeq($hourDate);
        }
        
        Log::info("  - Slots + Hourly: " . round((microtime(true) - $slotStart) * 1000, 2) . "ms");

        // Calculate 12-hour Leq for 7am and 7pm
        $morning = new DateTime($date->format('Y-m-d') . ' 07:00:00');
        $evening = new DateTime($date->format('Y-m-d') . ' 19:00:00');
        
        $dayData['12h']['morning'] = $this->calculate12HourLeq($morning);
        $dayData['12h']['evening'] = $this->calculate12HourLeq($evening);

        // Calculate dose percentages for EVERY hour (using XX:55:00 timestamp)
        // This matches the view logic where $slotDate is the last 5-min slot of each hour
        $doseStart = microtime(true);
        for ($hour = 0; $hour < 24; $hour++) {
            $hourTime = new DateTime($date->format('Y-m-d') . sprintf(' %02d:55:00', $hour));
            $dayData['dose'][$hour] = $this->calculateDose($hourTime);
        }
        // Dose for next day hours (0-6)
        for ($hour = 0; $hour < 7; $hour++) {
            $hourTime = new DateTime($nextDay->format('Y-m-d') . sprintf(' %02d:55:00', $hour));
            Log::info("About to calculate dose for next day hour: {$hourTime->format('Y-m-d H:i')}");
            $dayData['dose'][24 + $hour] = $this->calculateDose($hourTime);
        }
        Log::info("  - Dose: " . round((microtime(true) - $doseStart) * 1000, 2) . "ms");

        // Calculate max values for EVERY hour (using XX:55:00 timestamp)
        $maxStart = microtime(true);
        for ($hour = 0; $hour < 24; $hour++) {
            $hourTime = new DateTime($date->format('Y-m-d') . sprintf(' %02d:55:00', $hour));
            $dayData['max'][$hour] = $this->calculateMax($hourTime);
        }
        // Max for next day hours (0-6)
        for ($hour = 0; $hour < 7; $hour++) {
            $hourTime = new DateTime($nextDay->format('Y-m-d') . sprintf(' %02d:55:00', $hour));
            $dayData['max'][24 + $hour] = $this->calculateMax($hourTime);
        }
        Log::info("  - Max: " . round((microtime(true) - $maxStart) * 1000, 2) . "ms");

        Log::info("Day {$date->format('Y-m-d')} processed in " . round((microtime(true) - $startTime) * 1000, 2) . "ms");

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
            'leq_data' => $leq > 0 ? number_format(round($leq, 1), 1) : '0.0',
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
            'leq_data' => $leq > 0 ? number_format(round($leq, 1), 1) : '0.0',
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
        $timeStr = $time->format('Y-m-d H:i');
        $hour = (int)$time->format('H');
        
        if ($hour >= 0 && $hour < 7) {
            Log::info("calculateDose ENTRY: time={$timeStr}, hour={$hour}");
        }
        
        // Clone $time before passing to check_12_1_hour_limit_type to avoid modification
        $timeClone = clone $time;
        [$decision, $last_data_datetime] = $this->measurementPoint->soundLimit->check_12_1_hour_limit_type($timeClone);
        
        if ($hour >= 0 && $hour < 7) {
            Log::info("  -> After check_12_1_hour_limit_type: original time still={$time->format('Y-m-d H:i')}");
        }
        
        // Debug logging for early morning hours
        if ($hour >= 0 && $hour < 7) {
            Log::info("  -> Early morning hour detected, decision={$decision}");
        }
        
        // Use pre-fetched data instead of querying database
        if ($decision == '12h') {
            // Calculate 12-hour Leq using pre-fetched data
            [$startDateTime, $endDateTime] = $this->getTimeslotStartEnd($time);
            $timeslotData = $this->getDataBetween($startDateTime, $endDateTime);
            $num_blanks = 144 - count($timeslotData);
            $calculatedLeq = $this->calculateLeq($timeslotData);
            $limit = $this->measurementPoint->soundLimit->leq12h_limit($last_data_datetime);
            
            // Debug logging for early morning hours
            if ($hour >= 0 && $hour < 7) {
                Log::info("  -> 12h range={$startDateTime->format('Y-m-d H:i')} to {$endDateTime->format('Y-m-d H:i')}, found=" . count($timeslotData) . " records, blanks={$num_blanks}, Leq={$calculatedLeq}, limit={$limit}");
            }
            
            $calculated_dose_percentage = $this->measurementPoint->soundLimit->calculate_dose_perc(
                $calculatedLeq, 
                $limit, 
                $num_blanks, 
                144
            );
            
            // Log the final dose percentage
            if ($hour >= 0 && $hour < 7) {
                Log::info("  -> Dose percentage: {$calculated_dose_percentage}%");
            }
        } else {
            // Calculate 1-hour Leq using pre-fetched data
            // Create new DateTime object to avoid modifying the original
            $dateStr = $time->format('Y-m-d H:i:s');
            $hourFromTime = (int)$time->format('H');
            
            $startTime = DateTime::createFromFormat('Y-m-d H:i:s', substr($dateStr, 0, 10) . sprintf(' %02d:00:00', $hourFromTime));
            $endTime = DateTime::createFromFormat('Y-m-d H:i:s', substr($dateStr, 0, 10) . sprintf(' %02d:59:00', $hourFromTime));
            
            if ($hour >= 0 && $hour < 7) {
                Log::info("  -> 1h range for hour {$hourFromTime}: startTime={$startTime->format('Y-m-d H:i')}, endTime={$endTime->format('Y-m-d H:i')}");
            }
            
            $hourData = $this->getDataBetween($startTime, $endTime);
            $num_blanks = 12 - count($hourData);
            $calculatedLeq = $this->calculateLeq($hourData);
            $limit = $this->measurementPoint->soundLimit->leq1h_limit($last_data_datetime);
            
            // Debug logging for early morning hours
            if ($hour >= 0 && $hour < 7) {
                Log::info("  -> 1h range={$startTime->format('Y-m-d H:i')} to {$endTime->format('Y-m-d H:i')}, found=" . count($hourData) . " records, blanks={$num_blanks}, Leq={$calculatedLeq}, limit={$limit}");
            }
            
            $calculated_dose_percentage = $this->measurementPoint->soundLimit->calculate_dose_perc(
                $calculatedLeq, 
                $limit, 
                $num_blanks, 
                12
            );
            
            // Log the final dose percentage
            if ($hour >= 0 && $hour < 7) {
                Log::info("  -> Dose percentage: {$calculated_dose_percentage}%");
            }
        }

        // Return '0.00' if all data is missing (no real calculation possible)
        $maxBlanks = $decision == '12h' ? 144 : 12;
        if ($num_blanks >= $maxBlanks) {
            return [
                'leq_data' => '0.00',
                'should_alert' => false,
                'decision' => $decision
            ];
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
        
        // Check if dose is '0.00' (no data) - return 'N.A.' for max
        if ($doseData['leq_data'] === '0.00') {
            return ['leq_data' => 'N.A.', 'should_alert' => false];
        }
        
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
     * OPTIMIZED: Uses date-hour indexing for 1-hour queries, date indexing for longer periods
     */
    private function getDataBetween(DateTime $start, DateTime $end): Collection
    {
        $startKey = $start->format('Y-m-d H:i:s');
        $endKey = $end->format('Y-m-d H:i:s');
        
        // Check if this is a 1-hour query (most common case)
        $duration = $end->getTimestamp() - $start->getTimestamp();
        if ($duration <= 3660) { // 1 hour + 1 minute buffer
            // Use hour-level index for fast lookup (only scan ~12 records instead of ~288)
            $dateHourKey = $start->format('Y-m-d H');
            if (isset($this->noiseDataByDateHour[$dateHourKey])) {
                $result = [];
                foreach ($this->noiseDataByDateHour[$dateHourKey] as $data) {
                    $dataKey = $data->received_at->format('Y-m-d H:i:s');
                    if ($dataKey >= $startKey && $dataKey <= $endKey) {
                        $result[] = $data;
                    }
                }
                return collect($result);
            }
            return collect([]);
        }
        
        // For longer periods (12h), use date-based logic
        $startDate = $start->format('Y-m-d');
        $endDate = $end->format('Y-m-d');
        
        $result = [];
        $currentDate = new DateTime($startDate);
        $endDateTime = new DateTime($endDate);
        
        while ($currentDate <= $endDateTime) {
            $dateKey = $currentDate->format('Y-m-d');
            
            if (isset($this->noiseDataByDate[$dateKey])) {
                foreach ($this->noiseDataByDate[$dateKey] as $data) {
                    $dataKey = $data->received_at->format('Y-m-d H:i:s');
                    if ($dataKey >= $startKey && $dataKey <= $endKey) {
                        $result[] = $data;
                    }
                }
            }
            
            $currentDate->modify('+1 day');
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
            // For evening/night periods, the 12h window spans two calendar days
            // For 12am-7am, we need the PREVIOUS day's evening (e.g., Dec 13 19:00 to Dec 14 06:59)
            if ($time_range === '12am_7am') {
                $last_noise_data_start_date = (new DateTime($last_noise_data_date))->modify('-1 day')->format('Y-m-d');
                $last_noise_data_end_date = $last_noise_data_date;
            } else {
                // For 7pm-10pm and 10pm-12am, use current day evening to next day morning
                $last_noise_data_start_date = $last_noise_data_date;
                $last_noise_data_end_date = (new DateTime($last_noise_data_date))->modify('+1 day')->format('Y-m-d');
            }
        } else {
            $last_noise_data_start_date = $last_noise_data_date;
            $last_noise_data_end_date = $last_noise_data_date;
        }

        $start_time = new DateTime($last_noise_data_start_date . ' ' . $timeSlots[$time_range]['start']);
        $end_time = new DateTime($last_noise_data_end_date . ' ' . $timeSlots[$time_range]['end']);

        return [$start_time, $end_time];
    }
}

