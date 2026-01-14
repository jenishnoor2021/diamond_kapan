@extends('layouts.admin')
@section('content')
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                @include('includes.flash_message')

                <div class="card-title">khata Expense List</div>

                <div class="col-12 mb-3">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="card text-white bg-primary">
                                <div class="card-body text-center">
                                    <h6>Total Income Amount</h6>
                                    <h4>₹ {{ number_format($totalIncomeAmount, 2) }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card text-white bg-danger">
                                <div class="card-body text-center">
                                    <h6>Total Expense Amount</h6>
                                    <h4>₹ {{ number_format($totalExpenseAmount, 2) }}</h4>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <table class="table align-items-center table-flush table-borderless">
                    <thead>
                        <tr>
                            <th>Khata Name</th>
                            <th>Total Bill Amount</th>
                            <th>Expence Amount</th>
                            <th>Remaining Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($khatas as $khata)

                        @php
                        $billAmount = $khata->total_bill_amount ?? 0;
                        $expenseAmount = $khata->total_expense_amount ?? 0;
                        $remaining = $billAmount - $expenseAmount;
                        @endphp

                        <tr>
                            <td>{{$khata->fname}} - {{$khata->lname}}</td>
                            <td>₹ {{ number_format($billAmount, 2) }}</td>

                            <td>₹ {{ number_format($expenseAmount, 2) }}</td>

                            <td class="{{ $remaining < 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                ₹ {{ number_format($remaining, 2) }}
                            </td>
                            <td><a href="{{ route('admin.expnese.edit.simple', $khata->id) }}" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-edit"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div><!--End Row-->
@endsection