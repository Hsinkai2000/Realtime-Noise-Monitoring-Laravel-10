<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geoscan | {{ $measurementPoint->point_name }}</title>

    <!-- Include jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Tabulator CSS from CDN -->
    <link href="https://unpkg.com/tabulator-tables@5.4.3/dist/css/tabulator.min.css" rel="stylesheet" />
    <!-- Include Tabulator JS from CDN -->
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.3/dist/js/tabulator.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.5.3/air-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.5.3/air-datepicker.min.css">

    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/measurement_point.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- @vite(['resources/scss/measurement_point.scss', 'resources/js/app.js', 'resources/js/measurement_point.js']) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <x-nav.navbar projectId="{{ $measurementPoint->project->id }}" />

    <div class="container-fluid p-3 p-md-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 d-flex align-items-center">
                @if (Auth::user()->isAdmin())
                    <li class="breadcrumb-item"><a href="{{ route('project.admin') }}">Projects</a></li>
                @endif
                <li class="breadcrumb-item"><a
                        href="{{ route('project.show', $measurementPoint->project->id) }}">{{ $measurementPoint->project->job_number }}</a>
                </li>
                <li class="breadcrumb-item d-flex align-items-center"><a href="#"
                        class="href h3 text-decoration-none">{{ $measurementPoint->point_name }}</a></li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center heading-group mb-3">
            <div class="left-group d-flex align-items-center">
                <h3 class="mb-0 me-2">Measurement Point Information</h3>
                <button type="button" id="edit-button" class="d-inline btn btn-dark text-light shadow-sm"
                    data-bs-toggle="modal" data-bs-target="#measurementPointModal">Edit</button>
            </div>

            <div class="right-groups d-flex align-items-center">
                <button id="delete-data-button" class="btn btn-light text-danger border shadow-sm" type="button"
                    onclick='openModal("deleteConfirmationModal","{{ $measurementPoint->point_name }}" )'>Delete
                    Project</button>
            </div>
        </div>
        <table class="table">
            <tr>
                <th scope='row'>Point Name</th>
                <td scope='row'>{{ $measurementPoint->point_name }}</td>
            </tr>
            <tr>
                <th scope='row'>Device Location</th>
                <td scope='row'>{{ $measurementPoint->device_location }}</td>
            </tr>
            <tr>
                <th scope='row'>Remarks</th>
                <td scope='row'>{{ $measurementPoint->remarks }}</td>
            </tr>
        </table>

        <h6>Noise Meter</h6>
        <div id='noise_meter_table'>
        </div>

        @if (Auth::user()->isAdmin())
            <h6>Concentrator</h6>
            <div id='concentrator_table'>
            </div>
        @endif

        <br />
        <div class="d-flex justify-content-center">
            <button class="btn btn-primary bg-light text-primary px-4 me-3 shadow-sm"
                onclick="openModal('viewPdfModal')">View Report</button>
        </div>
    </div>

    <x-delete-confirmation-modal :type="$measurementPoint->point_name" />
    <x-measurementPoint.measurement-point-modal :measurementPoint="$measurementPoint" />
    <x-pdfs.view-pdf-component />

</body>

<script>
    window.measurementPointData = @json($measurementPoint);
    window.noise_meter = @json($measurementPoint->noiseMeter);
    window.concentrator = @json($measurementPoint->concentrator);
    window.admin = @json(Auth::user()->isAdmin());
</script>
<script src="{{ asset('js/measurement_point-test.js') }}" async defer></script>

</html>
