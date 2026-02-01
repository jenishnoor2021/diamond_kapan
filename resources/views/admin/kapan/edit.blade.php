@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Kapan</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit</h4>

                @include('includes.flash_message')

                {!! Form::model($kapan, ['method'=>'PATCH', 'action'=> ['AdminKapanController@update', $kapan->id],'files'=>true,'class'=>'form-horizontal', 'name'=>'editkapanform']) !!}
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kapan_name">Kapan Name</label>
                            <input type="text" name="kapan_name" class="form-control" id="kapan_name"
                                placeholder="Enter kapan name" onkeypress='return (event.charCode != 32)' value="{{ $kapan->kapan_name }}" required>
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
                                value="{{ $kapan->kapan_weight }}"
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
                                value="{{ $kapan->kapan_quantity }}"
                                required>
                            @if ($errors->has('kapan_quantity'))
                            <div class="error text-danger">{{ $errors->first('kapan_quantity') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">Update</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/kapan') }}">Back</a>
                </div>
                </form>
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

        $("form[name='editkapanform']").validate({
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