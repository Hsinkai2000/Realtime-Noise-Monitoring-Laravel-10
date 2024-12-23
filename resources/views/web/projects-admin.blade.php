<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geoscan | Main</title>

    <link href="https://unpkg.com/tabulator-tables@5.4.3/dist/css/tabulator.min.css" rel="stylesheet" />
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.3/dist/js/tabulator.min.js"></script>

    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/project-admin.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <x-nav.navbar />

    <div class="container-fluid p-3 p-md-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 d-flex align-items-center">
                <li class="breadcrumb-item d-flex align-items-center"><a href="#"
                        class="href h3 text-decoration-none">Projects</a>
                </li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" onClick="changeTab(event,'rental')">Rental
                        Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onClick="changeTab(event,'sales')">Sales Project</a>
                </li>
            </ul>
            <button class="btn btn-primary text-light shadow-sm m-2" id="createButton" data-bs-toggle="modal"
                data-bs-target="#projectModal"> Create </button>
        </div>

        <div class="shadow" id="example-table"></div>

    </div>

    <x-delete-modal type='user' />
    <x-project.project-modal />
    {{-- <x-delete-confirmation-modal /> --}}
    <x-user.user-create-modal />



</body>
<script>
    window.rental_projects = @json($rental_projects);
    window.sales_projects = @json($sales_projects);
    console.log(window.rental_projects);
</script>
<script src="{{ asset('js/project-admin-test.js') }}" async defer></script>

</html>
