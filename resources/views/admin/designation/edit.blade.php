@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Designation</h4>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                @include('includes.flash_message')

                {!! Form::model($designation, [
                'method' => 'PATCH',
                'action' => ['AdminDesignationController@update', $designation->id],
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'editdesignationform',
                ]) !!}
                @csrf

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="category">Designation Category</label>
                            <select name="category" id="category" class="form-select" required
                                {{ $designation->name == 'Grading' ? 'disabled' : '' }}>
                                <option value="">Select category</option>
                                <option value="Inner" {{ $designation->category == 'Inner' ? 'selected' : '' }}>Inner
                                </option>
                                <option value="Outter" {{ $designation->category == 'Outter' ? 'selected' : '' }}>Outter
                                </option>
                            </select>

                            @if ($designation->name == 'Grading')
                            <input type="hidden" name="category" value="{{ $designation->category }}">
                            @endif

                            @if ($errors->has('category'))
                            <div class="error text-danger">{{ $errors->first('category') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="name">Designation Name</label>
                            <input type="text" name="name" class="form-control" id="name"
                                placeholder="Enter name" onkeypress='return (event.charCode != 32)'
                                value="{{ $designation->name }}"
                                {{ $designation->name == 'Grading' ? 'readonly' : '' }}>
                            @if ($errors->has(' name'))
                            <div class="error text-danger">{{ $errors->first('name') }}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="rate_apply_on">Rate Calculate On</label>
                            <select name="rate_apply_on" id="rate_apply_on" class="form-select" style="width:100%"
                                required>
                                <option value="">Select weight</option>
                                <option value="issue_weight"
                                    {{ $designation->rate_apply_on == 'issue_weight' ? 'selected' : '' }}>Issue weight
                                </option>
                                <option value="return_weight"
                                    {{ $designation->rate_apply_on == 'return_weight' ? 'selected' : '' }}>Return weight
                                </option>
                                <option value="diff_weight"
                                    {{ $designation->rate_apply_on == 'diff_weight' ? 'selected' : '' }}>Diffrence
                                    weight</option>
                                <option value="ready_to_ruff_weight"
                                    {{ $designation->rate_apply_on == 'ready_to_ruff_weight' ? 'selected' : '' }}>Ready
                                    To Ruff</option>
                            </select>
                            @if ($errors->has('rate_apply_on'))
                            <div class="error text-danger">{{ $errors->first('rate_apply_on') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <hr />

                <div class="mb-3">
                    <div class="">
                        <b>Round Rate</b>
                    </div>
                </div>

                <div class="row">
                    @foreach ($roundworkerrangs as $roundworkerrang)
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="{{ $roundworkerrang->key }}" class="form-label">Rate
                                ({{ $roundworkerrang->min_value }} to {{ $roundworkerrang->max_value }})
                            </label>
                            <input type="number" name="{{ $roundworkerrang->key }}" class="form-control"
                                id="{{ $roundworkerrang->key }}" placeholder="Enter amount"
                                value="{{ $designationWiseRate[$roundworkerrang->key] ?? $roundworkerrang->value }}">
                            @if ($errors->has('$roundworkerrang->key'))
                            <div class="error text-danger">{{ $errors->first('$roundworkerrang->key') }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <hr />

                <div class="mb-3">
                    <div class="">
                        <b>Fancy Rate</b>
                    </div>
                </div>

                <div class="row">
                    @foreach ($otherworkerrangs as $otherworkerrang)
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="{{ $otherworkerrang->key }}" class="form-label">Rate
                                ({{ $otherworkerrang->min_value }} to {{ $otherworkerrang->max_value }})
                            </label>
                            <input type="number" name="{{ $otherworkerrang->key }}" class="form-control"
                                id="{{ $otherworkerrang->key }}" placeholder="Enter amount"
                                value="{{ $designationWiseRate[$roundworkerrang->key] ?? $otherworkerrang->value }}">
                            @if ($errors->has('$otherworkerrang->key'))
                            <div class="error text-danger">{{ $errors->first('$otherworkerrang->key') }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">update</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/designation') }}">Back</a>
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
        $("form[name='editdesignationform']").validate({
            rules: {
                name: {
                    required: true,
                },
                category: {
                    required: true,
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@endsection