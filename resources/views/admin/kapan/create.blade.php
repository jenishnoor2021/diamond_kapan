@extends('layouts.admin')
@section('style')
<style>
    @media (min-width: 768px) {
        .w-md {
            min-width: 110px;
        }
    }
</style>
@endsection
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">ADD Kapan</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">ADD</h4>

                @include('includes.flash_message')

                {!! Form::open([
                'method' => 'POST',
                'action' => 'AdminKapanController@store',
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'addkapanform',
                'id' => 'addkapanform',
                ]) !!}
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kapan_name">Kapan Name</label>
                            <input type="text" name="kapan_name" class="form-control" id="kapan_name"
                                placeholder="Enter kapan name" onkeypress='return (event.charCode != 32)' value="{{ old('kapan_name') }}" required>
                            @if ($errors->has('kapan_name'))
                            <div class="error text-danger">{{ $errors->first('kapan_name') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kapan_weight">kapan Weight</label>
                            <input type="text"
                                name="kapan_weight"
                                class="form-control"
                                id="kapan_weight"
                                placeholder="00.00"
                                inputmode="decimal"
                                oninput="formatWeight(this)"
                                value="{{ old('kapan_weight') }}"
                                required>
                            @if ($errors->has('kapan_weight'))
                            <div class="error text-danger">{{ $errors->first('kapan_weight') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kapan_quantity">kapan Quantity</label>
                            <input type="number"
                                name="kapan_quantity"
                                class="form-control"
                                id="kapan_quantity"
                                placeholder="0"
                                step="1"
                                min="0"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                value="{{ old('kapan_quantity') }}"
                                required>
                            @if ($errors->has('kapan_quantity'))
                            <div class="error text-danger">{{ $errors->first('kapan_quantity') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="per_carat_rate">Per Carat Rate</label>
                            <input type="text"
                                name="per_carat_rate"
                                class="form-control"
                                id="per_carat_rate"
                                placeholder="00.00"
                                inputmode="decimal"
                                oninput="formatWeight(this)"
                                value="{{ old('per_carat_rate') }}"
                                required>
                            @if ($errors->has('per_carat_rate'))
                            <div class="error text-danger">{{ $errors->first('per_carat_rate') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="doller_rate">Current $ Rate</label>
                            <input type="text"
                                name="doller_rate"
                                class="form-control"
                                id="doller_rate"
                                placeholder="00.00"
                                inputmode="decimal"
                                oninput="formatWeight(this)"
                                value="{{ old('doller_rate') }}"
                                required>
                            @if ($errors->has('doller_rate'))
                            <div class="error text-danger">{{ $errors->first('doller_rate') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="total_rate">Total Amount</label>
                            <input type="text"
                                name="total_rate"
                                class="form-control"
                                id="total_rate"
                                placeholder="00.00"
                                inputmode="decimal"
                                oninput="formatWeight(this)"
                                value="{{ old('total_rate') }}"
                                required>
                            @if ($errors->has('total_rate'))
                            <div class="error text-danger">{{ $errors->first('total_rate') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="hpht_cost">HPHT Cost</label>
                            <input type="text"
                                name="hpht_cost"
                                class="form-control"
                                id="hpht_cost"
                                placeholder="00.00"
                                inputmode="decimal"
                                oninput="formatWeight(this)"
                                value="0">
                            @if ($errors->has('hpht_cost'))
                            <div class="error text-danger">{{ $errors->first('hpht_cost') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="mfc_cost">Manufacturing Cost</label>
                            <input type="text"
                                name="mfc_cost"
                                class="form-control"
                                id="mfc_cost"
                                placeholder="00.00"
                                inputmode="decimal"
                                oninput="formatWeight(this)"
                                value="0">
                            @if ($errors->has('mfc_cost'))
                            <div class="error text-danger">{{ $errors->first('mfc_cost') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="certificate_cost">Certificate Cost</label>
                            <input type="text"
                                name="certificate_cost"
                                class="form-control"
                                id="certificate_cost"
                                placeholder="00.00"
                                inputmode="decimal"
                                oninput="formatWeight(this)"
                                value="0">
                            @if ($errors->has('certificate_cost'))
                            <div class="error text-danger">{{ $errors->first('certificate_cost') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary w-md">Save</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/kapan') }}">Back</a>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->
@endsection

@section('script')
<script>
    $(function() {

        $("form[name='addkapanform']").validate({
            rules: {
                kapan_name: {
                    required: true
                },
                kapan_weight: {
                    required: true,
                    number: true
                },
                kapan_quantity: {
                    required: true,
                    digits: true
                }
            },
            messages: {
                kapan_quantity: {
                    digits: "Please enter a whole number"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
<script>
    function formatWeight(input) {
        let value = input.value.replace(/[^0-9.]/g, '');

        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts[1];
        }

        if (parts[1]) {
            parts[1] = parts[1].substring(0, 2);
            value = parts.join('.');
        }

        input.value = value;
    }
</script>
<script>
    function calculateTotal() {

        let kapan_weight = parseFloat($('#kapan_weight').val()) || 0;
        let per_carat_rate = parseFloat($('#per_carat_rate').val()) || 0;
        let doller_rate = parseFloat($('#doller_rate').val()) || 0;

        let total = kapan_weight * per_carat_rate * doller_rate;

        $('#total_rate').val(total.toFixed(2));
    }

    // trigger on input change
    $('#kapan_weight, #per_carat_rate, #doller_rate').on('input', function() {
        calculateTotal();
    });

    // run on page load (edit case)
    $(document).ready(function() {
        calculateTotal();
    });
</script>
@endsection