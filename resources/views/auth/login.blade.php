<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tokabe Kasir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Kasir Tokabe" name="description" />
    <meta content="Tokabe" name="author" />

    <!-- App favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon-32x32.png')}}?v=3">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon-16x16.png')}}?v=3">
    <link rel="icon" type="image/png" href="{{asset('images/LogoTKB.png')}}?v=3">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}?v=3">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/apple-touch-icon.png')}}?v=3">
    <script>
        (function() {
            var link = document.createElement('link');
            link.type = 'image/png';
            link.rel = 'icon';
            link.href = "{{asset('images/LogoTKB.png')}}?v=" + new Date().getTime();
            document.getElementsByTagName('head')[0].appendChild(link);
        })();
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Layout config Js -->
    <script src="{{asset('js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css" />
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
    <body class="auth-bg 100-vh">
        <!-- @include('sweetalert::alert') -->
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
                                        <form action="{{ route('login') }}" method="POST" id="login_form">
                                          @csrf
                                        <div class="row g-0">
                                         <div class="col-lg-12">
                                          <div class="p-lg-5 p-4">
                                           <div class="text-center">
                                            <h5 class="mb-0">Selamat Datang!</h5>
                                             <p class="text-muted mt-2">Silahkan Log in untuk mengakses aplikasi</p>
                                           </div>
                                           
                                            @if(session()->has('error'))
                                              <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                                {{ session('error') }}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                              </div>
                                            @endif

                                            @if(session('success'))
                                              <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                                {{ session('success') }}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                              </div>
                                            @endif

                                           <div class="mt-4">
                                            <form action="index.html" class="auth-input">
                                             <div class="mb-3">
                                              <label for="username" class="form-label">Email</label>
                                               <input type="email" class="form-control @error('email') is-invalid @enderror" id="username" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Masukkan Email">

                                               @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                               @enderror

                                             </div>
                                             <div class="mb-2">
                                              <label for="userpassword" class="form-label">Password</label>
                                               <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" class="form-control pe-5 password-input @error('password') is-invalid @enderror" placeholder="Password" id="password-input" name="password" autocomplete="current-password" required>
                                                @error('password')
                                                  <span class="invalid-feedback" role="alert">
                                                      <strong>{{ $message }}</strong>
                                                  </span>
                                                @enderror
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="las la-eye align-middle fs-18"></i></button>
                                               </div>
                                              </div>
                                              <div class="mt-2">
                                               <button class="btn btn-primary w-100" type="submit">Log In</button>
                                               @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">Forgot Your Password?
                                                </a>
                                               @endif
                                              </div>

                                            </form>
                                           </div>
                                          </div>
                                         </div>

                                        </div>
                                    </div>
                                    <!-- end card -->
                                  </form>
                                    <div class="mt-5 text-center">
                                        <p class="mb-0 text-muted">©
                                            <script>document.write(new Date().getFullYear())</script> Tokabe

                                        </p>
                                    </div>
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
    {{-- <script src="{{asset('js/app.js')}}"></script> --}}

</body>
</html>