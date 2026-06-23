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

                <!-- <div id="right">
                    <div id="menu" class="mb-3">
                        <a href="{{ route('admin.purchase.export') }}"
                            class="btn btn-success btn-sm">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </div>
                </div> -->

                <div id="right">
                    <div id="menu" class="mb-3 d-flex gap-2">

                        <form method="GET" action="{{ route('admin.purchase.index') }}" class="d-flex gap-2">

                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All</option>
                                <option value="certi" {{ request('status') == 'certi' ? 'selected' : '' }}>Certified</option>
                                <option value="non_certi" {{ request('status') == 'non_certi' ? 'selected' : '' }}>Non Certified</option>
                            </select>

                        </form>

                        <a href="{{ route('admin.purchase.export', ['status' => request('status')]) }}"
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
                            <th>Return Weight</th>
                            <th>Shape</th>
                            <th>Color</th>
                            <th>Clarity</th>
                            <th>Polish</th>
                            <th>Symmetry</th>
                            <th>Price</th>
                            <th>Total Price</th>
                            <th>Diamond Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($purchases as $purchase)

                        <?php
                        $cRWeight = 0;
                        $status = '';
                        $certiReturn = Issue::where('designation_id', 3)->where('diamonds_id', $purchase->diamonds_id)->first();
                        if ($certiReturn) {
                            $cRWeight = $certiReturn->return_weight;
                            $status = $certiReturn->is_non_certi ? 'Non Certi' : 'Certi';
                        }
                        ?>
                        <tr>
                            <td>
                                <a href="{{ route('admin.purchase.edit', $purchase->id) }}" target="_blank" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-edit"></i></a>
                                @if($certiReturn)
                                <button
                                    class="btn btn-outline-info waves-effect waves-light priceBtn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#priceUpdateModal"
                                    data-issue-id="{{ $certiReturn->id }}"
                                    data-return-weight="{{ $certiReturn->return_weight }}"
                                    data-price="{{ $certiReturn->price }}"
                                    data-discount="{{ $certiReturn->discount }}"
                                    data-total-price="{{ $certiReturn->total_price }}"
                                    data-return-date="{{ $certiReturn->return_date }}"
                                    data-is-non-certi="{{ $certiReturn->is_non_certi }}">
                                    Price
                                </button>
                                @endif
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
                            <td>{{ optional($purchase->diamond)->diamond_name }}</td>
                            <td>{{ $certiReturn->return_weight ?? '' }}</td>
                            <td>{{ $certiReturn->r_shape ?? '' }}</td>
                            <td>{{ $certiReturn->r_color ?? '' }}</td>
                            <td>{{ $certiReturn->r_clarity ?? '' }}</td>
                            <td>{{ $certiReturn->r_polish ?? '' }}</td>
                            <td>{{ $certiReturn->r_symmetry ?? '' }}</td>
                            <td>
                                <input
                                    type="number"
                                    step="0.01"
                                    class="form-control form-control-sm inline-price-input"
                                    data-issue-id="{{ $certiReturn->id }}"
                                    data-return-weight="{{ $certiReturn->return_weight }}"
                                    data-return-date="{{ $certiReturn->return_date }}"
                                    data-discount="{{ $certiReturn->discount ?? 0 }}"
                                    data-is-non-certi="{{ $certiReturn->is_non_certi }}"
                                    value="{{ $certiReturn->price ?? 0 }}">
                            </td>
                            <td>
                                <input
                                    type="text"
                                    class="form-control form-control-sm total-price-display"
                                    readonly
                                    value="{{ number_format($certiReturn->total_price ?? 0, 2, '.', '') }}">
                            </td>
                            <td>
                                @if($status)
                                <span class="badge {{ $status === 'Non Certi' ? 'bg-danger' : 'bg-success' }}">
                                    {{ $status }}
                                </span>
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


<div class="modal fade" id="priceUpdateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="priceUpdateForm">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Purchase Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="price_issue_id">
                    <input type="hidden" name="is_non_certi" id="price_is_non_certi" value="1">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Return Weight</label>
                            <input type="number" step="0.01" name="return_weight" id="price_return_weight" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Return Date</label>
                            <input type="date" name="return_date" id="price_return_date" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Price</label>
                            <input type="number" step="0.01" name="price" id="price_value" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Discount (%)</label>
                            <input type="number" step="0.01" name="discount" id="price_discount" class="form-control" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total Price</label>
                            <input type="number" step="0.01" name="total_price" id="price_total_price" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="sellModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('admin.sell.store') }}" id="sellForm">
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

                        <!-- <div class="col-md-4 mb-2">
                            <label>Party Name</label>
                            <select name="parties_id" class="form-control">
                                <option value="">Select Party</option>
                                @foreach($partys as $party)
                                <option value="{{$party->id}}">{{$party->fname}} - {{$party->lname}}</option>
                                @endforeach
                            </select>
                        </div> -->

                        <div class="col-md-4 mb-2">
                            <label>Party Name</label>
                            <input type="text" name="parties_name" id="parties_name" class="form-control">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>mobile no</label>
                            <input type="text" name="mobile_no" id="mobile_no" class="form-control">
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

                        <!-- <div class="col-md-4 mb-2">
                            <label>Broker Name</label>
                            <select name="broker_id" class="form-control">
                                <option value="">Select Broker</option>
                                @foreach($brokers as $broker)
                                <option value="{{$broker->id}}">{{$broker->fname}} - {{$broker->lname}}</option>
                                @endforeach
                            </select>
                        </div> -->

                        <div class="col-md-4 mb-2">
                            <label>Broker Name</label>
                            <input type="text" name="broker_name" id="broker_name" class="form-control">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Brokerage (%)</label>
                            <input type="number" step="0.01" name="less_brokerage" id="less_brokerage" class="form-control">
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
    <?php /*document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.sellBtn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('purchase_id').value = this.dataset.id;
                document.getElementById('diamonds_id').value = this.dataset.diamonds_id;
                document.getElementById('dName').innerHTML = this.dataset.name;
                document.getElementById('pWeight').value = this.dataset.pweight;
            });
        });
    }); */ ?>
    document.addEventListener('click', function(e) {

        if (e.target.closest('.sellBtn')) {

            let button = e.target.closest('.sellBtn');

            document.getElementById('purchase_id').value = button.dataset.id;
            document.getElementById('diamonds_id').value = button.dataset.diamonds_id;
            document.getElementById('dName').innerHTML = button.dataset.name;
            document.getElementById('pWeight').value = button.dataset.pweight;

            // calculation trigger kar do
            calculateAmounts();
        }

    });

    function calculatePriceTotal() {
        let weight = parseFloat(document.getElementById('price_return_weight').value) || 0;
        let price = parseFloat(document.getElementById('price_value').value) || 0;
        let discount = parseFloat(document.getElementById('price_discount').value) || 0;

        let base = weight * price;
        let total = discount > 0 ? base - (base * discount / 100) : base;
        document.getElementById('price_total_price').value = total.toFixed(2);
    }

    ['price_return_weight', 'price_value', 'price_discount'].forEach(id => {
        document.getElementById(id).addEventListener('input', calculatePriceTotal);
    });

    document.getElementById('sellForm').addEventListener('submit', function(e) {

        // let party = document.querySelector('[name="parties_id"]').value;
        // let broker = document.querySelector('[name="broker_id"]').value;

        let party = document.querySelector('[name="broker_name"]').value;
        let broker = document.querySelector('[name="parties_name"]').value;

        if (!party && !broker) {
            e.preventDefault(); // form submit rok do
            // alert('Please select at least Party or Broker.');
            showAlert('Please enter at least Party or Broker.', 'danger');
            return false;
        }

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

        let brokerageAmount = 0;

        // 3. Brokerage Amount (Only if % > 0)
        if (brokeragePer > 0) {
            brokerageAmount = (finalAmount * brokeragePer) / 100;
            document.getElementById('brokerage_amount').value = brokerageAmount.toFixed(2);
        } else {
            document.getElementById('brokerage_amount').value = finalAmount;
        }

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

    function saveInlinePrice(input) {
        const issueId = input.dataset.issueId;
        const price = parseFloat(input.value) || 0;
        const returnWeight = parseFloat(input.dataset.returnWeight) || 0;
        const discount = parseFloat(input.dataset.discount) || 0;
        const returnDate = input.dataset.returnDate || new Date().toISOString().slice(0, 10);
        const isNonCerti = input.dataset.isNonCerti || 1;

        const total = discount > 0 ?
            returnWeight * price - (returnWeight * price * discount / 100) :
            returnWeight * price;

        const totalPriceInput = input.closest('tr').querySelector('.total-price-display');
        if (totalPriceInput) {
            totalPriceInput.value = total.toFixed(2);
        }

        const priceBtn = input.closest('tr').querySelector('.priceBtn');
        if (priceBtn) {
            priceBtn.dataset.price = price;
            priceBtn.dataset.discount = discount;
            priceBtn.dataset.returnDate = returnDate;
            priceBtn.dataset.totalPrice = total.toFixed(2);
        }

        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

        fetch('/admin/purchase/update/' + issueId, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-HTTP-Method-Override': 'PATCH'
                },
                body: JSON.stringify({
                    return_weight: returnWeight,
                    return_date: returnDate,
                    price: price,
                    discount: discount,
                    is_non_certi: isNonCerti
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        const message = `HTTP ${response.status} ${response.statusText}: ${text}`;
                        throw new Error(message);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if (typeof showAlert === 'function') {
                        showAlert(data.message || 'Price updated successfully', 'success');
                    }
                } else {
                    if (typeof showAlert === 'function') {
                        showAlert(data.message || 'Could not update price', 'danger');
                    } else {
                        console.error(data.message || 'Could not update price');
                    }
                }
            })
            .catch(error => {
                const message = error.message || 'Inline price update failed';
                if (typeof showAlert === 'function') {
                    showAlert(message, 'danger');
                } else {
                    console.error(message);
                }
            });
    }

    document.addEventListener('blur', function(e) {
        if (e.target.matches('.inline-price-input')) {
            saveInlinePrice(e.target);
        }
    }, true);

    document.addEventListener('keydown', function(e) {
        if (e.target.matches('.inline-price-input') && e.key === 'Enter') {
            e.preventDefault();
            saveInlinePrice(e.target);
            e.target.blur();
        }
    }, true);

    document.getElementById('priceUpdateForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const issueId = document.getElementById('price_issue_id').value;
        const price = parseFloat(document.getElementById('price_value').value) || 0;
        const returnWeight = parseFloat(document.getElementById('price_return_weight').value) || 0;
        const discount = parseFloat(document.getElementById('price_discount').value) || 0;
        const returnDate = document.getElementById('price_return_date').value;
        const isNonCerti = document.getElementById('price_is_non_certi').value || 1;

        const total = discount > 0 ?
            returnWeight * price - (returnWeight * price * discount / 100) :
            returnWeight * price;

        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

        fetch('/admin/purchase/update/' + issueId, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-HTTP-Method-Override': 'PATCH'
                },
                body: JSON.stringify({
                    return_weight: returnWeight,
                    return_date: returnDate,
                    price: price,
                    discount: discount,
                    is_non_certi: isNonCerti
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP ${response.status} ${response.statusText}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Could not update price');
                }

                const row = document.querySelector(`.inline-price-input[data-issue-id="${issueId}"]`);
                if (row) {
                    row.value = price.toFixed(2);
                    row.dataset.discount = discount;
                    row.dataset.returnDate = returnDate;
                    const totalPriceInput = row.closest('tr').querySelector('.total-price-display');
                    if (totalPriceInput) {
                        totalPriceInput.value = total.toFixed(2);
                    }
                    const priceBtn = row.closest('tr').querySelector('.priceBtn');
                    if (priceBtn) {
                        priceBtn.dataset.price = price;
                        priceBtn.dataset.discount = discount;
                        priceBtn.dataset.returnDate = returnDate;
                        priceBtn.dataset.totalPrice = total.toFixed(2);
                    }
                }

                if (typeof showAlert === 'function') {
                    showAlert(data.message || 'Price updated successfully', 'success');
                }

                const modalEl = document.getElementById('priceUpdateModal');
                const bsModal = modalEl && window.bootstrap ? window.bootstrap.Modal.getInstance(modalEl) : null;
                if (bsModal) {
                    bsModal.hide();
                }
            })
            .catch(error => {
                const message = error.message || 'Modal price save failed';
                if (typeof showAlert === 'function') {
                    showAlert(message, 'danger');
                } else {
                    console.error(message);
                }
            });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var priceUpdateModal = document.getElementById('priceUpdateModal');
        if (!priceUpdateModal) return;

        priceUpdateModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Button that triggered the modal
            if (!button) return;

            var issueId = button.getAttribute('data-issue-id');
            var row = button.closest('tr');
            var priceInput = row ? row.querySelector('.inline-price-input') : null;
            var weight = priceInput ? parseFloat(priceInput.dataset.returnWeight) || 0 : parseFloat(button.getAttribute('data-return-weight')) || 0;
            var discount = priceInput ? parseFloat(priceInput.dataset.discount) || 0 : parseFloat(button.getAttribute('data-discount')) || 0;
            var returnDate = priceInput ? priceInput.dataset.returnDate || new Date().toISOString().slice(0, 10) : button.getAttribute('data-return-date') || new Date().toISOString().slice(0, 10);
            var isNonCerti = button.getAttribute('data-is-non-certi') || 1;
            var price = priceInput ? parseFloat(priceInput.value) || 0 : parseFloat(button.getAttribute('data-price')) || 0;
            var totalPrice = price * weight;
            if (discount > 0) {
                totalPrice = totalPrice - (totalPrice * discount / 100);
            }

            document.getElementById('price_issue_id').value = issueId;
            document.getElementById('price_return_weight').value = weight;
            document.getElementById('price_value').value = price;
            document.getElementById('price_discount').value = discount;
            document.getElementById('price_total_price').value = totalPrice.toFixed(2);
            document.getElementById('price_return_date').value = returnDate;
            document.getElementById('price_is_non_certi').value = isNonCerti;

            var form = document.getElementById('priceUpdateForm');
            if (form) {
                form.action = '/admin/purchase/update/' + issueId;
            }

            // ensure total is calculated when modal opens
            var ev = new Event('input');
            document.getElementById('price_return_weight').dispatchEvent(ev);
        });
    });
</script>
@endsection