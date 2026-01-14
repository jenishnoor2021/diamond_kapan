@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Designation</h4>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    Generate Worker Barcode
                </h4>

                @include('includes.flash_message')

                {!! Form::open([
                'method' => 'POST',
                'action' => 'AdminDesignationController@store',
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'designationform',
                ]) !!}
                @csrf

                <div class="row">
                    <div class="mb-3">
                        <label for="category">Designation Category</label>
                        <select name="category" id="category" class="form-select" required>
                            <option value="">Select category</option>
                            <option value="Inner">Inner</option>
                            <option value="Outter">Outter</option>
                        </select>
                        @if ($errors->has('category'))
                        <div class="error text-danger">{{ $errors->first('category') }}</div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3">
                        <label for="name">Designation Name</label>
                        <input type="text" name="name" class="form-control" id="name"
                            placeholder="Enter Designation" value="{{ old('name') }}"
                            onkeypress='return (event.charCode != 32)' required>
                        @if ($errors->has('name'))
                        <div class="error text-danger">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                </div>

                </form>

            </div>
        </div>
    </div> <!-- end col -->

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Designation List</h4>

                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Category</th>
                            <th>Designation</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($designations as $designation)
                        <tr>
                            <td>
                                @if ($designation->name != 'Certificate')
                                <a href="{{ route('admin.designation.edit', $designation->id) }}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.designation.destroy', $designation->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                                @endif
                            </td>
                            <td>{{ $designation->category }}</td>
                            <td>{{ $designation->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->

</div> <!-- end row -->
@endsection

@section('script')
<script>
    $(function() {
        $("form[name='designationform']").validate({
            rules: {
                name: {
                    required: true,
                },
                category: {
                    required: true,
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@endsection