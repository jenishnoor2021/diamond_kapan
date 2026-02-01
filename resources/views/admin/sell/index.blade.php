@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Diamond Sell List</h4>
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
                    </div>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Diamond Name</th>
                            <th>Party Name</th>
                            <th>Final Rate</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sells as $sell)
                        <tr>
                            <td>
                                <a href="{{ route('admin.sell.edit', $sell->id) }}" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-edit"></i></a>
                            </td>
                            <td>{{ $sell->diamond->diamond_name }}</td>
                            <td>{{ $sell->party?->fname }}</td>
                            <td>{{ $sell->final_amount }}</td>
                            <td>{{ $sell->payment_status }}</td>
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
@endsection