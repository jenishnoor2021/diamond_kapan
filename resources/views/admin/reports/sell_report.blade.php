@extends('layouts.admin')
@section('content')

<!-- Start Page Title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">Sell Summary Report</h4>
    </div>
  </div>
</div>
<!-- End Page Title -->

<!-- Report Table -->
<div class="card">
  <div class="card-body">

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Kapan Name</th>
            <th>Kapan Weight</th>
            <!-- <th>Parts</th> -->
            <th>Total Diamonds</th>
            <!-- <th>Prediction Weight</th> -->
            <th>Pending Diamonds</th>
            <th>Purchse Diamonds</th>
            <th>Sold Diamonds</th>
            <th>Sold Weight</th>
            <th>Total Amount</th>
            <!-- <th>Pending Weight</th> -->
          </tr>
        </thead>
        <tbody>
          @foreach($kapans as $kapan)
          <tr>
            <td>
              <a href="{{ route('sell.report.detail', $kapan->id) }}">
                {{ $kapan->kapan_name }}
              </a>
            </td>
            <td>{{ $kapan->kapan_weight }}</td>
            <!-- <td>{{ $kapan->parts_count }}</td> -->
            <td>{{ $kapan->diamonds_count }}</td>
            <!-- <td>{{ $kapan->total_diamond_weight }}</td> -->
            <td>{{ $kapan->pending_diamonds_count }}</td>
            <td>{{ $kapan->purchase_diamonds_count }}</td>
            <td>{{ $kapan->sold_diamonds_count }}</td>

            <td>{{ number_format($kapan->sold_weight ?? 0, 2) }}</td>
            <td>{{ number_format($kapan->total_sell_amount ?? 0, 2) }}</td>


            <!-- <td>
              {{ number_format(
            ($kapan->total_diamond_weight ?? 0) - ($kapan->sold_weight ?? 0),
            2
        ) }}
            </td> -->
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