<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        Report_{{ $measurementPoint->noiseMeter->serial_number }}_{{ $start_date->format('dmY') }}-{{ $end_date->format('dmY') }}
    </title>

    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- @vite(['resources/scss/pdf.scss', 'resources/js/pdf.js', 'resources/js/app.js']) --}}


    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<style>
    body {
        font-size: 14px;
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
            <h2>{{ strtoupper(
                $measurementPoint->point_name .
                    ': ' .
                    $measurementPoint->noiseMeter->brand .
                    ' S/N ' .
                    $measurementPoint->noiseMeter->serial_number,
            ) }}
            </h2>
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
                <h1>Noise Data</h1>
                <h3>Noise Device ID: {{ $measurementPoint->noiseMeter->serial_number }}</h3>
                <h3>Date: {{ $date->format('d-m-Y') }}</h3>
            </div>
            <div>
                <br />
                <x-pdfs.partials-report-data :measurementPoint="$measurementPoint" :date="$date" />
            </div>
        </div>
    @endfor
    <script src="{{ asset('js/pdf.js') }}" async defer></script>
</body>

</html>
