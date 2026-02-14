@extends('layouts.admin')

@section('content')


<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Purchase / Sell Dashboard</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="row">

            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Purchased Diamonds</p>
                                <h4 class="mb-0">{{$purchaseDiamondCount}}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-diamond font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">sell Diamonds</p>
                                <h4 class="mb-0">{{$sellDiamondCount}}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-diamond font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="col-12">
        <div class="row">

            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body text-center">
                        <h6>Sold Diamonds</h6>
                        <h4>₹ {{ number_format($totalSellAmount , 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body text-center">
                        <h6>Paid Amount</h6>
                        <h4>₹ {{ number_format($paidAmount, 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-body text-center">
                        <h6>Pending Amount</h6>
                        <h4>₹ {{ number_format($pendingAmount, 2) }}</h4>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="col-xl-12">
        <div class="row">

            <div class="col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h4 class="text-muted fw-medium">Income</h4>
                                <p class="mb-0"><span class="text-primary font-weight-bold">Khata Income</span> = ₹ {{ number_format($totalIncome , 2) }}</p>
                                <p class="mb-0"><span class="text-primary font-weight-bold">Sell income</span> = ₹ {{ number_format($paidAmount , 2) }}</p>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-rupee font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h4 class="text-muted fw-medium">Expanse</h4>
                                <p class="mb-0"><span class="text-primary font-weight-bold">Cash</span> = ₹ {{ number_format($cashExpense , 2) }}</p>
                                <p class="mb-0"><span class="text-primary font-weight-bold">Bank</span>= ₹ {{ number_format($bankExpense , 2) }}</p>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-wallet font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="text-primary text-bold">Today Due Date</h3>

                    @if(count($todaylists)>0)
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Party</th>
                                <th>Broker</th>
                                <!-- <th>Mobile</th> -->
                                <th>Dimond Name</th>
                                <th>Amount</th>
                                <th>Sell Date</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todaylists as $todaylist)
                            <tr>
                                <td>{{$todaylist->parties_name}}</td>
                                <td>{{$todaylist->broker_name}}</td>
                                <!-- <td>{{$todaylist->party?->mobile}}</td> -->
                                <td>{{$todaylist->diamond->diamond_name}}</td>
                                <td>{{$todaylist->total_amount}}</td>
                                <td>{{ \Carbon\Carbon::parse($todaylist->sell_date)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($todaylist->due_date)->format('d-m-Y') }}</td>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="text-primary text-bold">Tomorrow Due Date</h3>

                    @if(count($tomorrowlists)>0)
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Party</th>
                                <th>Broker</th>
                                <!-- <th>Mobile</th> -->
                                <th>Dimond Name</th>
                                <th>Amount</th>
                                <th>Sell Date</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tomorrowlists as $tomorrowlist)
                            <tr>
                                <td>{{$tomorrowlist->parties_name}}</td>
                                <td>{{$tomorrowlist->broker_name}}</td>
                                <!-- <td>{{$tomorrowlist->party?->mobile}}</td> -->
                                <td>{{$tomorrowlist->diamond->diamond_name}}</td>
                                <td>{{$tomorrowlist->total_amount}}</td>
                                <td>{{ \Carbon\Carbon::parse($tomorrowlist->sell_date)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($tomorrowlist->due_date)->format('d-m-Y') }}</td>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="text-primary text-bold">Old Due Date</h3>

                    @if(count($outdatedlists)>0)
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Party</th>
                                <th>Broker</th>
                                <!-- <th>Mobile</th> -->
                                <th>Dimond Name</th>
                                <th>Amount</th>
                                <th>Sell Date</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($outdatedlists as $outdatedlist)
                            <tr>
                                <td>{{$outdatedlist->parties_name}}</td>
                                <td>{{$outdatedlist->broker_name}}</td>
                                <!-- <td>{{$outdatedlist->party?->mobile}}</td> -->
                                <td>{{$outdatedlist->diamond->diamond_name}}</td>
                                <td>{{$outdatedlist->total_amount}}</td>
                                <td>{{ \Carbon\Carbon::parse($outdatedlist->sell_date)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($outdatedlist->due_date)->format('d-m-Y') }}</td>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>
@endsection