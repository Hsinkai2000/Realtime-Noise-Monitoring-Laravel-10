<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>

        @if ($measurementPoint->noiseMeter)
            Report_{{ $measurementPoint->noiseMeter->serial_number }}_{{ $start_date->format('dmY') }}-{{ $end_date->format('dmY') }}
        @else
            Report_{{ $measurementPoint->job_number }}_{{ $start_date->format('dmY') }}-{{ $end_date->format('dmY') }}
        @endif
    </title>

    <link href="{{ public_path('css/base.css') }}" rel="stylesheet">
    <link href="{{ public_path('css/pdf.css') }}" rel="stylesheet">
    {{-- Removed app.js for PDF - not needed --}}
    <style>
        .reportGraph {
            display: block;
            margin: 0 auto;
            width: 900px;
            height: 200px;
        }
    </style>

    {{-- Load from local files for faster PDF generation --}}
    <script src="{{ public_path('vendor/moment/moment.min.js') }}"></script>
    <script src="{{ public_path('vendor/chartjs/Chart.bundle.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<style>
    body {
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif !important;
    }

    .bottom-bar {
        position: absolute;
        bottom: 0;
        width: 100%;
        background-color: red;
        height: 30px
    }

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
        background-color: white;
    }

    table .w-5 {
        width: 5%;
    }

    table .w-10 {
        width: 10%;
    }

    table .w-45 {
        width: 45%;
    }

    table td,
    th {
        padding: 6px;
    }

    .text-danger {
        color: red;
    }

    .time_col {
        min-width: 50px;
    }
</style>

<body>
    <div class="container d-flex flex-column justify-content-center text-center">
        <div>
            <h1>Noise Data</h1>

            @if ($measurementPoint->noiseMeter)
                <h2>{{ strtoupper(
                    $measurementPoint->point_name .
                        ': ' .
                        $measurementPoint->noiseMeter->brand .
                        ' S/N ' .
                        $measurementPoint->noiseMeter->serial_number,
                ) }}
                </h2>
            @else
                <h2>{{ strtoupper($measurementPoint->point_name) }}</h2>
            @endif
            <h2>Date: {{ \Carbon\Carbon::parse($start_date)->format('d-m-Y') }} -
                {{ \Carbon\Carbon::parse($end_date)->format('d-m-Y') }}</h2>
        </div>
        <br />
        @include('pdfs.partials-report-details', [
            'measurementPoint' => $measurementPoint,
            'contacts' => $contacts,
        ])

    </div>

    @for ($date = \Carbon\Carbon::parse($start_date); $date->lte(\Carbon\Carbon::parse($end_date)); $date->addDay())
        <div class="container h-100" style="page-break-before: always;">
            <div class="text-center">
                <?php $firstDataOfDay = $measurementPoint->getFirstDataOfDay($date->format('d-m-Y')); ?>
                @if ($firstDataOfDay)
                    <h1>Noise Data</h1>
                    <h3>Noise Device Serial: {{ $firstDataOfDay }}</h3>
                    <h3>Date: {{ $date->format('d-m-Y') }}</h3>
                @else
                    <h1>Noise Data</h1>
                    <h3>Date: {{ $date->format('d-m-Y') }}</h3>
                @endif
            </div>
            <br>
            <x-pdfs.partials-report-data :measurementPoint="$measurementPoint" :date="$date" :prepared-data="$preparedData" />
            <br>
            <x-pdfs.partials-report-chart :measurementPoint="$measurementPoint" :date="$date->copy()" />
        </div>
    @endfor

    <script type="text/javascript">
        'use strict';
        (function(setLineDash) {
            CanvasRenderingContext2D.prototype.setLineDash = function() {
                if (!arguments[0].length) {
                    arguments[0] = [1, 0];
                }
                // Now, call the original method
                return setLineDash.apply(this, arguments);
            };
        })(CanvasRenderingContext2D.prototype.setLineDash);
        Function.prototype.bind = Function.prototype.bind || function(thisp) {
            var fn = this;
            return function() {
                return fn.apply(thisp, arguments);
            };
        };
    </script>
</body>

</html>
