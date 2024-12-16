<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geoscan | Main</title>

    <!-- Include jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Tabulator CSS from CDN -->
    <link href="https://unpkg.com/tabulator-tables@5.4.3/dist/css/tabulator.min.css" rel="stylesheet" />
    <!-- Include Tabulator JS from CDN -->
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

    {{-- @vite(['resources/scss/project.scss', 'resources/js/app.js', 'resources/js/project.js']) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>

    <x-nav.navbar projectId="{{ $project['id'] }}" />

    <div class="container-fluid pt-3 p-5">
        <h3 class="text-dark">Project</h3>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                @if (Auth::user()->isAdmin())
                    <li class="breadcrumb-item"><a href="{{ route('project.admin') }}">Projects</a></li>
                @endif
                <li class="breadcrumb-item"><a href="#">{{ $project['job_number'] }}</a></li>
            </ol>
        </nav>
        <div class="mb-3">
            <h5 class="d-inline me-4">Project Information</h5>
        </div>
        <table class="table">
            <tr>
                <th scope='row'>PJO Number</th>
                <td scope='row'>{{ $project['job_number'] }}</td>
            </tr>
            <tr>
                <th scope='row'>Client</th>
                <td scope='row'>{{ $project['client_name'] }}</td>
            </tr>
            <tr>
                <th scope='row'>Location</th>
                <td scope='row'>{{ $project['jobsite_location'] }}</td>
            </tr>
            <tr>
                <th scope='row'>Project Description</th>
                <td scope='row'>{{ $project['project_description'] }}</td>
            </tr>
            <tr>
                <th scope='row'>BCA Reference Number</th>
                <td scope='row'>{{ $project['bca_reference_number'] }}</td>
            </tr>
            <tr>
                <th scope='row'>No. of Contacts</th>
                <td scope='row'>{{ $project['sms_count'] }}</td>
            </tr>
            <tr>
                <th scope='row'>Status</th>
                <td scope='row'>{{ $project['status'] }}</td>
            </tr>
        </table>

        <div class="p-2 mb-3 rounded">
            <div>
                <h5 class="d-inline">Contacts | </h5>
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
            <div class="shadow" id="contacts_table"></div>
        </div>
        <div class="">
            <h5>Measurement Points Information</h5>
            <div id="measurement_point_table"></div>

            <div class="d-flex flex-row mt-3 justify-content-between">
                @if (Auth::user()->isAdmin())
                    <button class="btn btn-light text-danger border shadow-sm" id="deleteButton"
                        onclick="openModal('deleteConfirmationModal','measurementPoints')">Delete</button>
                @endif
                <div id="measurement_point_pages" class="ms-auto me-auto"></div>
                <div>
                    <button class="btn btn-primary bg-light text-primary px-4 me-3 shadow-sm" id="editButton"
                        onclick='openModal("measurementPointModal", "update")'>Edit</button>
                    @if (Auth::user()->isAdmin())
                        <button class="btn btn-primary text-light shadow-sm" id="createButton"
                            onclick='openModal("measurementPointModal", "create")'>Create</button>
                    @endif
                </div>
            </div>
        </div>

        <x-confirmation-modal />
        <x-contacts.contact-modal />
        <x-delete-confirmation-modal />
        <x-delete-modal type='user' />
        <x-user.user-create-modal />
        <x-measurementPoint.measurement-point-modal :project="$project" />
        <input hidden id="inputprojectId" value="{{ $project['id'] }}">
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
</script>

</html>
