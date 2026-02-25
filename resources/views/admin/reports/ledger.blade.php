@extends('layouts.admin')
@section('content')

@php
$backRoute = $khata->type == 'income'
? route('admin.income-summary')
: route('admin.expense-summary');
@endphp

<h4 class="mb-3">
  <a href="{{ $backRoute }}" class="btn btn-danger btn-sm">
    <i class="fa fa-arrow-left"></i>
  </a>
  Ledger : {{ $khata->fname }} {{ $khata->lname }}
  <span class="badge bg-info text-dark">
    {{ ucfirst($khata->type) }} Account
  </span>
</h4>

<table class="table table-bordered">
  <thead class="table-dark">
    <tr>
      <th>Date</th>
      <th>Type</th>
      <th>Note</th>
      <th class="text-end">Debit</th>
      <th class="text-end">Credit</th>
      <th class="text-end">Balance</th>
    </tr>
  </thead>

  <tbody>
    @foreach($entries as $e)
    <tr>
      <td>{{ $e['date'] }}</td>
      <td>{{ $e['type'] }}</td>
      <td>{{ $e['note'] }}</td>

      <td class="text-end text-danger">
        {{ $e['debit']>0 ? number_format($e['debit'],2):'' }}
      </td>

      <td class="text-end text-success">
        {{ $e['credit']>0 ? number_format($e['credit'],2):'' }}
      </td>

      @php
      $balance = $e['balance'] ?? 0;

      if($khata->type == 'expense'){
      $label = $balance >= 0 ? 'Dr' : 'Cr';
      }else{
      $label = $balance >= 0 ? 'Cr' : 'Dr';
      }
      @endphp

      <td class="text-end fw-bold {{ $balance>=0?'text-danger':'text-success' }}">
        ₹ {{ number_format(abs($balance),2) }} {{ $label }}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

@endsection