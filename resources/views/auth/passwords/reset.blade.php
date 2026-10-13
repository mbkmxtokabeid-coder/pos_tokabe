<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tokabe Kasir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Kasir Tokabe" name="description">
    <meta content="Tokabe" name="author">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon-16x16.png')}}">
    <link rel="icon" type="image/png" href="{{asset('images/LogoTKB.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/apple-touch-icon.png')}}">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/icons.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css">
</head>
<body class="auth-bg 100-vh">
    <div class="account-pages">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <div class="auth-full-page-content d-flex min-vh-100 py-sm-1 py-1">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100 py-0 py-xl-4">
                                <div class="text-center mb-3">
                                    <a href="index.html">
                                        <span class="logo-lg">
                                            <img src="{{asset('images/LogoTKB.png')}}" alt="Tokabe" height="100">
                                        </span>
                                    </a>
                                </div>

                                <div class="card my-auto overflow-hidden col-lg-6 align-self-center">
                                    <form action="{{ route('password.update') }}" method="POST" id="login_form">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <div class="row g-0">
                                            <div class="col-lg-12">
                                                <div class="p-lg-5 p-4">
                                                    <div class="text-center">
                                                        <h5 class="mb-0">Reset Password</h5>
                                                    </div>
                                                    @if(session()->has('error'))
                                                        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                                            {{ session('error') }}
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>
                                                    @endif
                                                    <div class="mt-4">
                                                        <form action="index.html" class="auth-input">
                                                            <div class="mb-3">
                                                                <label for="userpassword" class="form-label">Email</label>
                                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                                                @error('email')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-2">
                                                                <label for="userpassword" class="form-label">Password</label>
                                                                <div class="position-relative auth-pass-inputgroup mb-3">
                                                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                                                    @error('password')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                                                                <div class="position-relative auth-pass-inputgroup mb-3">
                                                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                                                </div>
                                                            </div>
                                                            <div class="mt-2">
                                                                <button type="submit" class="btn btn-primary">Reset Password</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- end card -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{asset('libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('js/plugins.js')}}"></script>

    <!-- password-addon init -->
    <script src="{{asset('js/pages/password-addon.init.js')}}"></script>
</body>
</html>
