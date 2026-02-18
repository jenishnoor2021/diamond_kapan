@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">All Diamonds List</h4>

    </div>
  </div>
</div>
<!-- end page title -->

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        @include('includes.flash_message')

        <div id="right">
          <div id="menu" class="mb-3">

            <form action="{{ route('admin.all.diamonds') }}" method="get">
              @csrf

              <div class="row align-items-end">

                {{-- Kapan --}}
                <div class="col-md-2">
                  <label class="form-label">Kapan</label>
                  <select name="kapans_id" class="form-select">
                    <option value="">All</option>
                    @foreach($kapans as $kapan)
                    <option value="{{$kapan->id}}" {{ request()->kapans_id == $kapan->id ? 'selected' : '' }}>
                      {{$kapan->kapan_name}}
                    </option>
                    @endforeach
                  </select>
                </div>

                {{-- Status --}}
                <div class="col-md-2">
                  <label class="form-label">Status</label>
                  <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="pending" {{ request()->status=='pending'?'selected':'' }}>Pending</option>
                    <option value="purchased" {{ request()->status=='purchased'?'selected':'' }}>Purchased</option>
                    <option value="sell" {{ request()->status=='sell'?'selected':'' }}>Sell</option>
                  </select>
                </div>

                {{-- Buttons --}}
                <div class="col-md-4">
                  <button type="submit" class="btn btn-primary">
                    Filter
                  </button>

                  <a href="{{ route('admin.all.diamonds') }}" class="btn btn-secondary">
                    Clear
                  </a>
                </div>

              </div>
            </form>

          </div>
        </div>

        <hr style="border:1px solid #000;">


        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
          <thead>
            <tr>
              <th>Action</th>
              <th>Kapan</th>
              <th>Kapan Part</th>
              <th>Name</th>
              <th>Barcode</th>
              <th>Status</th>
            </tr>
          </thead>

          <tbody>
            @foreach ($diamonds as $diamond)
            <tr>
              <td>
                <a href="javascript:void(0)"
                  class="btn btn-outline-primary viewDiamond"
                  data-id="{{ $diamond->id }}">
                  <i class="fa fa-eye"></i>
                </a>
              </td>
              <td>{{ $diamond->kapan->kapan_name ?? '-' }}</td>
              <td>{{ $diamond->kapanPart->name ?? '-' }}</td>
              <td>{{ $diamond->diamond_name }}</td>
              <td>{{ $diamond->barcode_number ?? '-' }}</td>
              <td>
                @if($diamond->status == 'pending')
                <span class="badge bg-warning">Pending</span>
                @elseif($diamond->status == 'purchased')
                <span class="badge bg-info">Purchased</span>
                @elseif($diamond->status == 'sell')
                <span class="badge bg-success">Sell</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </div> <!-- end col -->
</div> <!-- end row -->


<div class="modal fade" id="diamondModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Diamond Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="diamondDetailBody">
        Loading...
      </div>

    </div>
  </div>
</div>

@endsection

@section('script')
<script>
  $(document).on('click', '.viewDiamond', function() {

    let id = $(this).data('id');

    $('#diamondModal').modal('show');
    $('#diamondDetailBody').html('Loading...');

    $.ajax({
      url: "/admin/diamond/detail/" + id,
      type: "GET",
      success: function(res) {
        $('#diamondDetailBody').html(res.html);
      },
      error: function() {
        $('#diamondDetailBody').html('<div class="text-danger">Failed to load data</div>');
      }
    });

  });
</script>
@endsection