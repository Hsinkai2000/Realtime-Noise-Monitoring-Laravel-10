<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geoscan | Main</title>
    <!-- Include Tabulator CSS from CDN -->
    <link href="https://unpkg.com/tabulator-tables@5.4.3/dist/css/tabulator.min.css" rel="stylesheet" />
    <!-- Include Tabulator JS from CDN -->
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.3/dist/js/tabulator.min.js"></script>

    <link href="{{ asset('css/project-admin.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- @vite(['resources/scss/project-admin.scss', 'resources/js/app.js', 'resources/js/project-admin.js']) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <x-nav.navbar />

    <div class="container-fluid p-sm-5 pt-sm-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <span class="text-dark h5">Projects</span>
                </li>
            </ol>
        </nav>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" onClick="changeTab(event,'rental')">Rental
                    Projects</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" onClick="changeTab(event,'sales')">Sales Project</a>
            </li>
        </ul>

        <div class="shadow" id="example-table"></div>

        <div class="d-flex flex-row mt-3 justify-content-between">
            <button class="btn btn-light text-danger border shadow-sm" id="deleteButton"
                onclick="openModal('deleteConfirmationModal')">Delete</button>

            <div id="table_pages"></div>

            <div>
                <button class="btn btn-primary bg-light text-primary px-4 me-3 shadow-sm" id="editButton"
                    onclick="openModal('projectModal','update')">Edit</button>
                <button class="btn btn-primary text-light  shadow-sm" id="createButton"
                    onclick="openModal('projectModal','create')">Create</button>
            </div>
        </div>
    </div>

    <x-project.project-modal />
    <x-delete-confirmation-modal />
    <x-delete-modal type='user' />
    <x-user.user-create-modal />

    <script src="{{ asset('js/project-admin.js') }}" async defer></script>


</body>
<script>
    window.projects = @json($projects);
    console.log(window.projects);
</script>

</html>
