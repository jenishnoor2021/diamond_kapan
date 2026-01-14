@extends('layouts.app')

@section('content')
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary-subtle">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">Reset Password</h5>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ asset('assets/images/profile-img.png') }}" alt=""
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div>
                                <a href="index.html">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('assets/images/logo.svg') }}" alt=""
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">

                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf

                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" id="email" placeholder="Enter email"
                                            value="{{ old('email') }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" id="password" placeholder="Enter password" required>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password-confirm" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password_confirmation"
                                            id="password-confirm" placeholder="Enter password-confirm" required>
                                    </div>

                                    <div class="mt-4 d-grid">
                                        <button class="btn btn-primary waves-effect waves-light"
                                            type="submit">Reset Password</button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p class="mb-0">By registering you agree to the Skote <a href="#"
                                                class="text-primary">Terms of Use</a></p>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">

                        <div>
                            <p>Already have an account ? <a href="/login" class="fw-medium text-primary"> Login</a>
                            </p>
                            <p>©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> Skote. Crafted with <i class="mdi mdi-heart text-danger"></i> by
                                Themesbrand
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
