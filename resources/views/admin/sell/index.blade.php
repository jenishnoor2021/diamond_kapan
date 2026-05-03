@extends('layouts.admin')
<link href="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Diamond Sell List</h4>
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
                    </div>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Diamond Name</th>
                            <th>Party</th>
                            <th>Broker</th>
                            <th>Mobile</th>
                            <th>Final Rate</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sells as $sell)
                        <tr>
                            <td>
                                <a href="{{ route('admin.sell.edit', $sell->id) }}" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.sell.destroy', $sell->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                            <td>{{ $sell->diamond->diamond_name }}</td>
                            <td>{{ $sell->parties_name }}</td>
                            <td>{{ $sell->broker_name }}</td>
                            <td>{{ $sell->mobile_no }}</td>
                            <td>{{ $sell->final_amount }}</td>
                            <td>
                                <input type="checkbox"
                                    class="payment-toggle"
                                    data-id="{{ $sell->id }}"
                                    {{ $sell->payment_status == 'paid' ? 'checked' : '' }}
                                    data-toggle="toggle"
                                    data-on="Paid"
                                    data-off="Unpaid"
                                    data-onstyle="success"
                                    data-offstyle="danger">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {

        let table = $('#datatable').DataTable();

        // ✅ init toggle first time
        initToggle();

        // ✅ re-init on every draw (pagination, search, sort)
        $('#datatable').on('draw.dt', function() {
            initToggle();
        });

    });

    // ✅ reusable function (IMPORTANT)
    function initToggle() {
        $('.payment-toggle').each(function() {

            // ❗ destroy if already initialized
            if ($(this).parent().hasClass('toggle')) {
                $(this).bootstrapToggle('destroy');
            }

            // ✅ re-init properly
            $(this).bootstrapToggle();
        });
    }
</script>

<script>
    $(document).on('change', '.payment-toggle', function() {
        let toggle = $(this);

        // 🚫 prevent multiple clicks
        if (toggle.data('processing')) return;

        toggle.data('processing', true);

        let id = toggle.data('id');
        let isChecked = toggle.prop('checked');
        let previousState = !isChecked;

        let status = isChecked ? 'paid' : 'unpaid';

        $.ajax({
            url: "{{ route('admin.sell.updateStatus') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                status: status
            },
            success: function(response) {

                if (response.success) {
                    toastr.success(response.message || 'Payment status updated');
                } else {
                    toastr.error(response.message || 'Update failed');

                    // ✅ proper revert (NO change() trigger)
                    toggle.bootstrapToggle(previousState ? 'on' : 'off');
                }
            },
            error: function(xhr) {

                // ❗ show actual error if exists
                let msg = 'Something went wrong';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }

                toastr.error(msg);

                // ✅ proper revert
                toggle.bootstrapToggle(previousState ? 'on' : 'off');
            },
            complete: function() {
                toggle.data('processing', false); // unlock
            }
        });
    });
</script>
@endsection