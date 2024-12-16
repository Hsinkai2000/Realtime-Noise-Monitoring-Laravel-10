<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>


    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">

    {{-- @vite(['resources/scss/login.scss']) --}}
</head>

<body>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div id="maincard" class="card text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 ">
                            <div class="mb-md-5 mt-md-4 pb-5">
                                <div class="text-center">

                                    <img id="geoscan_logo" src="{{ asset('image/geoscan-logo.png') }}"
                                        alt="geoscan-logo" />
                                    <h2 class="fw-bold mb-5 text-uppercase text-dark">Geoscan NMS</h2>
                                </div>
                                <form action="{{ route('login.post') }}" method="POST">
                                    @csrf
                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <label class="form-label" for="typeEmailX">Username</label>
                                        <input type="text" name='username' id="typeUserName" hint='Enter username'
                                            class="form-control form-control-lg" />
                                    </div>

                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <label class="form-label" for="typePasswordX">Password</label>
                                        <input type="password" id="typePassword" name="password" hint='Enter password'
                                            class="form-control form-control-lg" />
                                    </div>
                                    @if (session('error'))
                                        <p class="error-message text-danger mt-3">{{ session('error') }}</p>
                                    @endif
                                    <button data-mdb-button-init data-mdb-ripple-init id="btn-login"
                                        class="btn btn-dark btn-lg w-100 mt-5" type="submit">Login</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
