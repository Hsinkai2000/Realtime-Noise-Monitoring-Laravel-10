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

    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- @vite(['resources/scss/pdf.scss', 'resources/js/pdf.js', 'resource ss/js/app.js']) --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    <style>
        .reportGraph {
            width: 900px
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<style>
    body {
        font-size: 14px;
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
        padding: 10px;
    }

    .text-danger {
        color: red;
    }

    .time_col {
        min-width: 50px;
    }
</style>

<body>
    <div class="container d-flex flex-column justify-content-center text-center pt-5">
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
        <div class="container mt-3" style="page-break-before: always;">
            <div class="text-center">
                <?php $res = $measurementPoint->getFirstDataOfDay($date->format('d-m-Y')); ?>
                @if ($res)
                    <h1>Noise Data</h1>
                    <h3>Noise Device Serial: {{ $measurementPoint->getFirstDataOfDay($date->format('d-m-Y')) }}</h3>
                    <h3>Date: {{ $date->format('d-m-Y') }}</h3>
                @else
                    <h1>Noise Data</h1>
                    <h3>Date: {{ $date->format('d-m-Y') }}</h3>
                @endif
            </div>
            <div>
                <br />
                <x-pdfs.partials-report-data :measurementPoint="$measurementPoint" :date="$date" />
            </div>

            <br>

            {{-- <x-pdfs.partials-report-chart :measurementPoint="$measurementPoint" :date="$date->copy()" /> --}}

        </div>
    @endfor
    <script src="{{ asset('js/pdf.js') }}" async defer></script>


    <script type="text/javascript">
        // wkhtmltopdf 0.12.5 crash fix.
        // https://github.com/wkhtmltopdf/wkhtmltopdf/issues/3242#issuecomment-518099192
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
