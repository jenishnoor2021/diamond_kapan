@extends('layouts.admin')
@section('style')
@endsection
@section('content')
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <h4 class="mb-0 font-size-18">Diamonds</h4>

        <a href="{{ route('admin.diamonds.index') }}" class="btn btn-sm btn-primary">
          <i class="mdi mdi-arrow-right-bold-circle-outline me-1"></i> Diamonds List
        </a>

        <a href="{{ route('admin.issue.index') }}" class="btn btn-sm btn-success">
          <i class="mdi mdi-arrow-right-bold-circle-outline me-1"></i> Issue
        </a>

        <a href="{{ route('admin.return.index') }}" class="btn btn-sm btn-danger">
          <i class="mdi mdi-arrow-left-bold-circle-outline me-1"></i> Return
        </a>
      </div>
    </div>
  </div>
</div>
<!-- end page title -->

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        @include('includes.flash_message')

        <form action="{{ route('admin.diamonds.index') }}" method="get">
          @csrf

          <div data-repeater-list="group-a">
            <div data-repeater-item class="row">
              <div class="col-lg-2">
                <label for="kapans_id">Kapan</label>
                <select name="kapans_id" id="kapans_id" class="form-select" onchange="this.form.submit();" required>
                  <option value="">Select Kapan</option>
                  @foreach($kapans as $kapan)
                  <option value="{{$kapan->id}}" {{ request()->kapans_id == $kapan->id ? 'selected' : '' }}>{{$kapan->kapan_name}}</option>
                  @endforeach
                </select>
                @if ($errors->has('kapans_id'))
                <div class="error text-danger">{{ $errors->first('kapans_id') }}</div>
                @endif
              </div>

              <div class="col-lg-4">
                <!-- <button type="submit" class="btn btn-success mt-2 w-md">List</button> -->
                <a class="btn btn-light mt-2 w-md" href="/admin/diamonds">Clear</a>
              </div>

            </div>

          </div>
        </form>
      </div>
    </div>

    @if(count($diamonds) > 0)
    <div class="card">
      <div class="card-body">

        <table id="datatable" class="table table-bordered mt-3">
          <thead>
            <tr>
              <th>Action</th>
              <!-- <th>Kapan</th> -->
              <th>Kapan Part</th>
              <th>Name</th>
              <th>Weight</th>
              <th>Barcode</th>
            </tr>
          </thead>
          <tbody>
            @foreach($diamonds as $diamond)
            <tr>
              <td>
                <a href="{{ route('admin.diamond.edit', $diamond->id) }}" target="_blank" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-edit"></i></a>
                <!-- <a href="{{ route('admin.diamond.destroy', $diamond->id) }}" onclick="return confirm('Sure ! You want to delete ?');" class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-trash"></i></a> -->
              </td>
              <!-- <td>{{ $diamond->kapan->kapan_name ?? '-' }}</td> -->
              <td>{{ $diamond->kapanPart->name ?? '-' }}</td>
              <td>{{ $diamond->diamond_name }}</td>
              <td>{{ number_format($diamond->weight, 2) }}</td>
              <td>{{ $diamond->barcode_number ?? '-' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @elseif(request()->kapans_id != '')
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <span class="text-danger">No record found</span>
          </div>
        </div>
      </div>
    </div>
    @endif

  </div>
</div>
<!-- end row -->
@endsection

@section('script')
<script>
  $(document).ready(function() {
    $('#designation').change(function() {
      var designation = $(this).val();
      $('#worker_id').empty();

      if (designation == 'all') {
        $('#worker_id').append('<option value="">Select worker</option>');
      } else if (designation && designation != 'all') {
        $.ajax({
          type: 'POST',
          url: '/admin/get-workers',
          data: {
            '_token': '{{ csrf_token() }}',
            'designation': designation,
          },
          success: function(data) {
            $('#worker_id').append('<option value="">Select worker</option>');
            $.each(data, function(key, value) {
              $('#worker_id').append('<option value="' + value.id + '">' + value.fname + ' ' + value.lname + '</option>');
            });
          }
        });
      } else {
        $('#worker_id').append('<option value="">Select worker</option>');
      }
    });
  });
</script>
<script>
  $('#checkAll').on('change', function() {
    $('.partCheckbox').prop('checked', this.checked);
  });

  $('.partCheckbox').on('change', function() {
    if (!this.checked) {
      $('#checkAll').prop('checked', false);
    }
  });
</script>
<script>
  document.getElementById('storeIssuesKapan').addEventListener('submit', function(e) {

    let checked = document.querySelectorAll('.partCheckbox:checked').length;

    if (checked === 0) {
      alert('Please select at least one part to issue');
      e.preventDefault();
    }
  });
</script>
@endsection