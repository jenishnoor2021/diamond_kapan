@extends('layouts.admin')
@section('content')

<!-- Start Page Title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">Worker Report</h4>
    </div>
  </div>
</div>
<!-- End Page Title -->


<!-- Filter Card -->
<form method="GET" action="{{ route('worker.report') }}" class="card p-3 mb-3">
  <div class="row">

    <div class="col-md-2">
      <label>Designation</label>
      <select name="designation" id="designation" class="form-control">
        <option value="">All Designation</option>
        @foreach($designations as $des)
        <option value="{{ $des->id }}"
          {{ request('designation') == $des->id ? 'selected' : '' }}>
          {{ $des->name }}
        </option>
        @endforeach
      </select>
    </div>

    <div class="col-md-2">
      <label>Worker</label>
      <select name="worker_id" id="worker" class="form-control">
        <option value="">All Worker</option>
      </select>
    </div>

    <div class="col-md-2">
      <label>Kapan</label>
      <select name="kapans_id" class="form-control">
        <option value="">All Kapan</option>
        @foreach($kapans as $kapan)
        <option value="{{ $kapan->id }}"
          {{ request('kapans_id') == $kapan->id ? 'selected' : '' }}>
          {{ $kapan->kapan_name }}
        </option>
        @endforeach
      </select>
    </div>

    <div class="col-md-2">
      <label>Status</label>
      <select name="status" class="form-control">
        <option value="">All Status</option>
        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
      </select>
    </div>

    <div class="col-md-2">
      <label>From Date</label>
      <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
    </div>

    <div class="col-md-2">
      <label>To Date</label>
      <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
    </div>

    <div class="col-md-12 mt-3">
      <button class="btn btn-primary">Filter</button>
      <a href="{{ route('worker.report') }}" class="btn btn-secondary">Reset</a>
    </div>

  </div>
</form>


<!-- Summary Boxes -->
<!-- <div class="row mb-3">

  <div class="col-md-4 bg-primary text-white p-3">
    Total Issue Weight
    <h5>{{ number_format($totalIssueWeight, 2) }}</h5>
  </div>

  <div class="col-md-4 bg-success text-white p-3">
    Total Return Weight
    <h5>{{ number_format($totalReturnWeight, 2) }}</h5>
  </div>

  <div class="col-md-4 bg-danger text-white p-3">
    Pending Weight
    <h5>{{ number_format($totalPendingWeight, 2) }}</h5>
  </div>

</div> -->


<!-- Report Table -->
<div class="card">
  <div class="card-body">

    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Worker</th>
            <th>Designation</th>
            <th>Kapan</th>
            <th>Diamond</th>
            <th>Prediction Weight</th>
            <th>Return Weight</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>

          @forelse($reports as $row)
          <tr>
            <td>{{ $row->worker->full_name ?? '' }}</td>
            <td>{{ $row->worker->designations->name ?? '' }}</td>
            <td>{{ $row->kapans->kapan_name ?? '' }}</td>
            <td>{{ $row->diamond->diamond_name ?? '' }}</td>
            <td>{{ number_format($row->diamond->prediction_weight,2) }}</td>
            <td>{{ number_format($row->return_weight,2) }}</td>
            <td>
              <span class="badge {{ $row->status == 'Returned' ? 'bg-success' : 'bg-danger' }}">
                {{ $row->status }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center">No Data Found</td>
          </tr>
          @endforelse

        </tbody>
      </table>
    </div>

  </div>
</div>

@endsection


@section('script')

<script>
  document.getElementById('designation').addEventListener('change', function() {

    fetch('/get-workers-by-designation?designation=' + this.value)
      .then(response => response.json())
      .then(data => {

        let workerSelect = document.getElementById('worker');
        workerSelect.innerHTML = '<option value="">All Worker</option>';

        data.forEach(worker => {
          workerSelect.innerHTML +=
            `<option value="${worker.id}">
                    ${worker.fname} ${worker.lname}
                </option>`;
        });

      });

  });
</script>

@endsection