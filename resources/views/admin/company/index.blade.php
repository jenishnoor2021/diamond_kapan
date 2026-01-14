<?php

use App\Models\Process;
?>
@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Company</h4>

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
                            <div class="d-sm-flex flex-wrap gap-1">
                                <h4>Company</h4>
                            </div>

                            <h4 class="render-range fw-bold pt-1 mx-3">
                            </h4>

                            @if(count($comapyes) == 0)
                            <div class="align-self-start mt-3 mt-sm-0 mb-2">
                                <a class="btn btn-primary" href="{{ route('admin.company.create') }}"><i
                                        class="fa fa-plus">&nbsp;ADD</i></a>
                            </div>
                            @endif
                        </span>

                    </div>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>GST</th>
                            <th>PAN</th>
                            <th>Address</th>
                            <th>CGST</th>
                            <th>SGST</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($comapyes as $company)
                        <tr>
                            <td>
                                <a href="{{route('admin.company.edit', $company->id)}}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a>
                                @if(count($comapyes) > 1)
                                <a href="{{route('admin.company.destroy', $company->id)}}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                                @endif
                            </td>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->email }}</td>
                            <td>{{ $company->contact }}</td>
                            <td>{{ $company->gst_no }}</td>
                            <td>{{ $company->pan_no }}</td>
                            <td>
                                @if (strlen($company->address) > 50)
                                {!! substr($company->address, 0, 50) !!}
                                <span class="read-more-show hide_content">More<i
                                        class="fa fa-angle-down"></i></span>
                                <span class="read-more-content">
                                    {{ substr($company->address, 50, strlen($company->address)) }}
                                    <span class="read-more-hide hide_content">Less <i
                                            class="fa fa-angle-up"></i></span> </span>
                                @else
                                {{ $company->address }}
                                @endif
                            </td>
                            <td>{{ $company->cgst }}</td>
                            <td>{{ $company->sgst }}</td>
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
    $(document).ready(function() {
        // Capture the change event of the dropdown
        $('#status').on('change', function() {
            // Trigger form submission when the dropdown changes
            $('#myForm').submit();
        });
    });
</script>

@endsection