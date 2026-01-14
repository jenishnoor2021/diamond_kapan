<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Login | Skote - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">

    <!-- Bootstrap Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/icons.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}" type="text/css">
    <script src="{{ asset('assets/js/plugin.js') }}"></script>

</head>

<body>
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary-subtle">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">Welcome Back !</h5>
                                        <p>Sign in to continue to Skote.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ asset('assets/images/profile-img.png') }}" alt=""
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="auth-logo">
                                <a href="{{ URL::to('/') }}" class="auth-logo-light">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('assets/images/logo-light.svg') }}" alt=""
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>

                                <a href="{{ URL::to('/') }}" class="auth-logo-dark">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('assets/images/logo.svg') }}" alt=""
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">
                                @if (\Session::has('alert-error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="mdi mdi-block-helper me-2"></i>
                                    {!! \Session::get('alert-error') !!}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @endif

                                @if (\Session::has('alert'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="mdi mdi-check-all me-2"></i>
                                    {!! \Session::get('alert') !!}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @endif

                                <form class="form-horizontal" action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" id="username"
                                            placeholder="Enter username">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" class="form-control" name="password"
                                                placeholder="Enter password" aria-label="Password"
                                                aria-describedby="password-addon">
                                            <button class="btn btn-light " type="button" id="password-addon"><i
                                                    class="mdi mdi-eye-outline"></i></button>
                                        </div>
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <button class="btn btn-primary waves-effect waves-light" type="submit">Log
                                            In</button>
                                    </div>

                                    <!-- <div class="mt-4 text-center">
                                        <a href="/password/reset">Forgot Password</a>
                                    </div> -->
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">

                        <div>
                            <!-- <p>Don't have an account ? <a href="/register" class="fw-medium text-primary">
                                    Signup now </a> </p> -->
                            <p>©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> Skote. Crafted with <i class="mdi mdi-heart text-danger"></i>
                                by Themesbrand
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- end account-pages -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>