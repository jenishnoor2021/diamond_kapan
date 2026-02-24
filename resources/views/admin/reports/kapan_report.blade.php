@extends('layouts.admin')
@section('content')

<!-- Start Page Title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">Kapan Summary Report</h4>
    </div>
  </div>
</div>
<!-- End Page Title -->

<!-- <form method="GET" action="{{ route('kapan.report') }}" class="row mb-3">

  <div class="col-md-3">
    <input type="text" name="kapan_name"
      value="{{ request('kapan_name') }}"
      class="form-control"
      placeholder="Search Kapan Name">
  </div>

  <div class="col-md-3">
    <input type="date" name="from_date"
      value="{{ request('from_date') }}"
      class="form-control">
  </div>

  <div class="col-md-3">
    <input type="date" name="to_date"
      value="{{ request('to_date') }}"
      class="form-control">
  </div>

  <div class="col-md-2">
    <button class="btn btn-primary w-100">Filter</button>
  </div>

</form> -->


<!-- Report Table -->
<div class="card">
  <div class="card-body">

    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Kapan Name</th>
            <th>Kapan Parts</th>
            <th>Total Diamonds</th>
            <th>Kapan Weight</th>
            <th>Prediction Weight</th>
            <th>Return Weight</th>
            <th>Total Pending Process Diamonds</th>
          </tr>
        </thead>
        <tbody>

          @foreach($kapans as $kapan)
          <tr>
            <td>
              <a href="{{ route('kapan.detail',$kapan->id) }}">
                {{ $kapan->kapan_name }}
              </a>
            </td>
            <td>{{ $kapan->kapan_parts }}</td>
            <td>{{ $kapan->total_diamonds }}</td>
            <td><strong>{{ number_format($kapan->kapan_weight,2) }}</strong></td>
            <td>
              <div>
                <strong>
                  {{ number_format($kapan->prediction_weight ?? 0,2) }}
                </strong>
                <small class="text-primary">
                  ({{ number_format($kapan->prediction_percent,2) }}%)
                </small>
              </div>
            </td>

            <td>
              <div>
                <strong>
                  {{ number_format($kapan->return_weight ?? 0,2) }}
                </strong>
                <small class="text-success">
                  ({{ number_format($kapan->return_percent,2) }}%)
                </small>
              </div>
            </td>
            <td>{{ $kapan->total_pending_process_diamond ?? 0 }}</td>
          </tr>
          @endforeach

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