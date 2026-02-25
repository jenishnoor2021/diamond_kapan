@extends('layouts.admin')

@section('content')

<h4 class="mb-3">
  <a href="{{ route('sell.report') }}" class="btn btn-danger btn-sm">
    <i class="fa fa-arrow-left"></i>
  </a>
  Sold Diamond Detail : {{ $kapan->kapan_name }}
</h4>

<div class="row">

  <div class="col-md-2">
    <div class="card shadow-sm text-center p-3">
      <small>Total Sold Diamonds</small>
      <h5>{{ $diamonds->count() }}</h5>
    </div>
  </div>

  <!-- <div class="col-md-3">
    <div class="card shadow-sm text-center p-3 bg-light">
      <small>Prediction Weight</small>
      <h5 class="text-primary">
        {{ number_format($totalPrediction,2) }}
      </h5>
    </div>
  </div> -->

  <div class="col-md-2">
    <div class="card shadow-sm text-center p-3">
      <small>Sold Weight</small>
      <h5>
        {{ number_format($totalSoldWeight,2) }}
      </h5>
    </div>
  </div>

  <div class="col-md-2">
    <div class="card shadow-sm text-center p-3 bg-light">
      <small>Paid Amount</small>
      <h5 class="text-success">
        ₹ {{ number_format($paidAmount ?? 0, 2) }}
      </h5>
    </div>
  </div>

  <div class="col-md-2">
    <div class="card shadow-sm text-center p-3 bg-light">
      <small>Unpaid Amount</small>
      <h5 class="text-danger">
        ₹ {{ number_format($unpaidAmount ?? 0, 2) }}
      </h5>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card shadow-sm text-center p-3 bg-light">
      <small>Total Sell Amount</small>
      <h5 class="text-dark">
        {{ number_format($totalSellAmount,2) }}
      </h5>
    </div>
  </div>

</div>

<div class="card mt-3 shadow-sm border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-1">
      <span>Sell Completion</span>
      <strong>{{ number_format($returnPercent,2) }}%</strong>
    </div>

    <div class="progress" style="height: 20px;">
      <div class="progress-bar bg-success"
        style="width: {{ $returnPercent }}%;">
      </div>
    </div>
  </div>
</div>

<table class="table table-bordered mt-3">
  <thead class="table-dark">
    <tr>
      <th>Diamond</th>
      <th>Prediction Weight</th>
      <th>Sold Weight</th>
      <th>Rate</th>
      <th>Amount</th>
      <th>Payment Status</th>
      <th>Process Detail</th>
    </tr>
  </thead>
  <tbody>
    @foreach($diamonds as $diamond)
    <tr>
      <td>{{ $diamond->diamond_name }}</td>

      <td>{{ $diamond->prediction_weight }}</td>

      <td>
        {{ $diamond->issues->sum('return_weight') }}
      </td>

      <td>
        {{ optional($diamond->sell)->rate_per_ct }}
      </td>

      <td>
        {{ optional($diamond->sell)->final_amount }}
      </td>

      <td>
        <span class="badge 
                    {{ optional($diamond->sell)->payment_status == 'paid' 
                        ? 'bg-success' 
                        : 'bg-danger' }}">
          {{ ucfirst(optional($diamond->sell)->payment_status ?? 'pending') }}
        </span>
      </td>

      <td>
        @foreach($diamond->issues as $issue)
        <div class="border p-1 mb-1 bg-light">
          Return: {{ $issue->return_weight }}
        </div>
        @endforeach
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

@endsection