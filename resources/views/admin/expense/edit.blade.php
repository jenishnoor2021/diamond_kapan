@extends('layouts.admin')
@section('content')

<div class="row">

    <div class="col-12 mb-3">
        <div class="row">

            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body text-center">
                        <h6>Total Bill Amount</h6>
                        <h4>₹ {{ number_format($totalBillAmount, 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-body text-center">
                        <h6>Total Expense Amount</h6>
                        <h4>₹ {{ number_format($totalExpenseAmount, 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body text-center">
                        <h6>Remaining Amount</h6>
                        <h4>₹ {{ number_format($remainingAmount, 2) }}</h4>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ================= KHATA BILL ================= --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Khata Bills</h5>
                <button class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#addBillModal">
                    <i class="fa fa-plus"></i> Add
                </button>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Bill No</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Note</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($khatabills as $bill)
                        <tr>
                            <td>{{ $bill->bill_no }}</td>
                            <td>{{ $bill->bill_date }}</td>
                            <td>{{ $bill->amount }}</td>
                            <td>{{ $bill->note }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary editBill" data-id="{{ $bill->id }}">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= EXPENSE ================= --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Expenses</h5>
                <button class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#addExpenseModal">
                    <i class="fa fa-plus"></i> Add
                </button>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Note</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_date }}</td>
                            <td>{{ $expense->amount }}</td>
                            <td>{{ ucfirst($expense->payment_type) }}</td>
                            <td>{{ $expense->note }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary editExpense" data-id="{{ $expense->id }}">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>


@include('admin.expense.modals')

@endsection

@section('script')

<script>
    $('.editBill').click(function() {
        let id = $(this).data('id');
        $.get('/khata-bill/' + id + '/edit', function(res) {
            $('#e_bill_no').val(res.bill_no);
            $('#e_bill_date').val(res.bill_date);
            $('#e_amount').val(res.amount);
            $('#e_note').val(res.note);
            $('#editBillForm').attr('action', '/khata-bill/' + id);
            $('#editBillModal').modal('show');
        });
    });

    $('.editExpense').click(function() {
        let id = $(this).data('id');
        $.get('/expense/' + id + '/edit', function(res) {
            $('#e_expense_date').val(res.expense_date);
            $('#e_expense_amount').val(res.amount);
            $('#e_payment_type').val(res.payment_type);
            $('#e_expense_note').val(res.note);
            $('#editExpenseForm').attr('action', '/expense/' + id);
            $('#editExpenseModal').modal('show');
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.date-input').forEach(function(input) {
            input.addEventListener('focus', function() {
                if (this.showPicker) {
                    this.showPicker();
                }
            });
        });
    });
</script>
@endsection