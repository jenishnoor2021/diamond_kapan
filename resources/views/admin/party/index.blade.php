<?php

use App\Models\Sell;

?>

@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Party List</h4>

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
                                    href="{{ route('admin.party.create') }}"><i class="fa fa-plus editable" style="font-size:15px;">&nbsp;ADD</i></a>
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
                            <th>Party code</th>
                            <th>Address</th>
                            <th>Mobile no</th>
                            <th>GST no</th>
                            <th>Active/De-active</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($partys as $party)
                        <?php
                        $usedCount = Sell::where('parties_id', $party->id)->count();
                        ?>
                        <tr>
                            <td>
                                <a href="{{ route('admin.party.edit', $party->id) }}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a>
                                @if($usedCount == 0)
                                <a href="{{ route('admin.party.destroy', $party->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                                @endif
                            </td>
                            <!-- <td>{{ $party->id }}</td> -->
                            <td>{{ $party->type }}</td>
                            <td>{{ $party->fname }}</td>
                            <td>{{ $party->lname }}</td>
                            <td>{{ $party->party_code }}</td>
                            <td>
                                @if (strlen($party->address) > 40)
                                {!! substr($party->address, 0, 40) !!}
                                <span class="read-more-show hide_content">More<i
                                        class="fa fa-angle-down"></i></span>
                                <span class="read-more-content">
                                    {{ substr($party->address, 40, strlen($party->address)) }}
                                    <span class="read-more-hide hide_content">Less <i
                                            class="fa fa-angle-up"></i></span> </span>
                                @else
                                {{ $party->address }}
                                @endif
                            </td>
                            <td>{{ $party->mobile }}</td>
                            <td>{{ $party->gst_no }}</td>
                            <td>
                                @if ($party->is_active == 1)
                                <a href="/admin/party/active/{{ $party->id }}"
                                    class="btn btn-success">Active</a>
                                @else
                                <a href="/admin/party/active/{{ $party->id }}"
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