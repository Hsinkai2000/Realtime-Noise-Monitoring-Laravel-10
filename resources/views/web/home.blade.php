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


    <link href="{{ asset('css/home.css') }}" rel="stylesheet">

    {{-- @vite(['resources/scss/home.scss', 'resources/scss/base.scss']) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body>
    <nav class="navbar">
        <div class="navbar-logo">
            <a href="{{ route('home') }}">
                <img id="geoscan_logo" src="{{ asset('image/geoscan-logo.png') }}" alt="geoscan-logo"></img>
            </a>
        </div>

        <form class='button-form' action="{{ route('logout') }}" method="POST" role="search">
            @csrf
            @method('DELETE')
            <button class="logout-button">Logout</button>
        </form>
    </nav>
    <div class="container">
        <h1> Welcome, {{ Auth::user()->username }}</h1>
        <div class="button-container">
            <a href="{{ route('project.admin') }}" class="btn">Project</a>
            <a href="{{ route('project.admin') }}" class="btn">Measurement Point</a>
            <a href="{{ route('project.admin') }}" class="btn">Concentrator</a>
            <a href="{{ route('project.admin') }}" class="btn">Noise Meter</a>
            <a href="{{ route('project.admin') }}" class="btn">Contact</a>
            <a href="{{ route('project.admin') }}" class="btn">Contact</a>
        </div>

        <div class="footer">
            <p>Tel: +65 6781 1919</p>
            <p>Fax: +65 6781 9297</p>
            <p>Email: enquiry@geoscan.com.sg</p>
            <p>Web: geoscan.com.sg</p>
        </div>

</body>

</html>
