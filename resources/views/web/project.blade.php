<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geoscan | {{ $project->job_number }}</title>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://unpkg.com/tabulator-tables@5.4.3/dist/css/tabulator.min.css" rel="stylesheet" />
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.3/dist/js/tabulator.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css"
        integrity="sha512-aD9ophpFQ61nFZP6hXYu4Q/b/USW7rpLCQLX6Bi0WJHXNO7Js/fUENpBQf/+P4NtpzNX0jSgR5zVvPOJp+W2Kg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"
        integrity="sha512-4MvcHwcbqXKUHB6Lx3Zb5CEAVoE9u84qN+ZSMM6s7z8IeJriExrV3ND5zRze9mxNlABJ6k864P/Vl8m0Sd3DtQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/project.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>

    <x-nav.navbar projectId="{{ $project['id'] }}" />
    <div class="container-fluid p-3 p-md-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 d-flex align-items-center">
                @if (Auth::user()->isAdmin())
                    <li class="breadcrumb-item"><a href="{{ route('project.admin') }}">Projects</a></li>
                @endif
                <li class="breadcrumb-item d-flex align-items-center"><a href="#"
                        class="href h3 text-decoration-none">{{ $project['job_number'] }}</a>
                </li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center heading-group mb-3">
            <div class="left-group d-flex align-items-center">
                <h3 class="mb-0 me-2">Project Information</h3>
                @if (Auth::user()->isAdmin())
                    <button type="button" id="edit-button" class="d-inline btn btn-dark text-light shadow-sm"
                        data-bs-toggle="modal" data-bs-target="#projectModal">Edit</button>
                @endif
            </div>
            @if (Auth::user()->isAdmin())
                <div class="right-groups d-flex align-items-center">
                    <button id="delete-data-button" class="btn btn-light text-danger border shadow-sm" type="button"
                        onclick='openModal("deleteConfirmationModal","{{ $project->job_number }}" )'>Delete
                        Project</button>
                </div>
            @endif
        </div>
        <hr>
        <div class="project-information">
            <div class="row w-100 pb-3">
                <div class="col-md-2 col-6">
                    PJO Number:
                </div>
                <div class="col-md-10 col-6">
                    {{ $project->job_number }}
                </div>
            </div>
            <div class="row w-100 pb-3">
                <div class="col-md-2 col-6">
                    Client:
                </div>
                <div class="col-md-10 col-6">
                    {{ $project->client_name }}
                </div>
            </div>
            <div class="row w-100 pb-3">
                <div class="col-md-2 col-6">
                    Location:
                </div>
                <div class="col-md-10 col-6">
                    {{ $project->jobsite_location }}
                </div>
            </div>
            <div class="row w-100 pb-3">
                <div class="col-md-2 col-6">
                    Project Description:
                </div>
                <div class="col-md-10 col-6">
                    {{ $project->project_description }}
                </div>
            </div>
            <div class="row w-100 pb-3">
                <div class="col-md-2 col-6">
                    BCA Reference Number:
                </div>
                <div class="col-md-10 col-6">
                    {{ $project->bca_reference_number }}
                </div>
            </div>
            <div class="row w-100 pb-3">
                <div class="col-md-2 col-6">
                    No. of Contacts:
                </div>
                <div class="col-md-10 col-6">
                    {{ $project->sms_count }}
                </div>
            </div>
            {{-- <div class="row w-100 pb-3">
                <div class="col-md-2 col-6">
                    Status:
                </div>
                <div class="col-md-10 col-6">
                    {{ $project->status }}
                </div>
            </div> --}}
        </div>



        <div class="mb-3">
            <div>
                <h3 class="d-inline">Contacts | </h3>
                <h6 id="contact_counter" class="d-inline @if (count($project->contact) == $project['sms_count']) text-danger  s @endif">
                    {{ count($project->contact) }} / {{ $project['sms_count'] }}</h6>
            </div>

            <div class="mt-2 mb-3 ">
                <button class="d-inline btn btn-primary text-light shadow-sm" id="createContactButton"
                    onclick='openModal("contactModal","create")'>Add</button>
                <button class="d-inline btn btn-dark text-light shadow-sm" id="editContactButton"
                    onclick='openModal("contactModal","update")'>Edit</button>
                <button class="btn btn-light text-danger border shadow-sm" id="deleteContactButton"
                    onclick='openModal("deleteConfirmationModal","contact")'>Delete</button>
            </div>
            <hr>
            <div class="shadow" id="contacts_table"></div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center heading-group mb-3">
                <h3 class="mb-0 me-2">Measurement Points Information</h3>
                @if (Auth::user()->isAdmin())
                    <button class="btn btn-primary text-light shadow-sm" id="createButton"
                        onclick='openModal("measurementPointModal")'>Create</button>
                @endif

            </div>
            <hr>
            <div id="measurement_point_table"></div>

            <div class="d-flex flex-row mt-3 justify-content-between">
                <div id="measurement_point_pages" class="ms-auto me-auto"></div>
            </div>
        </div>

        <x-confirmation-modal />
        <x-delete-modal type='user' />
        <x-delete-confirmation-modal :project="$project" :type="$project->job_number" />
        <x-contacts.contact-modal :project="$project" />
        <x-measurementPoint.measurement-point-modal :project="$project" />
        <x-project.project-modal :project="$project" />

    </div>


    <script src="{{ asset('js/project.js') }}" async defer></script>

</body>

<script>
    $(document).ready(function() {
        $('#selectConcentrator').select2({
            dropdownParent: $('#measurementPointModal'),
        });

        $('#selectNoiseMeter').select2({
            dropdownParent: $('#measurementPointModal'),
        });
    });

    window.project = @json($project);
    window.contacts = @json($project->contact);
    window.admin = @json(Auth::user()->isAdmin());
    console.log(window.project);
</script>

</html>
