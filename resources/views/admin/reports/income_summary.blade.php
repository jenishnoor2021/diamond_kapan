@extends('layouts.admin')
@section('content')

<h4 class="mb-3">Income Summary</h4>

<form method="GET" action="{{ route('admin.income-summary') }}" class="card p-3 mb-3">
  <div class="row">

    <div class="col-md-3">
      <label>From Date</label>
      <input type="date" name="from" value="{{ request('from') }}" class="form-control">
    </div>

    <div class="col-md-3">
      <label>To Date</label>
      <input type="date" name="to" value="{{ request('to') }}" class="form-control">
    </div>

    <div class="col-md-3">
      <label>Party</label>
      <select name="khata_id" class="form-control">
        <option value="">All Party</option>
        @foreach($allKhatas as $p)
        <option value="{{ $p->id }}" {{ request('khata_id')==$p->id?'selected':'' }}>
          {{ $p->fname }} {{ $p->lname }}
        </option>
        @endforeach
      </select>
    </div>

    <div class="col-md-3 mt-4">
      <button class="btn btn-primary">Filter</button>
      <a href="{{ route('admin.income-summary') }}" class="btn btn-secondary">Reset</a>
    </div>

  </div>
</form>


<div class="row mb-3">
  <div class="col-md-4 bg-primary text-white p-3">
    Total Bill ₹ {{ number_format($total_bill,2) }}
  </div>

  <div class="col-md-4 bg-success text-white p-3">
    Received ₹ {{ number_format($total_received,2) }}
  </div>

  <div class="col-md-4 bg-dark text-white p-3">
    Balance ₹ {{ number_format($company_balance,2) }}
  </div>
</div>


<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>Party</th>
      <th>Bill</th>
      <th>Received</th>
      <th>Balance</th>
      <th>Ledger</th>
    </tr>
  </thead>

  <tbody>
    @foreach($khatas as $k)
    <tr>
      <td>{{ $k->fname }} {{ $k->lname }}</td>

      <td>₹ {{ number_format($k->total_bill ?? 0,2) }}</td>

      <td class="text-success">
        ₹ {{ number_format($k->total_received ?? 0,2) }}
      </td>

      <td class="{{ $k->balance >= 0 ? 'text-success':'text-danger' }}">
        ₹ {{ number_format($k->balance,2) }}
      </td>

      <td>
        <a href="{{ route('admin.ledger',$k->id) }}" class="btn btn-sm btn-primary">
          View Ledger
        </a>
      </td>

    </tr>
    @endforeach
  </tbody>
</table>

@endsection