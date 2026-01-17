<?php

use App\Models\Issue;
?>
@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Diamond Purchase List</h4>
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
                        <a href="{{ route('admin.purchase.export') }}"
                            class="btn btn-success btn-sm">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </div>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Diamond Name</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($purchases as $purchase)

                        <?php
                        $cRWeight = 0;
                        $certiReturn = Issue::where('designation_id', 3)->where('diamonds_id', $purchase->diamonds_id)->first();
                        if ($certiReturn) {
                            $cRWeight = $certiReturn->return_weight;
                        }
                        ?>
                        <tr>
                            <td>
                                <a href="{{ route('admin.purchase.edit', $purchase->id) }}" target="_blank" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-edit"></i></a>
                                <button
                                    class="btn btn-outline-success waves-effect waves-light sellBtn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#sellModal"
                                    data-id="{{ $purchase->id }}"
                                    data-diamonds_id="{{ $purchase->diamonds_id }}"
                                    data-name="{{ $purchase->diamond->diamond_name }}"
                                    data-pweight="{{ $cRWeight }}">
                                    Sell
                                </button>
                            </td>
                            <td>{{ $purchase->diamond->diamond_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


<div class="modal fade" id="sellModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('admin.sell.store') }}">
            @csrf

            <input type="hidden" name="purchase_id" id="purchase_id">
            <input type="hidden" name="diamonds_id" id="diamonds_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sell Diamond - <span id="dName" class="text-success"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-4 mb-2">
                            <label>Diamond Weight</label>
                            <input type="number" step="0.01" name="pWeight" id="pWeight" class="form-control" disabled>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Party Name</label>
                            <select name="parties_id" class="form-control" required>
                                @foreach($partys as $party)
                                <option value="{{$party->id}}">{{$party->fname}} - {{$party->lname}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Ct Rate ($)</label>
                            <input type="number" step="0.01" name="rate_per_ct" id="rate_per_ct" class="form-control" required>
                        </div>

                        <!--  weightXratePerCt = pWeight * rate_per_ct  -->
                        <div class="col-md-4 mb-2">
                            <label>Total $</label>
                            <input type="number" step="0.01" name="weightXratePerCt" id="weightXratePerCt" class="form-control" disabled>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Today $ Rate</label>
                            <input type="number" step="0.01" name="dollar_rate" id="dollar_rate" class="form-control" required>
                        </div>

                        <!-- final_amount = dollar_rate * weightXratePerCt -->
                        <div class="col-md-4 mb-2">
                            <label>Total Amount (INR)</label>
                            <input type="number" step="0.01" name="final_amount" id="final_amount" class="form-control" style="background-color:#f8f8fb" required readonly>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Broker Name</label>
                            <select name="broker_id" class="form-control">
                                @foreach($brokers as $broker)
                                <option value="{{$broker->id}}">{{$broker->fname}} - {{$broker->lname}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Brokerage (%)</label>
                            <input type="number" step="0.01" name="less_brokerage" id="less_brokerage" class="form-control" required>
                        </div>

                        <!-- brokerage_amount = final_amount * less_brokerage  / for ex. 18000* 2% = 360  -->
                        <div class="col-md-4 mb-2">
                            <label>Brokerage Amount</label>
                            <input type="number" step="0.01" name="brokerage_amount" id="brokerage_amount" class="form-control" style="background-color:#f8f8fb" required disabled>
                        </div>

                        <!-- total_amount = final_amount - brokerage_amount -->
                        <div class="col-md-4 mb-2">
                            <label>Income Amount</label>
                            <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" style="background-color:#f8f8fb" required readonly>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Payment Type</label>
                            <select name="payment_type" class="form-control" required>
                                <option value="cash">Cash</option>
                                <option value="online">Online</option>
                                <option value="chque">Cheque</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Status</label>
                            <select name="payment_status" class="form-control" required>
                                <option value="unpaid">Unpaid</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Sell Date</label>
                            <input type="date" name="sell_date" value="{{ now()->toDateString() }}" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Due Date</label>
                            <input type="date" name="due_date" value="{{ now()->toDateString() }}" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Note</label>
                            <textarea type="text" name="note" class="form-control"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.sellBtn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('purchase_id').value = this.dataset.id;
                document.getElementById('diamonds_id').value = this.dataset.diamonds_id;
                document.getElementById('dName').innerHTML = this.dataset.name;
                document.getElementById('pWeight').value = this.dataset.pweight;
            });
        });
    });
</script>

<script>
    function calculateAmounts() {

        let pWeight = parseFloat(document.getElementById('pWeight').value) || 0;
        let ratePerCt = parseFloat(document.getElementById('rate_per_ct').value) || 0;
        let dollarRate = parseFloat(document.getElementById('dollar_rate').value) || 0;
        let brokeragePer = parseFloat(document.getElementById('less_brokerage').value) || 0;

        // 1. Weight × Rate per Ct
        let weightXrate = pWeight * ratePerCt;
        document.getElementById('weightXratePerCt').value = weightXrate.toFixed(2);

        // 2. Final Amount (INR)
        let finalAmount = weightXrate * dollarRate;
        document.getElementById('final_amount').value = finalAmount.toFixed(2);

        // 3. Brokerage Amount
        let brokerageAmount = (finalAmount * brokeragePer) / 100;
        document.getElementById('brokerage_amount').value = brokerageAmount.toFixed(2);

        // 4. Income Amount
        let totalAmount = finalAmount - brokerageAmount;
        document.getElementById('total_amount').value = totalAmount.toFixed(2);
    }

    // Trigger calculation on input change
    document.querySelectorAll(
        '#pWeight, #rate_per_ct, #dollar_rate, #less_brokerage'
    ).forEach(el => {
        el.addEventListener('input', calculateAmounts);
    });
</script>
@endsection