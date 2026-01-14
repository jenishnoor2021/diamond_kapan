@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Shape List</h4>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                @include('includes.flash_message')

                <div id="right">
                    <div id="menu" class="mb-3">

                        <!-- <span id="menu-navi"
                            class="d-sm-flex flex-wrap text-center text-sm-start justify-content-sm-between">
                            <div class="">
                                <a class="btn btn-info waves-effect waves-light"
                                    href="{{ route('admin.shape.create') }}"><i class="fa fa-plus editable" style="font-size:15px;">&nbsp;ADD</i></a>
                            </div>
                        </span> -->

                        {!! Form::open(['method'=>'POST', 'action'=> 'AdminShapeController@store','files'=>true,'class'=>'form-horizontal','name'=>'addshapeform']) !!}
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="shape_type" class="form-label">Type</label>
                                    <input type="text" name="shape_type" class="form-control" id="shape_type" placeholder="Enter type" value="{{ old('shape_type') }}" onkeypress='return (event.charCode != 32)' required>
                                    @if($errors->has('shape_type'))
                                    <div class="error text-danger">{{ $errors->first('shape_type') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-2 mb-3">
                                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/shape') }}">Back</a>
                                </div>
                            </div>
                        </div>

                        </form>

                    </div>
                </div>

                <hr style="border:1px solid #000;">


                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Shape Type</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($shapes as $shape)
                        <tr>
                            <td>
                                <a href="{{ route('admin.shape.edit', $shape->id) }}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.shape.destroy', $shape->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                            <td>{{ $shape->shape_type }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection