@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Worker</h4>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit</h4>

                @include('includes.flash_message')

                {!! Form::model($worker, [
                'method' => 'PATCH',
                'action' => ['AdminWorkerController@update', $worker->id],
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'editworkerform',
                ]) !!}
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="fname">First Name</label>
                            <input type="text" name="fname" class="form-control" id="fname"
                                placeholder="Enter First name" onkeypress='return (event.charCode != 32)'
                                value="{{ $worker->fname }}" required>
                            @if ($errors->has('fname'))
                            <div class="error text-danger">{{ $errors->first('fname') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="lname">Last Name</label>
                            <input type="text" name="lname" class="form-control" id="lname"
                                placeholder="Enter Last Name" onkeypress='return (event.charCode != 32)'
                                value="{{ $worker->lname }}" required>
                            @if ($errors->has('lname'))
                            <div class="error text-danger">{{ $errors->first('lname') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="designation">Designation</label>
                            <select name="designation" id="designation" class="form-select" required>
                                <option value="">Select designation</option>
                                @foreach ($designations as $designation)
                                <option value="{{ $designation->id }}"
                                    {{ $worker->designation == $designation->id ? 'selected' : '' }}>
                                    {{ $designation->name }}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('designation'))
                            <div class="error text-danger">{{ $errors->first('designation') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="remark">Remark / katori</label>
                    <textarea type="text" name="remark" class="form-control" id="remark" placeholder="Enter remark">{{ $worker->remark }}</textarea>
                    @if ($errors->has('remark'))
                    <div class="error text-danger">{{ $errors->first('remark') }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="address">Address</label>
                    <textarea type="text" name="address" class="form-control" id="address" placeholder="Enter Address">{{ $worker->address }}</textarea>
                    @if ($errors->has('address'))
                    <div class="error text-danger">{{ $errors->first('address') }}</div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="mobile">Mobile no</label>
                            <input type="number" name="mobile" class="form-control" id="mobile"
                                placeholder="Enter number" value="{{ $worker->mobile }}">
                            @if ($errors->has('mobile'))
                            <div class="error text-danger">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="aadhar_no">Aadhar Number</label>
                            <input type="text" name="aadhar_no" class="form-control" id="aadhar_no"
                                placeholder="Enter aadhar no" oninput="formatAadharInput(this)"
                                value="{{ $worker->aadhar_no }}">
                            @if ($errors->has('aadhar_no'))
                            <div class="error text-danger">{{ $errors->first('aadhar_no') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <label class="form-check-label" for="myCheckbox">
                            <input class="form-check-input" type="checkbox" id="myCheckbox">
                            Add Bank detail
                        </label>
                    </div>
                </div>

                <div id="myDiv" style="display: none;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="bank_name">Bank name</label>
                                <input type="text" name="bank_name" class="form-control" id="bank_name"
                                    placeholder="Enter bank name" value="{{ $worker->bank_name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ifsc_code">IFSC code</label>
                                <input type="text" name="ifsc_code" class="form-control" id="ifsc_code"
                                    placeholder="Enter IFSC code" value="{{ $worker->ifsc_code }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="account_holder_name">Account Holder name</label>
                                <input type="text" name="account_holder_name" class="form-control"
                                    id="account_holder_name" placeholder="Enter Account Holder name"
                                    value="{{ $worker->account_holder_name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="account_no">Account Number</label>
                                <input type="number" name="account_no" class="form-control" id="account_no"
                                    placeholder="Enter Account number" value="{{ $worker->account_no }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">Update</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/worker') }}">Back</a>
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

        $("form[name='editworkerform']").validate({
            rules: {
                fname: {
                    required: true,
                },
                lname: {
                    required: true,
                },
                // address: {
                //    required: true,
                // },
                // mobile: {
                //    required: true,
                // },
                designation: {
                    required: true,
                },
                // aadhar_no: {
                //    required: true,
                // }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var checkbox = document.getElementById('myCheckbox');
        var div = document.getElementById('myDiv');

        checkbox.addEventListener('change', function() {
            div.style.display = checkbox.checked ? 'block' : 'none';
        });
    });
</script>
@endsection