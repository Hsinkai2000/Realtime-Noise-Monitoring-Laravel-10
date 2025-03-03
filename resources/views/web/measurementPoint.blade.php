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

    {{-- 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js">
    </script> --}}

    <style>
        .reportGraph {
            width: 100%;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css"
        integrity="sha512-/zs32ZEJh+/EO2N1b0PEdoA10JkdC3zJ8L5FTiQu82LR9S/rOQNfQN7U59U9BC12swNeRAz3HSzIL2vpp4fv3w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"
        integrity="sha512-mf78KukU/a8rjr7aBRvCa2Vwg/q0tUjJhLtcK53PHEbFwCEqQ5durlzvVTgQgKpv+fyNMT6ZQT1Aq6tpNqf1mg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <link rel="stylesheet" href="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css">
    </link>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>

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
                    onclick="openModal('measurementPointModal')">Edit</button>
            </div>
            @if (Auth::user()->isAdmin())
                <div class="right-groups d-flex align-items-center">
                    <button id="delete-data-button" class="btn btn-light text-danger border shadow-sm" type="button"
                        onclick='openModal("deleteConfirmationModal","{{ $measurementPoint->point_name }}" )'>Delete
                    </button>
                </div>
            @endif
        </div>

        <div class="measurementPoint-information">
            <div class="row w-100 pb-3">
                <div class="col-md-2 col-sm-4">
                    Point Name:
                </div>
                <div class="col-md-9 col-6">
                    {{ $measurementPoint->point_name }}
                </div>
            </div>
            <div class="row w-100 pb-3">
                <div class="col-md-2 col-sm-4">
                    Device Location:
                </div>
                <div class="col-md-9 col-sm-12">
                    {{ $measurementPoint->device_location }}
                </div>
            </div>
            <div class="row w-100 pb-3">
                <div class="col-md-2 col-sm-4">
                    Remarks:
                </div>
                <div class="col-md-9 col-sm-12">
                    {{ $measurementPoint->remarks }}
                </div>
            </div>
            <div class="row w-100 pb-3">
                <div class="col-md-2 col-sm-4">
                    Category:
                </div>
                <div class="col-md-9 col-sm-12">
                    {{ $measurementPoint->soundLimit->category }}
                </div>
            </div>
            <div class="row w-100 pb-3">
                <div class="col-md-2 col-sm-4">
                    Sound Limits:
                </div>
                <div class="col-md-4 col-sm-12">
                    @if ($measurementPoint->soundLimit->category == 'Residential')
                        <table class="table-bordered w-100">
                            <tr>
                                <th></th>
                                <th>7am-7pm</th>
                                <th>7pm-10pm</th>
                                <th>10pm-12am</th>
                                <th>12am-7am</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Mon-Sat</th>
                                <td>{{ $measurementPoint->soundLimit->mon_sat_7am_7pm_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->mon_sat_7pm_10pm_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->mon_sat_10pm_12am_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->mon_sat_12am_7am_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                            </tr>
                            <tr>
                                <td>{{ $measurementPoint->soundLimit->mon_sat_7am_7pm_leq12hr }} dBA <br>(Leq 12 hrs)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->mon_sat_7pm_10pm_leq12hr }} dBA <br>(Leq 1 hr)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->mon_sat_10pm_12am_leq12hr }} dBA <br>(Leq 1 hr)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->mon_sat_12am_7am_leq12hr }} dBA <br>(Leq 1 hr)
                                </td>
                            </tr>
                            <tr>
                                <th rowspan="2">Sun/Ph</th>
                                <td>{{ $measurementPoint->soundLimit->sun_ph_7am_7pm_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->sun_ph_7pm_10pm_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->sun_ph_10pm_12am_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->sun_ph_12am_7am_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                            </tr>
                            <tr>
                                <td>{{ $measurementPoint->soundLimit->sun_ph_7am_7pm_leq12hr < 140 ? $measurementPoint->soundLimit->sun_ph_7am_7pm_leq12hr . ' dBA' : '-' }}
                                    (Leq 12 hrs)
                                </td>
                                <td colspan="3">
                                    {{ $measurementPoint->soundLimit->sun_ph_7pm_10pm_leq12hr < 140 ? $measurementPoint->soundLimit->sun_ph_7pm_10pm_leq12hr . ' dBA' : '-' }}
                                    <br>(Leq 12 hrs)

                                </td>
                            </tr>
                        </table>
                    @else
                        <table class="table-bordered w-100">
                            <tr>
                                <th>{{ 'Category: ' . $measurementPoint->soundLimit->category }}</th>
                                <th>7am-7pm</th>
                                <th>7pm-10pm</th>
                                <th>10pm-12am</th>
                                <th>12am-7am</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Mon-Sat</th>
                                <td>{{ $measurementPoint->soundLimit->mon_sat_7am_7pm_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->mon_sat_7pm_10pm_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->mon_sat_10pm_12am_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->mon_sat_12am_7am_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                            </tr>
                            <tr>
                                <td>{{ $measurementPoint->soundLimit->mon_sat_7am_7pm_leq12hr }} dBA <br>(Leq 12 hrs)
                                </td>
                                <td colspan="3">{{ $measurementPoint->soundLimit->mon_sat_7pm_10pm_leq12hr }} dBA
                                    <br>(Leq
                                    12
                                    hrs)
                                </td>
                            </tr>
                            <tr>
                                <th rowspan="2">Sun/Ph</th>
                                <td>{{ $measurementPoint->soundLimit->sun_ph_7am_7pm_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->sun_ph_7pm_10pm_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->sun_ph_10pm_12am_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                                <td>{{ $measurementPoint->soundLimit->sun_ph_12am_7am_leq5min }} dBA <br>(Leq 5 mins)
                                </td>
                            </tr>
                            <tr>
                                <td>{{ $measurementPoint->soundLimit->sun_ph_7am_7pm_leq12hr < 140 ? $measurementPoint->soundLimit->sun_ph_7am_7pm_leq12hr . ' dBA' : '-' }}
                                    (Leq 12 hrs)
                                </td>
                                <td colspan="3">
                                    {{ $measurementPoint->soundLimit->sun_ph_7pm_10pm_leq12hr < 140 ? $measurementPoint->soundLimit->sun_ph_7pm_10pm_leq12hr . ' dBA' : '-' }}
                                    (Leq 12 hrs)
                                </td>
                            </tr>
                        </table>
                    @endif
                </div>
            </div>
        </div>



        <div class="mb-3">
            <h6>Noise Meter:</h6>
            <div id='noise_meter_table'>
            </div>
        </div>

        <div class="mb-3">
            @if (Auth::user()->isAdmin())
                <h6>Concentrator</h6>
                <div id='concentrator_table'>
                </div>
            @endif
        </div>
        <br />
        <div class="d-flex justify-content-center">
            <button class="btn btn-primary bg-light text-primary px-4 me-3 shadow-sm"
                onclick="openModal('viewPdfModal')">View Report</button>
        </div>
        <br>

        <x-pdfs.partials-report-chart :measurementPoint="$measurementPoint" :date="now()" />
    </div>

    <x-confirmation-modal />
    <x-delete-confirmation-modal :type="$measurementPoint->point_name" />
    <x-measurementPoint.measurement-point-modal :measurementPoint="$measurementPoint" />
    <x-pdfs.view-pdf-component />

</body>

<script>
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


    $(document).ready(function() {
        $('#selectConcentrator').select2({
            dropdownParent: $('#measurementPointModal'),
        });

        $('#selectNoiseMeter').select2({
            dropdownParent: $('#measurementPointModal'),
        });
    });

    window.measurementPointData = @json($measurementPoint);
    window.noise_meter = @json($measurementPoint->noiseMeter);
    window.concentrator = @json($measurementPoint->concentrator);
    window.soundLimit = @json($measurementPoint->soundLimit);
    window.admin = @json(Auth::user()->isAdmin());
</script>
<script src="{{ asset('js/measurement_point.js') }}" async defer></script>

</html>
