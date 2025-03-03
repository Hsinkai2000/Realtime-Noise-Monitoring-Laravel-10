{{-- filepath: /Users/nghsinkai/Documents/Coding Projects/GeoScan/geoscan-backend-10/resources/views/components/pdfs/partials-report-chart.blade.php --}}
<?php

use Carbon\Carbon;

// Define the maximum value for scaling
$maxValue = 120; // Adjust this based on your expected maximum value

$generateLimitData = function (Carbon $date, $measurementPoint, $maxValue) {
    $data = [];
    $start = new DateTime($date->format('Y-m-d') . 'T07:00:00');
    $end = new DateTime($date->format('Y-m-d') . 'T06:59:00 +1 day');

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

        // Scale the data to fit within the 0-61 range
        $scaledValue = round(($yValue / $maxValue) * 61);
        $data[] = $scaledValue;
    }
    return $data;
};

$generateNoiseData = function (Carbon $date, $measurementPoint, $noiseData, $maxValue) {
    $data = [];
    $start = new DateTime($date->format('Y-m-d') . 'T07:00:00');
    $end = new DateTime($date->format('Y-m-d') . 'T06:59:00 +1 day');

    $interval = new DateInterval('PT5M');
    $period = new DatePeriod($start, $interval, $end);

    foreach ($period as $time) {
        $data[] = null;
    }

    foreach ($noiseData as $item) {
        $receiveAt = new DateTime($item->received_at);
        $startTime = new DateTime($date->format('Y-m-d') . 'T07:00:00');
        $diff = $receiveAt->getTimestamp() - $startTime->getTimestamp();
        $index = floor($diff / (5 * 60));

        if ($index >= 0 && $index < count($data)) {
            $yValue = $item->leq;
            // Scale the data to fit within the 0-61 range
            $scaledValue = round(($yValue / $maxValue) * 61);
            $data[$index] = $scaledValue;
        }
    }

    return $data;
};

// Define the simple encoding characters
$simpleEncoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

// Function to encode the data using simple encoding
$encodeSimple = function ($data) use ($simpleEncoding) {
    $encoded = '';
    foreach ($data as $value) {
        if ($value === null) {
            $encoded .= '_'; // Use '_' for null values
        } else {
            $encoded .= $simpleEncoding[$value];
        }
    }
    return $encoded;
};

$limitData = $generateLimitData($date, $measurementPoint, $maxValue);
$noiseData = $generateNoiseData($date, $measurementPoint, $noiseData, $maxValue);

$encodedLimitData = $encodeSimple($limitData);
$encodedNoiseData = $encodeSimple($noiseData);

// Build the chart URL
$chartUrl = 'https://chart.googleapis.com/chart?';
$chartParams = [
    'cht' => 'lc', // Line chart
    'chs' => '800x400', // Chart size
    'chd' => 's:' . $encodedLimitData . ',' . $encodedNoiseData, // Chart data (simple encoding)
    'chxl' => '0:|7|8|9|10|11|12|13|14|15|16|17|18|19|20|21|22|23|0|1|2|3|4|5|6', // Shortened X-axis labels
    'chxt' => 'x,y', // Axis to display
    'chco' => 'FF0000,0000FF', // Line colors (Red, Blue)
    'chdl' => 'Limit|LAeq 5min', // Legend labels
    'chtt' => 'Noise Data', // Chart title
    'chds' => '0,' . $maxValue, // Data scaling
];

$chartUrl .= http_build_query($chartParams);
?>

<div class="reportGraph">
    HELLO
    <img alt="Google Chart" src="{{ $chartUrl }}" style="width: 800px; height: 400px;">
</div>
