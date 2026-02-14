@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Khata List</h4>

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

                        <span id="menu-navi"
                            class="d-sm-flex flex-wrap text-center text-sm-start justify-content-sm-between">
                            <div class="">
                                <a class="btn btn-info waves-effect waves-light"
                                    href="{{ route('admin.khata.create') }}"><i class="fa fa-plus editable" style="font-size:15px;">&nbsp;ADD</i></a>
                            </div>
                        </span>

                    </div>
                </div>



                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <!-- <th>Party id</th> -->
                            <th>Party Type</th>
                            <th>First name</th>
                            <th>Last Name</th>
                            <th>Mobile no</th>
                            <th>Active/De-active</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($khatas as $khata)
                        <tr>
                            <td>
                                <a href="{{ route('admin.khata.edit', $khata->id) }}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.khata.destroy', $khata->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                            <!-- <td>{{ $khata->id }}</td> -->
                            <td>{{ $khata->type }}</td>
                            <td>{{ $khata->fname }}</td>
                            <td>{{ $khata->lname }}</td>
                            <td>{{ $khata->mobile }}</td>
                            <td>
                                @if ($khata->is_active == 1)
                                <a href="/admin/khata/active/{{ $khata->id }}"
                                    class="btn btn-success">Active</a>
                                @else
                                <a href="/admin/khata/active/{{ $khata->id }}"
                                    class="btn btn-danger">De-active</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection