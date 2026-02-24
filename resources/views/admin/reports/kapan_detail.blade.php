@extends('layouts.admin')

@section('content')

<h4 class="mb-3">
  <a href="{{ route('kapan.report') }}" class="btn btn-danger btn-sm">
    <i class="fa fa-arrow-left"></i>
  </a>
  Kapan Detail : {{ $kapan->kapan_name }}
</h4>

@php
$returnPercent=$kapan->prediction_weight > 0
? ($kapan->return_weight / $kapan->prediction_weight) * 100
: 0;

$pendingWeight = ($kapan->prediction_weight ?? 0) - ($kapan->return_weight ?? 0);
@endphp

<div class="row">

  <!-- Kapan Weight -->
  <div class="col-md-2">
    <div class="card shadow-sm border-0 text-center p-3">
      <small class="text-muted">Kapan Weight</small>
      <h5 class="mb-0">{{ number_format($kapan->kapan_weight,2) }}</h5>
    </div>
  </div>

  <!-- Parts -->
  <div class="col-md-2">
    <div class="card shadow-sm border-0 text-center p-3">
      <small class="text-muted">Total Parts</small>
      <h5 class="mb-0">{{ $kapan->parts_count }}</h5>
    </div>
  </div>

  <!-- Diamonds -->
  <div class="col-md-2">
    <div class="card shadow-sm border-0 text-center p-3">
      <small class="text-muted">Total Diamonds</small>
      <h5 class="mb-0">{{ $kapan->diamonds_count }}</h5>
    </div>
  </div>

  <!-- Prediction -->
  <div class="col-md-2">
    <div class="card shadow-sm border-0 text-center p-3 bg-light">
      <small class="text-muted">Prediction Weight</small>
      <h5 class="mb-0 text-primary">
        {{ number_format($kapan->prediction_weight ?? 0,2) }}
      </h5>
    </div>
  </div>

  <!-- Return -->
  <div class="col-md-2">
    <div class="card shadow-sm border-0 text-center p-3 bg-light">
      <small class="text-muted">Return Weight</small>
      <h5 class="mb-0 text-success">
        {{ number_format($kapan->return_weight ?? 0,2) }}
      </h5>
    </div>
  </div>

  <!-- Pending -->
  <div class="col-md-2">
    <div class="card shadow-sm border-0 text-center p-3 bg-light">
      <small class="text-muted">Pending Weight</small>
      <h5 class="mb-0 text-danger">
        {{ number_format($pendingWeight,2) }}
      </h5>
    </div>
  </div>

</div>

<div class="card mt-3 shadow-sm border-0">
  <div class="card-body">

    <div class="d-flex justify-content-between mb-1">
      <span>Return Progress</span>
      <strong>{{ number_format($returnPercent,2) }}%</strong>
    </div>

    <div class="progress" style="height: 20px;">
      <div class="progress-bar bg-success"
        role="progressbar"
        style="width: {{ $returnPercent }}%;">
      </div>
    </div>

  </div>
</div>

<table class="table table-bordered">
  <thead class="table-dark">
    <tr>
      <th>Diamond Name</th>
      <!-- <th>Weight</th> -->
      <th>Prediction Weight</th>
      <th>Return Weight</th>
      <th>Status</th>
      <th>Issue Process</th>
    </tr>
  </thead>
  <tbody>

    @foreach($diamonds as $diamond)
    <tr>
      <td>{{ $diamond->diamond_name }}</td>
      <!-- <td>{{ $diamond->weight }}</td> -->
      <td>{{ $diamond->prediction_weight }}</td>
      <td>
        @foreach($diamond->issues as $issue)
        {{ $issue->return_weight }}
        @endforeach
      </td>
      <td>
        @if($diamond->status == 'sell')
        <span class="badge bg-success">Sell</span>
        @elseif($diamond->status == 'pending')
        <span class="badge bg-secondary">Pending</span>
        @else
        <span class="badge bg-warning text-dark">Purchased</span>
        @endif
      </td>
      <td>

        @foreach($diamond->issues as $issue)
        <div class="border p-1 mb-1">
          Worker: {{ $issue->worker_id }} <br>
          Issue Wt: {{ $issue->issue_weight }} <br>
          Return Wt: {{ $issue->return_weight }} <br>
          Return Status:
          {{ $issue->is_return ? 'Returned' : 'Pending' }}
        </div>
        @endforeach

      </td>
    </tr>
    @endforeach

  </tbody>
</table>

@endsection