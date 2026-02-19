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

        @include('includes.flash_message')

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
                <button class="btn btn-primary">Submit</button>
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
              <th>Shape</th>
              <th>P Weight</th>
              @if(request('designation') != '3')
              <th>Return Weight</th>
              <th>Return Date</th>
              <th>Action</th>
              @else
              <th>Button</th>
              @endif
            </tr>
          </thead>

          <tbody>
            @foreach($issues as $issue)
            <tr>
              <form method="POST" action="{{ route('admin.return.store') }}">
                @csrf

                <input type="hidden" name="issue_id" value="{{ $issue->id }}">

                <td class="d-flex justify-content-between align-items-center">

                  <span class="diamond-name">
                    {{ $issue->diamond->diamond_name }}
                  </span>

                  <button type="button"
                    class="btn btn-sm btn-light editDiamondBtn ms-2"
                    data-id="{{ $issue->diamond->id }}"
                    data-name="{{ $issue->diamond->diamond_name }}" title="Update Name">
                    <i class="fas fa-marker text-primary"></i>
                  </button>

                </td>

                <td>{{ $issue->diamond->shapes->shape_type }}</td>
                <td>{{ $issue->diamond->prediction_weight }}</td>

                @if(request('designation') != '3')
                <td>
                  <input type="number"
                    step="0.01"
                    name="return_weight"
                    class="form-control return-weight"
                    value=""
                    max="{{ $issue->diamond->prediction_weight }}"
                    data-issue-weight="{{ $issue->diamond->prediction_weight }}"
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
                <td>
                  <button class="btn btn-success btn-sm">
                    Save
                  </button>
                </td>
                @else
                <td>
                  <button type="button"
                    class="btn btn-sm btn-info certificateBtn"
                    data-bs-toggle="modal"
                    data-bs-target="#certificateModal"
                    data-id="{{ $issue->id }}"
                    data-diamonds_id="{{ $issue->diamonds_id }}"
                    data-prediction="{{ $issue->diamond->prediction_weight }}">
                    Add & save
                  </button>
                </td>
                @endif
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


<div class="modal fade" id="diamondNameModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Update Diamond Name</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="diamond_id">

        <label>Diamond Name</label>
        <input type="text" id="diamond_name" class="form-control">

        <div class="text-danger mt-2" id="nameError"></div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" id="updateDiamondName">
          Update
        </button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="certificateModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('admin.return.store') }}">
      @csrf

      <input type="hidden" name="issue_id" id="issue_id">
      <input type="hidden" name="diamonds_id" id="diamonds_id">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Return from certificate Diamond</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="row">

            <div class="col-md-12 mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_certified" name="is_non_certi" value="1" checked>
                <label class="form-check-label fw-bold" for="is_certified">
                  Certified Diamond
                </label>
              </div>
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>Certificate #</label>
              <input type="text" name="certi_no" id="certi_no" class="form-control" placeholder="Enter certi no" required>
            </div>

            <div class="col-md-8 mb-2 certi-field">
              <label>Certificate Url</label>
              <input type="text" name="Certificate_url" class="form-control" placeholder="Enter Certificate Url" required>
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>Availability</label>
              <input type="text" name="availability" id="availability" class="form-control" placeholder="Enter ailability" value="GA" required>
            </div>

            <div class="col-md-4 mb-2 non-certi-field">
              <label>Return Weight</label>
              <input type="number"
                step="0.01"
                name="return_weight"
                class="form-control return-weight"
                id="return_weight"
                value=""
                max=""
                data-prediction=""
                data-issue-weight=""
                placeholder="returm weight"
                required>
              <small id="weightMessage"></small>
            </div>


            <div class="col-md-4 mb-2 non-certi-field">
              <label>Return Date</label>
              <input type="date"
                name="return_date"
                class="form-control return-date"
                value="{{ now()->toDateString() }}"
                min=""
                data-issue-date=""
                required>
            </div>

            <div class="col-md-4 mb-2 non-certi-field">
              <label>Shape</label>
              <select name="r_shape" id="r_shape" class="form-select" required>
                @foreach($shapes as $shape)
                <option value="{{$shape->shape_type}}">{{$shape->shape_type}}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-2 non-certi-field">
              <label>Color</label>
              <select name="r_color" id="r_color" class="form-select" required>
                @foreach($colors as $color)
                <option value="{{$color->c_name}}">{{$color->c_name}}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-2 non-certi-field">
              <label>Clarity</label>
              <select name="r_clarity" id="r_clarity" class="form-select" required>
                @foreach($clarity as $clar)
                <option value="{{$clar->name}}">{{$clar->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>Polish</label>
              <select name="r_polish" id="r_polish" class="form-select" required>
                @foreach($polish as $pol)
                <option value="{{$pol->name}}">{{$pol->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>Symmetry</label>
              <select name="r_symmetry" id="r_symmetry" class="form-select" required>
                @foreach($symmetry as $symme)
                <option value="{{$symme->name}}">{{$symme->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 mb-2 non-certi-field">
              <label>Price</label>
              <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="Enter Price">
            </div>

            <div class="col-md-4 mb-2 non-certi-field">
              <label>Discount</label>
              <input type="number" step="0.01" name="discount" id="discount" class="form-control" placeholder="Enter Discount in %">
            </div>

            <div class="col-md-4 mb-2 non-certi-field">
              <label>Total price</label>
              <input type="number" step="0.01" name="total_price" id="total_price" class="form-control" placeholder="Enter total price">
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>Image Link</label>
              <input type="text" name="image_link" class="form-control" placeholder="Enter image link">
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>video Link</label>
              <input type="text" name="video_link" class="form-control" placeholder="Enter video link">
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>Depth Percent</label>
              <input type="number" step="0.01" name="depth_percent" class="form-control" placeholder="Enter depth percent">
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>Table Percent</label>
              <input type="number" step="0.01" name="table_percent" class="form-control" placeholder="Enter table percent">
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>Fluorescence Intensity</label>
              <input type="text" name="fluorescence_intensity" class="form-control" value="NONE" placeholder="Enter fluorescence intensity">
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>Lab</label>
              <input type="text" name="lab" class="form-control" placeholder="Enter Lab" value="IGI">
            </div>

            <div class="col-md-4 mb-2 non-certi-field">
              <label>Measurements</label>
              <input type="text" name="measurements" class="form-control" placeholder="Enter measurements">
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>BGM</label>
              <input type="text" name="bgm" class="form-control" value="NONE" placeholder="None">
            </div>

            <div class="col-md-4 mb-2 non-certi-field">
              <label>Fancy Color</label>
              <input type="text" name="fancy_color" class="form-control" placeholder="Enter Fancy Color">
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>Fancy Color Intensity</label>
              <input type="text" name="fancy_color_intensity" class="form-control" placeholder="Enter Fancy Color Intencity">
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>Cut Grade</label>
              <select name="cut_grade" id="cut_grade" class="form-select">
                <option value="">NONE</option>
                <option value="IDEAL">IDEAL</option>
                <option value="EX">EX</option>
                <option value="VG">VG</option>
              </select>
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>H&A</label>
              <select name="h_and_a" id="h_and_a" class="form-select">
                <option value="No">No</option>
                <option value="Yes">Yes</option>
              </select>
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>City</label>
              <select name="city" id="city" class="form-select" required>
                <option value="SURAT">SURAT</option>
              </select>
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>State</label>
              <select name="state" id="state" class="form-select" required>
                <option value="GUJARAT">GUJARAT</option>
              </select>
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>Country</label>
              <select name="country" id="country" class="form-select">
                <option value="India">India</option>
              </select>
            </div>


            <div class="col-md-4 mb-2 certi-field">
              <label>Eye Clean</label>
              <select name="eye_clean" id="eye_clean" class="form-select">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>

            <div class="col-md-4 mb-2 certi-field">
              <label>Growth Type</label>
              <input type="text" name="growth_type" class="form-control" value="CVD" placeholder="CVD">
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


  // document.addEventListener('DOMContentLoaded', function() {
  //   document.querySelectorAll('.certificateBtn').forEach(button => {
  //     button.addEventListener('click', function() {
  //       document.getElementById('issue_id').value = this.dataset.id;
  //       document.getElementById('diamonds_id').value = this.dataset.diamonds_id;
  //     });
  //   });
  // });

  document.addEventListener('click', function(e) {
    let btn = e.target.closest('.certificateBtn');
    if (btn) {
      document.getElementById('issue_id').value = btn.dataset.id;
      document.getElementById('diamonds_id').value = btn.dataset.diamonds_id;

      let prediction = parseFloat(btn.dataset.prediction);

      let input = document.getElementById('return_weight');
      input.dataset.prediction = prediction;
      input.value = '';

      document.getElementById('weightMessage').innerHTML =
        "Predicted Weight: " + prediction;

    }
  });

  $('#return_weight, #price').on('input', function() {
    let weight = parseFloat($('#return_weight').val()) || 0;
    let price = parseFloat($('#price').val()) || 0;

    console.log(weight)
    console.log(price)

    $('#total_price').val((weight * price).toFixed(2));
  });

  <?php /*
  document.addEventListener('input', function(e) {
    if (e.target.classList.contains('return-weight')) {

      let issueWeight = parseFloat(e.target.dataset.issueWeight);
      let returnWeight = parseFloat(e.target.value);

      if (returnWeight > issueWeight) {
        alert('Return weight cannot be greater than diamond weight');
        e.target.value = issueWeight;
      }
    }
  });
  */ ?>

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

  document.getElementById('return_weight').addEventListener('input', function() {

    let prediction = parseFloat(this.dataset.prediction);
    let returnWeight = parseFloat(this.value);

    if (isNaN(returnWeight)) {
      document.getElementById('weightMessage').innerHTML = '';
      return;
    }

    let difference = (prediction - returnWeight).toFixed(2);

    if (difference > 0) {
      document.getElementById('weightMessage').innerHTML =
        "<span class='text-warning'>Diffrence is : " + difference + "</span>";
    } else if (difference < 0) {
      document.getElementById('weightMessage').innerHTML =
        "<span class='text-danger'>Diffrence is : " + Math.abs(difference) + "</span>";
    } else {
      document.getElementById('weightMessage').innerHTML =
        "<span class='text-success'>Exact Match</span>";
    }
  });
</script>
<script>
  document.getElementById('certi_no').addEventListener('input', function() {
    let certiNo = this.value.trim();

    let baseUrl = "https://api.igi.org/viewpdf.php?r=";

    document.querySelector('[name="Certificate_url"]').value = certiNo ?
      baseUrl + certiNo :
      '';
  });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {

    const checkbox = document.getElementById('is_certified');

    // Store original required
    document.querySelectorAll('#certificateModal input, #certificateModal select')
      .forEach(el => {
        if (el.required) {
          el.dataset.originalRequired = "true";
        }
      });

    function toggleFields() {

      const certiFields = document.querySelectorAll('.certi-field');

      certiFields.forEach(field => {

        if (checkbox.checked) {

          field.style.display = '';

          field.querySelectorAll('input, select').forEach(el => {
            if (el.dataset.originalRequired === "true") {
              el.required = true;
            }
          });

        } else {

          field.style.display = 'none';

          field.querySelectorAll('input, select').forEach(el => {
            el.required = false;
          });
        }

      });
    }

    checkbox.addEventListener('change', toggleFields);
    toggleFields();
  });
</script>
<script>
  document.getElementById('price').addEventListener('input', calculateTotal);
  document.getElementById('discount').addEventListener('input', calculateTotal);
  document.getElementById('return_weight').addEventListener('input', calculateTotal);

  function calculateTotal() {
    let price = parseFloat(document.getElementById('price').value) || 0;
    let discount = parseFloat(document.getElementById('discount').value) || 0;
    let weight = parseFloat($('#return_weight').val()) || 0;

    let total = price - (price * discount / 100);

    document.getElementById('total_price').value = (weight * total.toFixed(2));
  }
</script>

<script>
  // open modal
  $(document).on('click', '.editDiamondBtn', function() {
    $('#diamond_id').val($(this).data('id'));
    $('#diamond_name').val($(this).data('name'));
    $('#nameError').text('');
    $('#diamondNameModal').modal('show');
  });

  // update
  $('#updateDiamondName').click(function() {

    $.ajax({
      url: "{{ route('admin.diamond.update.name') }}",
      type: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        diamond_id: $('#diamond_id').val(),
        diamond_name: $('#diamond_name').val()
      },
      success: function(res) {

        if (!res.status) {
          $('#nameError').text(res.message);
          return;
        }

        $('#diamondNameModal').modal('hide');
        location.reload(); // or update row dynamically
      }
    });

  });
</script>
@endsection