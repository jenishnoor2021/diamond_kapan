@extends('layouts.admin')
@section('content')
<style>
    .table>:not(caption)>*>* {
        padding: .25rem .75rem;
    }
</style>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Kapan Parts List</h4>
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

                        {!! Form::open(['method'=>'get', 'action'=> 'AdminKapanPartsController@getKapanParts','class'=>'form-horizontal','name'=>'getKapanParts']) !!}
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="kapans_id" class="form-label">kapans</label>
                                    <select name="kapans_id" id="kapans_id" class="form-select" onchange="this.form.submit();" required>
                                        <option value="">Select Kapan</option>
                                        @foreach ($kapans as $kapan)
                                        <option value="{{ $kapan->id }}"
                                            {{ isset($selectedKapan) && $selectedKapan && $kapan->id == $selectedKapan->id ? 'selected' : '' }}>
                                            {{ $kapan->kapan_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('kapans_id'))
                                    <div class="error text-danger">{{ $errors->first('kapans_id') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex gap-2 mb-3">
                                    <!-- <button type="submit" class="btn btn-primary w-md">Submit</button> -->
                                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/kapan_part') }}">Clear</a>
                                </div>
                            </div>
                        </div>

                        </form>

                    </div>
                </div>

                @if(count($kapan_parts) > 0)

                @php
                $totalInserted = $kapan_parts->sum('weight');
                $remaining = $selectedKapan->kapan_weight - $totalInserted;
                @endphp

                <span class="text-danger" style="font-size:15px;">
                    "{{ $selectedKapan->kapan_name }}" kapan total weight is
                    <strong id="kapanWeight">{{ $selectedKapan->kapan_weight }}</strong>

                    | Inserted Weight:
                    <strong id="insertedWeight">{{ $totalInserted }}</strong>

                    | Remaining:
                    <strong id="remainingWeight"
                        class="{{ $remaining < 0 ? 'text-danger' : 'text-success' }}">
                        {{ $remaining }}
                    </strong>
                </span>

                @php
                $chunks = $kapan_parts->chunk( ceil($kapan_parts->count() / 2) );
                @endphp

                <div class="row mt-3">

                    @foreach($chunks as $chunk)
                    <div class="col-md-6">
                        <table class="table">
                            <thead>
                                <tr align="center">
                                    <th class="text-primary" style="font-weight:700">No</th>
                                    <th>Name</th>
                                    <th>Weight</th>
                                    <th width="20%"></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($chunk as $part)
                                <tr>
                                    <td class="text-primary" style="font-weight:700">{{ $part->part_no }}</td>

                                    <td>
                                        <input type="text"
                                            value="{{ $part->name }}"
                                            class="form-control"
                                            readonly>
                                    </td>

                                    <td>
                                        <input type="number"
                                            name="parts[{{ $part->id }}]"
                                            value="{{ $part->weight }}"
                                            step="0.01"
                                            class="form-control kapan-weight-input"
                                            data-id="{{ $part->id }}">
                                    </td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endforeach

                </div>

                @endif

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')
<script>
    $(document).on('change', '.toggle-status-switch', function() {
        const toggleSwitch = $(this);
        const agentId = toggleSwitch.data('id');
        const isChecked = toggleSwitch.is(':checked');

        $.ajax({
            url: "{{ route('admin.kapan.active') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: agentId
            },
            success: function(response) {
                if (response.success) {
                    toggleSwitch.next('label').text(response.status);
                    alert('Status updated successfully');
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                // Revert toggle state in case of an error
                toggleSwitch.prop('checked', !isChecked);
            }
        });
    });

    $(document).on('focus', '.kapan-weight-input', function() {
        $(this).data('old', $(this).val());
    });


    $(document).on('change', '.kapan-weight-input', function() {
        let input = $(this);
        let partId = input.data('id');
        let weight = parseFloat(input.val()) || 0;

        $.ajax({
            url: "{{ route('admin.kapan.parts.update.single') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                part_id: partId,
                weight: weight
            },
            success: function(res) {
                if (res.success) {

                    $('#insertedWeight').text(res.total_weight);

                    // Remaining = kapanWeight - inserted
                    let kapanWeight = parseFloat($('#kapanWeight').text());
                    let remaining = kapanWeight - res.total_weight;

                    $('#remainingWeight')
                        .text(remaining)
                        .removeClass('text-danger text-success')
                        .addClass(remaining < 0 ? 'text-danger' : 'text-success');

                    showAlert(res.message, 'success');
                } else {
                    showAlert(res.message, 'danger');
                    input.val(input.data('old'));
                }
            },
            error: function() {
                showAlert('Something went wrong!', 'danger');
                input.val(input.data('old'));
            }
        });
    });
</script>
@endsection