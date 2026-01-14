@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Change Password</h4>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                @include('includes.flash_message')

                <form class="form-horizontal" method="POST" action="{{ route('profile.update') }}" name='editprofile'>
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="current_password">Current Password</label>
                                <input type="password" name="current_password" class="form-control" id="current_password"
                                    placeholder="Enter Your current password" required>
                                @if ($errors->has('current_password'))
                                <div class="error text-danger">{{ $errors->first('current_password') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" class="form-control" id="new_password"
                                    placeholder="Enter Your new password" required>
                                @if ($errors->has('new_password'))
                                <div class="error text-danger">{{ $errors->first('new_password') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="new_password-confirm">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control"
                                    id="new_password-confirm" placeholder="Enter Confirm New Password" required>
                                @if ($errors->has('new_password_confirmation'))
                                <div class="error text-danger">{{ $errors->first('new_password_confirmation') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                        <a class="btn btn-light w-md" href="{{ URL::to('/admin/dashboard') }}">Back</a>
                    </div>
                </form>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->
@endsection

@section('script')
<script>
    $(function() {

        $("form[name='editprofile']").validate({
            rules: {
                current_password: {
                    required: true,
                },
                new_password: {
                    required: true,
                },
                new_password_confirmation: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@endsection