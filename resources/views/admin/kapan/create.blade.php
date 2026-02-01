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
@endsection