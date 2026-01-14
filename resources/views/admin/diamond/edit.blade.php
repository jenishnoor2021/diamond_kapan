@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Diamond</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                @include('includes.flash_message')

                {!! Form::model($diamond, ['method'=>'PATCH', 'action'=> ['AdminDiamondController@updateByEdit', $diamond->id],'files'=>true,'class'=>'form-horizontal', 'name'=>'editdiamondform']) !!}
                @csrf

                <div class="row">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="janger_no">Janger no</label>
                            <input type="text" name="janger_no" class="form-control" id="janger_no"
                                placeholder="Enter Janger no" value="{{$diamond->janger_no}}">
                            @if ($errors->has('janger_no'))
                            <div class="error text-danger">{{ $errors->first('janger_no') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="diamond_name">Diamond Name</label>
                            <input type="text" name="diamond_name" class="form-control" id="diamond_name"
                                placeholder="Enter Stone Id" value="{{$diamond->diamond_name}}" required>
                            @if ($errors->has('diamond_name'))
                            <div class="error text-danger">{{ $errors->first('diamond_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="weight">Row Weight</label>
                            <input type="text" name="weight" class="form-control" id="weight" placeholder="00.00"
                                oninput="formatWeight(this);" value="{{$diamond->weight}}" required>
                            @if ($errors->has('weight'))
                            <div class="error text-danger">{{ $errors->first('weight') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="prediction_weight">Polished Weight</label>
                            <input type="text" name="prediction_weight" class="form-control" id="prediction_weight"
                                placeholder="00.00" value="{{$diamond->prediction_weight}}" oninput="formatWeight(this);"
                                required>
                            @if ($errors->has('prediction_weight'))
                            <div class="error text-danger">{{ $errors->first('prediction_weight') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">Update</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/diamonds') }}">Back</a>
                </div>
                </form>
            </div>
            <!-- end card body -->
        </div>


        @if($diamond->issues->count())
        <div class="card">
            <div class="card-body">

                <h5 class="text-success">Diamond Issue History</h5>

                <table class="table table-bordered mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Designation</th>
                            <th>Worker</th>
                            <!-- <th>Issue Weight</th> -->
                            <th>Issue Date</th>
                            <th>Return Weight</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($diamond->issues as $key => $issue)
                        <tr id="issue_row_{{ $issue->id }}">
                            <td>{{ $key+1 }}</td>
                            <td>{{ $issue->designation->name }}</td>
                            <td>{{ $issue->worker->fname ?? '' }}</td>

                            <!-- <td>
                                <input type="number"
                                    class="form-control form-control-sm"
                                    id="issue_weight_{{ $issue->id }}"
                                    value="{{ $issue->issue_weight }}">
                            </td> -->

                            <td>
                                <input type="date"
                                    class="form-control form-control-sm"
                                    id="issue_date_{{ $issue->id }}"
                                    value="{{ $issue->issue_date }}">
                            </td>

                            <td>
                                <input type="number"
                                    class="form-control form-control-sm"
                                    id="return_weight_{{ $issue->id }}"
                                    value="{{ $issue->return_weight }}">
                            </td>

                            <td>
                                <input type="date"
                                    class="form-control form-control-sm"
                                    id="return_date_{{ $issue->id }}"
                                    value="{{ $issue->return_date }}">
                            </td>

                            <td>
                                @if($issue->is_return)
                                <span class="badge bg-success">Returned</span>
                                @else
                                <span class="badge bg-warning">In Process</span>
                                @endif
                            </td>

                            <td>
                                <button class="btn btn-success btn-sm"
                                    onclick="updateIssue({{ $issue->id }})">
                                    Save
                                </button>

                                <button class="btn btn-danger btn-sm"
                                    onclick="deleteIssue({{ $issue->id }})">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

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
    <!-- end col -->
</div>
<!-- end row -->
@endsection

@section('script')
<script>
    $(function() {

        $("form[name='editdiamondform']").validate({
            rules: {
                diamond_name: {
                    required: true,
                },
                // janger_no: {
                //     required: true,
                // },
                // shape: {
                //     required: true,
                // },
                weight: {
                    required: true,
                },
                // clarity: {
                //     required: true,
                // },
                // color: {
                //     required: true,
                // },
                // cut: {
                //     required: true,
                // },
                // polish: {
                //     required: true,
                // },
                // symmetry: {
                //     required: true,
                // }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
<script>
    function formatWeight(input) {
        // Remove any non-numeric characters
        var cleanedValue = input.value.replace(/[^0-9.]/g, '');

        // Ensure valid pattern: either empty, '0.00', or '00.00'
        var match = cleanedValue.match(/^(\d{0,2}(\.\d{0,2})?)?$/);

        // Update the input value with the formatted result
        input.value = match ? match[1] || '' : '';
    }
</script>

<script>
    function updateIssue(issueId) {

        // let issueWeight = parseFloat($('#issue_weight_' + issueId).val()) || 0;
        let returnWeight = parseFloat($('#return_weight_' + issueId).val()) || 0;

        // if (returnWeight > issueWeight) {
        //     alert('Return weight cannot be greater than Issue weight');
        //     $('#return_weight_' + issueId).focus();
        //     return;
        // }

        let data = {
            _token: '{{ csrf_token() }}',
            // issue_weight: issueWeight,
            return_weight: returnWeight,
            issue_date: $('#issue_date_' + issueId).val(),
            return_date: $('#return_date_' + issueId).val()
        };

        $.ajax({
            url: '/admin/issue/update-inline/' + issueId,
            type: 'POST',
            data: data,
            success: function(res) {
                alert('updated successfully');
                // toastr.success('updated successfully');
            },
            error: function() {
                alert('Something went wrong');
                // toastr.error('Something went wrong');
            }
        });
    }

    function deleteIssue(issueId) {

        if (!confirm('Are you sure you want to delete this issue?')) {
            return;
        }

        $.ajax({
            url: '/admin/issue/delete-inline/' + issueId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                $('#issue_row_' + issueId).remove();
                toastr.success('Issue deleted successfully');
            },
            error: function() {
                toastr.error('Delete failed');
            }
        });
    }
</script>
@endsection