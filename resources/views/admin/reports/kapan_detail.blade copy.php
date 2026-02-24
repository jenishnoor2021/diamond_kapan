@extends('layouts.admin')

@section('content')

<h4 class="mb-3">
  <a href="{{ route('kapan.report') }}" class="btn btn-danger btn-sm">
    <i class="fa fa-arrow-left"></i>
  </a>
  Kapan Detail : {{ $kapan->kapan_name }}
</h4>

<div class="row mb-3">

  <div class="col-md-2">
    <div class="card bg-light text-center p-2">
      <strong>Kapan Weight</strong>
      <div>{{ number_format($kapan->kapan_weight,2) }}</div>
    </div>
  </div>

  <div class="col-md-2">
    <div class="card bg-light text-center p-2">
      <strong>Total Parts</strong>
      <div>{{ $kapan->parts_count }}</div>
    </div>
  </div>

  <div class="col-md-2">
    <div class="card bg-light text-center p-2">
      <strong>Total Diamonds</strong>
      <div>{{ $kapan->diamonds_count }}</div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-light text-center p-2">
      <strong>Total Prediction Weight</strong>
      <div>{{ number_format($kapan->prediction_weight ?? 0,2) }}</div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card bg-light text-center p-2">
      <strong>Total Return Weight</strong>
      <div>{{ number_format($kapan->return_weight ?? 0,2) }}</div>
    </div>
  </div>

</div>

<table class="table table-bordered">
  <thead class="table-dark">
    <tr>
      <th>Diamond Name</th>
      <th>Weight</th>
      <th>Prediction Weight</th>
      <th>Status</th>
      <th>Issue Process</th>
    </tr>
  </thead>
  <tbody>

    @foreach($diamonds as $diamond)
    <tr>
      <td>{{ $diamond->diamond_name }}</td>
      <td>{{ $diamond->weight }}</td>
      <td>{{ $diamond->prediction_weight }}</td>
      <td>{{ $diamond->status }}</td>
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