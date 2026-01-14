@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">Sub Division</h4>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        @include('includes.flash_message')

        <form action="{{ route('admin.sub-division.index') }}" method="get">
          @csrf

          <div data-repeater-list="group-a">
            <div data-repeater-item class="row">
              <div class="col-lg-2">
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

              <div class="col-lg-3">
                <label for="kapan_parts_id">Kapan Part</label>
                <select name="kapan_parts_id" id="kapan_parts_id" class="form-select" required>
                  @if(request('kapan_parts_id'))
                  @foreach($kapan_parts as $kapanPa)
                  <option value="{{$kapanPa->id}}" {{ request()->kapan_parts_id == $kapanPa->id ? 'selected' : '' }}>
                    {{$kapanPa->name}}
                    @if($kapanPa->diamond_count > 0)
                    &nbsp;&nbsp;&nbsp;(<span class="text-success">{{$kapanPa->diamond_count}}</span> 💎)
                    @else
                    &nbsp;&nbsp;&nbsp;(0 💎)
                    @endif
                  </option>
                  @endforeach
                  @else
                  <option value="">Select Kapan Part</option>
                  @endif
                </select>
                @if($errors->has('kapan_parts_id'))
                <div class="error text-danger">{{ $errors->first('kapan_parts_id') }}</div>
                @endif
              </div>

              <div class="col-lg-2">
                <button class="btn btn-primary">Submit</button>
                <a href="{{ route('admin.sub-division.index') }}" class="btn btn-light">Clear</a>
              </div>

            </div>

          </div>
        </form>

        @if(request('kapans_id') && request('kapan_parts_id') && $kapanPart)
        <div class="card mb-3">
          <div class="card-body">
            <div class="row text-center">

              <div class="col-md-2">
                <h6 class="text-muted mb-1">Kapan Name</h6>
                <h5 class="fw-bold">
                  {{ $kapans->where('id', request('kapans_id'))->first()->kapan_name ?? '-' }}
                </h5>
              </div>

              <div class="col-md-2">
                <h6 class="text-muted mb-1">Kapan Part Name</h6>
                <h5 class="fw-bold">
                  {{ $kapanPart ? $kapanPart->name : '-' }}
                </h5>
              </div>

              <div class="col-md-2">
                <h6 class="text-muted mb-1">Row weight</h6>
                <h5 class="fw-bold text-primary">
                  {{ number_format($kapanPart->weight, 2) }}
                </h5>
              </div>

              <!-- used Weight -->
              <!-- <div class="col-md-2">
                <h6 class="text-muted mb-1">Used Weight</h6>
                <h5 class="fw-bold text-success">
                  {{ number_format($usedWeight, 2) }}
                </h5>
              </div> -->

              <!-- addition of pridiction weight -->
              <div class="col-md-2">
                <h6 class="text-muted mb-1">Plan Weight</h6>
                <h5 class="fw-bold text-success">
                  {{ number_format($totalPWeight, 2) }}
                </h5>
              </div>

              <!-- total pridiction weight - row weight -->
              <div class="col-md-2">
                <h6 class="text-muted mb-1">Loss Weight</h6>
                @php
                $rawWeight = $kapanPart->weight ?? 0;
                $lossWeight = max($rawWeight - $totalPWeight, 0);
                @endphp
                <h5 class="fw-bold text-danger">
                  {{ number_format($lossWeight, 2) }}
                </h5>
              </div>

              <div class="col-md-2">
                <h6 class="text-muted mb-1">Planing %</h6>
                @php
                $lossPer = $rawWeight > 0
                ? ($totalPWeight * 100) / $rawWeight
                : 0;
                @endphp
                <h5 class="fw-bold text-warning">
                  {{ number_format($lossPer, 2) }} %
                </h5>
              </div>

            </div>
          </div>
        </div>
        @endif

      </div>
    </div>

    @if(request('kapans_id'))
    <div class="card">
      <div class="card-body">

        <table class="table table-bordered mt-3">
          <thead>
            <tr>
              <th>Name</th>
              <!-- <th>Weight</th> -->
              <th>Prediction weight</th>
              <th>Shap</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            @foreach($diamonds as $diamond)
            <tr>
              @if(request('edit') == $diamond->id)

              <form method="POST" action="{{ route('admin.diamond.update', $diamond->id) }}">
                @csrf

                <input type="hidden" name="kapans_id" value="{{ request('kapans_id') }}">
                <input type="hidden" name="kapan_parts_id" value="{{ request('kapan_parts_id') }}">

                <td>
                  <input type="text" name="diamond_name" value="{{ $diamond->diamond_name }}" placeholder="Enter name" class="form-control">
                </td>
                <!-- <td>
                  <input type="number" step="0.01" name="weight" value="{{ $diamond->weight }}" placeholder="Enter weight" class="form-control">
                </td> -->
                <td>
                  <input type="number" step="0.01" name="prediction_weight" placeholder="Enter prediction weight" value="{{ $diamond->prediction_weight }}" class="form-control">
                </td>
                <td>
                  <select name="shape" id="shape" class="form-select" required>
                    @foreach($shapes as $shape)
                    <option value="{{$shape->id}}" {{ $shape->id == $diamond->shape ? 'selected' : '' }}>{{$shape->shape_type}}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <button class="btn btn-success btn-sm">Save</button>
                  <a href="{{ route('admin.sub-division.index', request()->except('edit')) }}"
                    class="btn btn-secondary btn-sm">Cancel</a>
                </td>
              </form>

              @else
              <td>{{ $diamond->diamond_name }}</td>
              <!-- <td>{{ $diamond->weight }}</td> -->
              <td>{{ $diamond->prediction_weight }}</td>
              <td>{{ $diamond->shapes->shape_type }}</td>
              <td>
                <a href="{{ route('admin.sub-division.index', array_merge(request()->all(), ['edit' => $diamond->id])) }}"
                  class="btn btn-warning btn-sm">Edit</a>

                <form method="POST" action="{{ route('admin.diamond.delete', $diamond->id) }}"
                  class="d-inline">
                  @csrf

                  <input type="hidden" name="kapans_id" value="{{ request('kapans_id') }}">
                  <input type="hidden" name="kapan_parts_id" value="{{ request('kapan_parts_id') }}">

                  <button class="btn btn-danger btn-sm"
                    onclick="return confirm('Delete this diamond?')">Delete</button>
                </form>
              </td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>

        @if(request('kapans_id') && request('kapan_parts_id') && !request('edit'))
        <form method="POST" action="{{ route('admin.diamond.store') }}" id="addDiamondForm"
          data-total-weight="{{ $kapanPart->weight }}">
          @csrf
          <input type="hidden" name="kapans_id" value="{{ request('kapans_id') }}">
          <input type="hidden" name="kapan_parts_id" value="{{ request('kapan_parts_id') }}">

          <table class="table">
            <tbody>
              <tr>
                @php
                $diamondCount = $diamonds->count(); // existing diamonds
                $nextAlphabet = chr(65 + $diamondCount); // 65 = 'A'
                @endphp

                <td width="12%">
                  <input name="diamond_name" class="form-control" placeholder="Enter name" value="{{ $nextAlphabet }}" required>
                  <span class="text-danger" style="font-size:10px;">Just Enter alphbets</span>
                </td>
                <!-- <td width="12%"><input type="number" name="weight" step="0.01" class="form-control" placeholder="Enter weight"></td> -->
                <td width="12%"><input type="number" name="prediction_weight" step="0.01" class="form-control" placeholder="Enter prediction weight" required>
                </td>
                <td width="12%">
                  <select name="shape" id="shape" class="form-select" required>
                    @foreach($shapes as $shape)
                    <option value="{{$shape->id}}">{{$shape->shape_type}}</option>
                    @endforeach
                  </select>
                </td>
                @php $disabled = $remainingWeight <= 0; @endphp

                  <td width="15%">
                  <button
                    type="submit"
                    class="btn btn-primary btn-sm me-1"
                    {{ $disabled ? 'disabled' : '' }}>
                    Add
                  </button>

                  <button
                    type="button"
                    onclick="location.reload();"
                    class="btn btn-light btn-sm"
                    {{ $disabled ? 'disabled' : '' }}>
                    Clear
                  </button>
                  </td>
              </tr>
            </tbody>
          </table>
        </form>
        @endif

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

            const label = value.diamond_count > 0 ?
              `${value.name} &nbsp;&nbsp;&nbsp;(${value.diamond_count} 💎 )` :
              `${value.name} &nbsp;&nbsp;&nbsp;(0 💎)`;

            $('#kapan_parts_id').append(
              `<option value="${value.id}">
                ${label}
              </option>`
            );
          });
        }
      });
    });
  });
</script>

<script>
  $(document).ready(function() {

    $('#addDiamondForm').on('submit', function(e) {

      const totalWeight = parseFloat($(this).data('total-weight')) || 0;
      // const usedWeight = parseFloat($(this).data('used-weight')) || 0;

      // const weight = parseFloat($('input[name="weight"]').val()) || 0;
      const predictionWeight = parseFloat($('input[name="prediction_weight"]').val()) || 0;

      // Rule 1: Prediction <= Weight
      if (predictionWeight > totalWeight) {
        alert('Prediction weight cannot be greater than diamond weight.');
        e.preventDefault();
        return false;
      }

      // Rule 2: Used + New <= Kapan Part Weight
      // if ((usedWeight + weight) > totalWeight) {
      //   alert(
      //     'Total weight exceeds Kapan Part weight.\n\n' +
      //     'Remaining Weight: ' + (totalWeight - usedWeight).toFixed(2)
      //   );
      //   e.preventDefault();
      //   return false;
      // }
    });

  });
</script>
@endsection