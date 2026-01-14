@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <h4 class="mb-0 font-size-18">Return</h4>

        <a href="{{ route('admin.diamonds.index') }}" class="btn btn-sm btn-primary">
          <i class="mdi mdi-arrow-right-bold-circle-outline me-1"></i> Diamonds List
        </a>

        <a href="{{ route('admin.issue.index') }}" class="btn btn-sm btn-success">
          <i class="mdi mdi-arrow-left-bold-circle-outline me-1"></i> Issue
        </a>

        <a href="{{ route('admin.return.index') }}" class="btn btn-sm btn-danger">
          <i class="mdi mdi-arrow-left-bold-circle-outline me-1"></i> Return
        </a>
      </div>
    </div>
  </div>
</div>

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

        <form action="{{ route('admin.return.index') }}" method="get">
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
                <select name="kapans_id" id="kapans_id" class="form-select">
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

              <div class="mb-3 col-lg-2">
                <button class="btn btn-primary">Search</button>
                <!-- <a href="{{ route('admin.return.index') }}" class="btn btn-light">Clear</a> -->
              </div>

            </div>

          </div>
        </form>
      </div>
    </div>

    @if(count($issues) > 0)
    <div class="card">
      <div class="card-body">

        @if($issues->count())
        <table id="datatable" class="table table-bordered">
          <thead>
            <tr>
              <th>Diamond Name</th>
              <!-- <th>Barcode</th> -->
              <!-- <th>Issue Weight</th> -->
              @if(request('designation') != '3')
              <th>Return Weight</th>
              <th>Return Date</th>
              @endif

              @if(request('designation') == '3')
              <th>Button</th>
              <!-- <th>Certi No.</th>
              <th>Color</th>
              <th>Shape</th>
              <th>Cut</th> -->
              @endif

              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            @foreach($issues as $issue)
            <tr>
              <form method="POST" action="{{ route('admin.return.store') }}">
                @csrf

                <input type="hidden" name="issue_id" value="{{ $issue->id }}">

                <td>{{ $issue->diamond->diamond_name }}</td>
                <!-- <td>{{ $issue->diamond->barcode_number }}</td> -->
                <!-- <td>{{ $issue->issue_weight }}</td> -->

                @if(request('designation') != '3')
                <td>
                  <input type="number"
                    step="0.01"
                    name="return_weight"
                    class="form-control return-weight"
                    value=""
                    max="{{ $issue->issue_weight }}"
                    data-issue-weight="{{ $issue->issue_weight }}"
                    placeholder="returm weight"
                    required>
                </td>

                <td>
                  <input type="date"
                    name="return_date"
                    class="form-control return-date"
                    value="{{ now()->toDateString() }}"
                    min="{{ \Carbon\Carbon::parse($issue->issue_date)->toDateString() }}"
                    data-issue-date="{{ \Carbon\Carbon::parse($issue->issue_date)->toDateString() }}"
                    required>
                </td>
                @endif

                @if(request('designation') == '3')
                <td>
                  <button type="button"
                    class="btn btn-sm btn-info certificateBtn"
                    data-bs-toggle="modal"
                    data-bs-target="#certificateModal"
                    data-id="{{ $issue->id }}"
                    data-diamonds_id="{{ $issue->diamonds_id }}">
                    Add Detail
                  </button>
                </td>
                <!-- <td>
                  <input type="text" name="certi_no" id="certi_no" class="form-control" placeholder="Enter certi no" required>
                </td>
                <td>
                  <select name="r_shape" id="r_shape" class="form-select" required>
                    @foreach($shapes as $shape)
                    <option value="{{$shape->id}}">{{$shape->shape_type}}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <select name="r_color" id="r_color" class="form-select" required>
                    @foreach($colors as $color)
                    <option value="{{$color->id}}">{{$color->c_name}}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <select name="r_clarity" id="r_clarity" class="form-select" required>
                    @foreach($clarity as $clar)
                    <option value="{{$clar->id}}">{{$clar->name}}</option>
                    @endforeach
                  </select>
                </td> -->
                @endif

                <td>
                  <button class="btn btn-success btn-sm">
                    Save
                  </button>
                </td>
              </form>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif

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


<div class="modal fade" id="certificateModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.return.store') }}">
      @csrf

      <input type="hidden" name="issue_id" id="issue_id">
      <input type="hidden" name="diamonds_id" id="diamonds_id">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">return from certificate Diamond</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="row">

            <div class="col-md-4 mb-2">
              <label>Certificate No</label>
              <input type="text" name="certi_no" id="certi_no" class="form-control" placeholder="Enter certi no" required>
            </div>

            <div class="col-md-4 mb-2">
              <label>Availability</label>
              <input type="text" name="availability" id="availability" class="form-control" placeholder="Enter ceavailability" required>
            </div>

            <div class="col-md-4 mb-2">
              <label>Return Weight</label>
              <input type="number"
                step="0.01"
                name="return_weight"
                class="form-control return-weight"
                value=""
                max="{{ $issue->issue_weight }}"
                data-issue-weight="{{ $issue->issue_weight }}"
                placeholder="returm weight"
                required>
            </div>

            <div class="col-md-4 mb-2">
              <label>Return Date</label>
              <input type="date"
                name="return_date"
                class="form-control return-date"
                value="{{ now()->toDateString() }}"
                min="{{ \Carbon\Carbon::parse($issue->issue_date)->toDateString() }}"
                data-issue-date="{{ \Carbon\Carbon::parse($issue->issue_date)->toDateString() }}"
                required>
            </div>

            <div class="col-md-4 mb-2">
              <label>Shape</label>
              <select name="r_shape" id="r_shape" class="form-select" required>
                @foreach($shapes as $shape)
                <option value="{{$shape->id}}">{{$shape->shape_type}}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-2">
              <label>Color</label>
              <select name="r_color" id="r_color" class="form-select" required>
                @foreach($colors as $color)
                <option value="{{$color->id}}">{{$color->c_name}}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-2">
              <label>Clarity</label>
              <select name="r_clarity" id="r_clarity" class="form-select" required>
                @foreach($clarity as $clar)
                <option value="{{$clar->id}}">{{$clar->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-2">
              <label>Price</label>
              <input type="number" step="0.01" name="price" class="form-control" required>
            </div>

            <div class="col-md-4 mb-2">
              <label>Total price</label>
              <input type="number" step="0.01" name="total_price" class="form-control" required>
            </div>

            <div class="col-md-4 mb-2">
              <label>Image Link</label>
              <input type="text" name="image_link" class="form-control" required>
            </div>

            <div class="col-md-4 mb-2">
              <label>video Link</label>
              <input type="text" name="video_link" class="form-control" required>
            </div>

            <div class="col-md-4 mb-2">
              <label>Polish</label>
              <select name="r_clarity" id="r_clarity" class="form-select" required>
                @foreach($clarity as $clar)
                <option value="{{$clar->id}}">{{$clar->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-2">
              <label>Symmetry</label>
              <select name="r_clarity" id="r_clarity" class="form-select" required>
                @foreach($clarity as $clar)
                <option value="{{$clar->id}}">{{$clar->name}}</option>
                @endforeach
              </select>
            </div>

          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Submit</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>

      </div>
    </form>
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


  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.certificateBtn').forEach(button => {
      button.addEventListener('click', function() {
        document.getElementById('issue_id').value = this.dataset.id;
        document.getElementById('diamonds_id').value = this.dataset.diamonds_id;
        document.getElementById('dName').innerHTML = this.dataset.name;
      });
    });
  });

  document.addEventListener('input', function(e) {
    if (e.target.classList.contains('return-weight')) {

      let issueWeight = parseFloat(e.target.dataset.issueWeight);
      let returnWeight = parseFloat(e.target.value);

      if (returnWeight > issueWeight) {
        alert('Return weight cannot be greater than Issue weight');
        e.target.value = issueWeight;
      }
    }
  });

  document.addEventListener('change', function(e) {
    if (e.target.classList.contains('return-date')) {

      let issueDate = new Date(e.target.dataset.issueDate);
      let returnDate = new Date(e.target.value);

      if (returnDate < issueDate) {
        alert('Return date cannot be earlier than Issue date');
        e.target.value = e.target.dataset.issueDate;
      }
    }
  });
</script>
@endsection