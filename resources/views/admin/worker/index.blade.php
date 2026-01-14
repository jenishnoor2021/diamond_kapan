@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Worker List</h4>

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
                                    href="{{ route('admin.worker.create') }}"><i class="fa fa-plus editable"
                                        style="font-size:15px;">&nbsp;ADD</i></a>
                            </div>
                        </span>

                    </div>
                </div>



                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Designation</th>
                            <th>First name</th>
                            <th>Last Name</th>
                            <th>Address</th>
                            <th>Mobile no</th>
                            <th>Aadhar no</th>
                            <th>Active/De-active</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($workers as $worker)
                        <tr>
                            <td>
                                <a href="{{ route('admin.worker.edit', $worker->id) }}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.worker.destroy', $worker->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                            <td>{{ $worker->designations?->name ?? '-' }}</td>
                            <td>{{ $worker->fname }}</td>
                            <td>{{ $worker->lname }}</td>
                            <td>
                                @if (strlen($worker->address) > 100)
                                {!! substr($worker->address, 0, 100) !!}
                                <span class="read-more-show hide_content">More<i
                                        class="fa fa-angle-down"></i></span>
                                <span class="read-more-content">
                                    {{ substr($worker->address, 100, strlen($worker->address)) }}
                                    <span class="read-more-hide hide_content">Less <i
                                            class="fa fa-angle-up"></i></span> </span>
                                @else
                                {{ $worker->address }}
                                @endif
                            </td>
                            <td>{{ $worker->mobile }}</td>
                            <td>{{ $worker->aadhar_no }}</td>
                            <td>
                                @if ($worker->is_active == 1)
                                <a href="/admin/worker/active/{{ $worker->id }}"
                                    class="btn btn-success">Active</a>
                                @else
                                <a href="/admin/worker/active/{{ $worker->id }}"
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