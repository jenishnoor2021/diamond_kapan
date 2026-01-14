@extends('layouts.admin')
@section('content')
<div class="row mt-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">

                @include('includes.flash_message')

                <div class="card-title">ADD khata Bill</div>
                <hr>
                {!! Form::open(['method'=>'POST', 'action'=> 'AdminKhataBillController@store','files'=>true,'class'=>'form-horizontal','name'=>'khataBillform']) !!}
                @csrf
                <input type="hidden" name="khatas_id" value="{{$khata->id}}">
                <div class="form-group">
                    <label for="bill_no">Bill No</label>
                    <input type="text" name="bill_no" class="form-control form-control-rounded" placeholder="Enter bill no" required>
                </div>
                <div class="form-group">
                    <label for="bill_date">Bill Data</label>
                    <input type="date" name="bill_date" class="form-control form-control-rounded" placeholder="Enter date" required>
                </div>
                <div class="form-group">
                    <label for="amount">Bill Amount</label>
                    <input type="number" name="amount" class="form-control form-control-rounded" placeholder="Enter amount" required>
                </div>
                <div class="form-group">
                    <label or="note">Note</label>
                    <textarea type="text" name="note" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-light btn-round px-5"><i class="fa fa-plus"></i> ADD</button>
                </div>
                </form>

                <table class="table align-items-center table-flush table-borderless">
                    <thead>
                        <tr>
                            <th>Bill No</th>
                            <th>Bill date</th>
                            <th>Bill amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($khatabills as $khatabill)
                        <tr>
                            <td>{{ $khatabill->bill_no }}</td>
                            <td>{{ $khatabill->bill_date }}</td>
                            <td>{{ $khatabill->amount }}</td>
                            <td>
                                <a href="{{ route('admin.khatabill.edit',$khatabill->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Expense List
                <div class="card-action">

                    <div class="card-title">ADD Expense</div>
                    <hr>
                    {!! Form::open(['method'=>'POST', 'action'=> 'AdminExpenseController@store','files'=>true,'class'=>'form-horizontal','name'=>'khataBillform']) !!}
                    @csrf
                    <input type="hidden" name="khatas_id" value="{{$khata->id}}">
                    <div class="form-group">
                        <label for="expense_date">Expense Data</label>
                        <input type="date" name="expense_date" class="form-control form-control-rounded" placeholder="Enter date" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" class="form-control form-control-rounded" placeholder="Enter amount" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_type">Payment Type</label>
                        <select name="payment_type" class="form-control" required>
                            <option value="cash">Cash</option>
                            <option value="online">Online</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label or="note">Note</label>
                        <textarea type="text" name="note" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-light btn-round px-5"><i class="fa fa-plus"></i> ADD</button>
                    </div>
                    </form>

                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center table-flush table-borderless">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($expenses as $expense)
                            <td>{{$expense->expense_date}}</td>
                            <td>{{$expense->amount}}</td>
                            <td>{{$expense->payment_type}}</td>
                            <td>{{$expense->note}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!--End Row-->
@endsection