@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Company</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                @include('includes.flash_message')

                {!! Form::model($company, [
                'method' => 'PATCH',
                'action' => ['AdminCompanyController@update', $company->id],
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'editcompanyform',
                ]) !!}
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="name">Comapny Name</label>
                            <input type="text" name="name" class="form-control" id="name"
                                placeholder="Enter name" value="{{ $company->name }}" required>
                            @if ($errors->has('name'))
                            <div class="error text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="gst_no">GST No</label>
                            <input type="text" name="gst_no" class="form-control" id="gst_no"
                                placeholder="Enter GST No" value="{{ $company->gst_no }}" required>
                            @if ($errors->has('gst_no'))
                            <div class="error text-danger">{{ $errors->first('gst_no') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="pan_no">PAN No</label>
                            <input type="text" name="pan_no" class="form-control" id="pan_no"
                                placeholder="Enter PAN No" value="{{ $company->pan_no }}" required>
                            @if ($errors->has('pan_no'))
                            <div class="error text-danger">{{ $errors->first('pan_no') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="address">Address</label>
                            <textarea type="text" name="address" class="form-control" id="address" placeholder="Enter detail">{{ $company->address }}</textarea>
                            @if ($errors->has('address'))
                            <div class="error text-danger">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email"
                                placeholder="Enter email" value="{{ $company->email }}" required>
                            @if ($errors->has('email'))
                            <div class="error text-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="contact">Contact</label>
                            <input type="number" name="contact" class="form-control" id="contact"
                                placeholder="Enter contact No" value="{{ $company->contact }}" required>
                            @if ($errors->has('contact'))
                            <div class="error text-danger">{{ $errors->first('contact') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="sign">Sign</label>
                            <input type="file" name="sign" class="form-control" id="sign" required>
                            <img src="{{ $company->sign }}" alt="Your Logo" width="100px">
                            @if ($errors->has('sign'))
                            <div class="error text-danger">{{ $errors->first('sign') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="cgst">CGST</label>
                            <input type="number" name="cgst" class="form-control" id="cgst"
                                placeholder="Enter CGST" value="{{ $company->cgst }}" required>
                            @if ($errors->has('cgst'))
                            <div class="error text-danger">{{ $errors->first('cgst') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="sgst">SGST</label>
                            <input type="number" name="sgst" class="form-control" id="sgst"
                                placeholder="Enter SGST" value="{{ $company->sgst }}" required>
                            @if ($errors->has('sgst'))
                            <div class="error text-danger">{{ $errors->first('sgst') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <?php $bank_info = $company['bank_info'] ? json_decode($company['bank_info'], true) : ''; ?>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="bank_name">Bank</label>
                            <input type="name" name="bank_name" class="form-control" id="bank_name"
                                placeholder="Enter bank name" value="<?= $bank_info['bank_name'] ?? '' ?>">
                            @if ($errors->has('bank_name'))
                            <div class="error text-danger">{{ $errors->first('bank_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="account_no">Account No</label>
                            <input type="number" name="account_no" class="form-control" id="account_no"
                                placeholder="Enter account no" value="<?= $bank_info['account_no'] ?? '' ?>">
                            @if ($errors->has('account_no'))
                            <div class="error text-danger">{{ $errors->first('account_no') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="ifsc_code">IFSC code</label>
                            <input type="text" name="ifsc_code" class="form-control" id="ifsc_code"
                                value="<?= $bank_info['ifsc_code'] ?? '' ?>">
                            @if ($errors->has('ifsc_code'))
                            <div class="error text-danger">{{ $errors->first('ifsc_code') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="branch">Branch</label>
                            <input type="text" name="branch" class="form-control"
                                id="branch" placeholder="Enter branch" value="<?= $bank_info['branch'] ?? '' ?>">
                            @if ($errors->has('branch'))
                            <div class="error text-danger">{{ $errors->first('branch') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/company') }}">Back</a>
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

        $("form[name='editcompanyform']").validate({
            rules: {
                name: {
                    required: true,
                },
                gst_no: {
                    required: true,
                },
                pan_no: {
                    required: true,
                },
                email: {
                    required: true,
                },
                contact: {
                    required: true,
                },
                address: {
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