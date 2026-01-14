@extends('layouts.admin')
@section('style')
@endsection
@section('content')
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <h4 class="mb-0 font-size-18">Issue</h4>

        <a href="{{ route('admin.diamonds.index') }}" class="btn btn-sm btn-primary">
          <i class="mdi mdi-arrow-right-bold-circle-outline me-1"></i> Diamonds List
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

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="mdi mdi-check-all me-2"></i>
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="mdi mdi-block-helper me-2"></i>
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form action="{{ route('admin.issue.index') }}" method="get">
          @csrf

          <div data-repeater-list="group-a">
            <div data-repeater-item class="row">
              <div class="mb-3 col-lg-2">
                <label for="designation">Designation</label>
                <select name="designation" id="designation" class="form-select" required>
                  <option value="">Select designation</option>
                  @foreach($designations as $designation)
                  <option value="{{$designation->id}}" {{ request()->designation == $designation->id ? 'selected' : '' }}>{{$designation->name}}</option>
                  @endforeach
                </select>
                @if($errors->has('designation'))
                <div class="error text-danger">{{ $errors->first('designation') }}</div>
                @endif
              </div>

              <div class="mb-3 col-lg-2">
                <label for="worker_id">Worker</label>
                <select name="worker_id" id="worker_id" class="form-select" required>
                  @if(request('worker_id'))
                  @foreach($workers as $worker)
                  <option value="{{$worker->id}}" {{ request()->worker_id == $worker->id ? 'selected' : '' }}>{{$worker->fname}}</option>
                  @endforeach
                  @else
                  <option value="">Select worker</option>
                  @endif
                </select>
                @if($errors->has('worker_id'))
                <div class="error text-danger">{{ $errors->first('worker_id') }}</div>
                @endif
              </div>

              <div class="mb-3 col-lg-2">
                <label for="kapans_id">Kapan</label>
                <select name="kapans_id" id="kapans_id" class="form-select" required>
                  <option value="">Select Kapan</option>
                  @foreach($kapans as $kapan)
                  <option value="{{$kapan->id}}" {{ request()->kapans_id == $kapan->id ? 'selected' : '' }}>{{$kapan->kapan_name}}</option>
                  @endforeach
                </select>
                @if ($errors->has('kapans_id'))
                <div class="error text-danger">{{ $errors->first('kapans_id') }}</div>
                @endif
              </div>

              <div class="mb-3 col-lg-3">
                <label for="kapan_parts_id">Kapan Part</label>
                <select name="kapan_parts_id" id="kapan_parts_id" class="form-select">
                  @if(request('kapans_id'))
                  <option value="">ALL</option>
                  @foreach($kapan_parts as $kapanPa)
                  <option value="{{$kapanPa->id}}" {{ request()->kapan_parts_id == $kapanPa->id ? 'selected' : '' }}>{{$kapanPa->name}}</option>
                  @endforeach
                  @else
                  <option value="">Select Kapan Part</option>
                  @endif
                </select>
                @if($errors->has('kapan_parts_id'))
                <div class="error text-danger">{{ $errors->first('kapan_parts_id') }}</div>
                @endif
              </div>

              <div class="mb-3 col-lg-4">
                <button type="submit" class="btn btn-success mt-2 w-md">search</button>
                <a class="btn btn-light mt-2 w-md" href="/admin/issue">Clear</a>
              </div>

            </div>

          </div>
        </form>
      </div>
    </div>

    @if(count($diamonds) > 0)
    <div class="card">
      <div class="card-body">

        @if ($errors->has('kapan_parts_id'))
        <div class="alert alert-danger">
          {{ $errors->first('kapan_parts_id') }}
        </div>
        @endif

        <form method="POST" id="storeIssuesKapan" action="{{ route('admin.issue.store') }}">
          @csrf

          <input type="hidden" name="designation_id" value="{{ request()->designation }}">
          <input type="hidden" name="worker_id" value="{{ request()->worker_id }}">
          <input type="hidden" name="kapans_id" value="{{ request()->kapans_id }}">

          <button type="submit" class="btn btn-success">
            Issue Selected Diamonds
          </button>

          <table class="table table-bordered mt-3">
            <thead>
              <tr>
                <th>
                  <input type="checkbox" id="checkAll">
                </th>
                <th>Name</th>
                <th>Barcode</th>
                <th>Weight</th>
                <th>Pridiction Weight</th>
                <th>Shape</th>
              </tr>
            </thead>
            <tbody>
              @foreach($diamonds as $diamond)
              <tr>
                <td>
                  <input type="checkbox"
                    class="partCheckbox"
                    name="diamonds_id[]"
                    value="{{ $diamond->id }}">
                </td>
                <td>{{ $diamond->diamond_name }}</td>
                <td>{{ $diamond->barcode_number }}</td>
                <td>{{ $diamond->weight }}</td>
                <td>{{ $diamond->prediction_weight }}</td>
                <td>{{ $diamond->shapes->shape_type }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>

        </form>
      </div>
    </div>
    @elseif(request()->designation != '')
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

    $('#kapans_id').change(function() {

      var kapan = $(this).val();
      $('#kapan_parts_id').empty();

      if (!kapan || kapan === 'all') {
        $('#kapan_parts_id').append('<option value="">Select kapan part</option>');
        $('#diamondTable tbody').html('');
        $('#addDiamondBtn').addClass('d-none');
        return;
      }

      $.ajax({
        type: 'POST',
        url: '/admin/get-kapan-parts',
        data: {
          _token: '{{ csrf_token() }}',
          kapans_id: kapan
        },
        success: function(data) {

          $('#kapan_parts_id').append('<option value="">Select kapan part</option>');

          $.each(data, function(index, value) {
            if (value) {
              $('#kapan_parts_id').append(
                '<option value="' + value.id + '">' +
                value.name +
                '</option>'
              );
            }
          });
        }
      });
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
      alert('Please select at least one diamond to issue');
      e.preventDefault();
    }
  });
</script>
@endsection