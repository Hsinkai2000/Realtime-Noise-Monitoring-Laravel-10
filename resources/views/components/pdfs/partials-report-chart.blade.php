{{-- filepath: /Users/nghsinkai/Documents/Coding Projects/GeoScan/geoscan-backend-10/resources/views/components/pdfs/partials-report-chart.blade.php --}}
<div class="reportGraph">
    HELLO
    <img alt="Google Chart" src="{{ $chartUrl }}" style="width: 800px; height: 400px;">
</div>

<?php
$chartData = [
    'Limit' => generateLimitData(),
    'LAeq 5min' => generateNoiseData(),
];

function generateLimitData()
{
    $data = [];
    $start = new DateTime(date('Y-m-d', strtotime($date)) . 'T07:00:00');
    $end = new DateTime(date('Y-m-d', strtotime($date)) . 'T06:59:00 +1 day');

    $interval = new DateInterval('PT5M');
    $period = new DatePeriod($start, $interval, $end);

    foreach ($period as $time) {
        $dayOfWeek = $time->format('w'); // 0 (Sunday) to 6 (Saturday)
        $isWeekend = $dayOfWeek == 0;
        $hours = $time->format('H');

        if (!$isWeekend) {
            if ($hours >= 7 && $hours < 19) {
                $yValue = $measurementPoint->soundLimit->mon_sat_7am_7pm_leq5min;
            } elseif ($hours >= 19 && $hours < 22) {
                $yValue = $measurementPoint->soundLimit->mon_sat_7pm_10pm_leq5min;
            } elseif ($hours >= 22 && $hours < 24) {
                $yValue = $measurementPoint->soundLimit->mon_sat_10pm_12am_leq5min;
            } else {
                $yValue = $measurementPoint->soundLimit->mon_sat_12am_7am_leq5min;
            }
        } else {
            if ($hours >= 7 && $hours < 19) {
                $yValue = $measurementPoint->soundLimit->sun_ph_7am_7pm_leq5min;
            } elseif ($hours >= 19 && $hours < 22) {
                $yValue = $measurementPoint->soundLimit->sun_ph_7pm_10pm_leq5min;
            } elseif ($hours >= 22 && $hours < 24) {
                $yValue = $measurementPoint->soundLimit->sun_ph_10pm_12am_leq5min;
            } else {
                $yValue = $measurementPoint->soundLimit->sun_ph_12am_7am_leq5min;
            }
        }

        $data[] = $yValue;
    }
    return $data;
}

function generateNoiseData()
{
    $data = [];
    $start = new DateTime(date('Y-m-d', strtotime($date)) . 'T07:00:00');
    $end = new DateTime(date('Y-m-d', strtotime($date)) . 'T06:59:00 +1 day');

    $interval = new DateInterval('PT5M');
    $period = new DatePeriod($start, $interval, $end);

    foreach ($period as $time) {
        $data[] = null;
    }

    foreach ($noiseData as $item) {
        $receiveAt = new DateTime($item->received_at);
        $startTime = new DateTime(date('Y-m-d', strtotime($date)) . 'T07:00:00');
        $diff = $receiveAt->getTimestamp() - $startTime->getTimestamp();
        $index = floor($diff / (5 * 60));

        if ($index >= 0 && $index < count($data)) {
            $data[$index] = $item->leq;
        }
    }

    return $data;
}

// Build the chart URL
$chartUrl = 'https://chart.googleapis.com/chart?';
$chartParams = [
    'cht' => 'lc', // Line chart
    'chs' => '800x400', // Chart size
    'chd' => 't:' . implode(',', $chartData['Limit']) . '|' . implode(',', $chartData['LAeq 5min']), // Chart data
    'chxl' => '0:|7:00|8:00|9:00|10:00|11:00|12:00|13:00|14:00|15:00|16:00|17:00|18:00|19:00|20:00|21:00|22:00|23:00|0:00|1:00|2:00|3:00|4:00|5:00|6:00', // X-axis labels
    'chxt' => 'x,y', // Axis to display
    'chco' => 'FF0000,0000FF', // Line colors (Red, Blue)
    'chdl' => 'Limit|LAeq 5min', // Legend labels
    'chtt' => 'Noise Data', // Chart title
];

$chartUrl .= http_build_query($chartParams);
?>
