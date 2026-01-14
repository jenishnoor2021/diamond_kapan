@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">Return</h4>
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

        <!-- <form action="{{ route('admin.return.index') }}" method="get"> -->
        <!-- @csrf -->

        <div data-repeater-list="group-a">
          <div data-repeater-item class="row">
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
              <label for="kapan_part_id">Kapan Part</label>
              <select name="kapan_part_id" id="kapan_part_id" class="form-select" required>
                <option value="">Select kapan part</option>
              </select>
              @if($errors->has('kapan_part_id'))
              <div class="error text-danger">{{ $errors->first('kapan_part_id') }}</div>
              @endif
            </div>

            <div class="mb-3 col-lg-2">
              <a class="btn btn-light mt-2 w-md" href="/admin/return">Clear</a>
            </div>

          </div>

        </div>
        <!-- </form> -->
      </div>
    </div>

    <div class="card">
      <div class="card-body">

        <table class="table table-bordered mt-3" id="diamondTable">
          <thead>
            <tr>
              <th>Name</th>
              <th>Weight</th>
              <th>Prediction Weight</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

        <button class="btn btn-primary d-none" id="addDiamondBtn">Add</button>

      </div>
    </div>

  </div>
</div>
<!-- end row -->
@endsection

@section('script')
<script>
  $(document).ready(function() {
    $('#kapans_id').change(function() {

      var kapan = $(this).val();
      $('#kapan_part_id').empty();

      if (!kapan || kapan === 'all') {
        $('#kapan_part_id').append('<option value="">Select kapan part</option>');
        $('#diamondTable tbody').html('');
        $('#addDiamondBtn').addClass('d-none');
        return;
      }

      $.ajax({
        type: 'POST',
        url: '/admin/get-issued-kapan-parts',
        data: {
          _token: '{{ csrf_token() }}',
          kapans_id: kapan
        },
        success: function(data) {

          $('#kapan_part_id').append('<option value="">Select kapan part</option>');

          $.each(data, function(index, value) {
            if (value.kapan_part) {
              $('#kapan_part_id').append(
                '<option value="' + value.kapan_part.id + '">' +
                value.kapan_part.name +
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
  $('#kapan_part_id').change(function() {

    let kapan_id = $('#kapans_id').val();
    let kapan_part_id = $(this).val();

    if (!kapan_id || !kapan_part_id) return;

    $.post('/admin/get-diamond-by-kapan', {
      _token: '{{ csrf_token() }}',
      kapan_id: kapan_id,
      kapan_part_id: kapan_part_id
    }, function(data) {

      let tbody = '';
      $('#diamondTable tbody').html('');

      data.forEach(function(item) {
        tbody += `
        <tr>
            <td>${item.diamond_name}</td>
            <td>
                <input type="number" step="0.01"
                    class="form-control weight"
                    data-id="${item.id}"
                    value="${item.weight}">
            </td>
            <td>
                <input type="number" step="0.01"
                    class="form-control prediction_weight"
                    data-id="${item.id}"
                    value="${item.prediction_weight}">
            </td>
        </tr>
    `;
      });

      $('#diamondTable tbody').html(tbody);

      // 🔹 If 1 entries exist → show ADD button
      if (data.length >= 1) {
        $('#addDiamondBtn').removeClass('d-none');
      } else {
        $('#addDiamondBtn').addClass('d-none');
      }
    });
  });

  $('#addDiamondBtn').click(function() {

    let newRow = `
        <tr class="new-diamond-row">
            <td>
                <input type="text" class="form-control" id="new_diamond_name" placeholder="Name">
            </td>
            <td>
                <input type="number" step="0.01" class="form-control" id="new_weight" placeholder="Weight">
            </td>
            <td>
                <input type="number" step="0.01" class="form-control" id="new_prediction_weight" placeholder="Prediction">
            </td>
            <td>
                <button class="btn btn-success btn-sm" id="saveNewDiamond">Save</button>
                <button class="btn btn-secondary btn-sm" id="cancelNewDiamond">Cancel</button>
            </td>
        </tr>
    `;

    // prevent multiple add rows
    if ($('#diamondTable tbody .new-diamond-row').length === 0) {
      $('#diamondTable tbody').append(newRow);
    }
  });
</script>
<script>
  $('#addDiamondBtn').click(function() {
    $('#diamondForm').removeClass('d-none');
  });

  $(document).on('click', '#saveNewDiamond', function() {

    $.post('/admin/save-diamond', {
      _token: '{{ csrf_token() }}',
      kapan_id: $('#kapans_id').val(),
      kapan_part_id: $('#kapan_part_id').val(),
      diamond_name: $('#new_diamond_name').val(),
      weight: $('#new_weight').val(),
      prediction_weight: $('#new_prediction_weight').val()
    }, function() {
      $('#kapan_part_id').trigger('change'); // reload table
    });
  });

  $(document).on('click', '#cancelNewDiamond', function() {
    $('.new-diamond-row').remove();
  });
</script>
<script>
  $(document).on('change', '.weight, .prediction_weight', function() {

    let id = $(this).data('id');
    let weight = $(this).closest('tr').find('.weight').val();
    let prediction_weight = $(this).closest('tr').find('.prediction_weight').val();

    $.post('/admin/update-diamond', {
      _token: '{{ csrf_token() }}',
      id: id,
      weight: weight,
      prediction_weight: prediction_weight
    });
  });
</script>
@endsection