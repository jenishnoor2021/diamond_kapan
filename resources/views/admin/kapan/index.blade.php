@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Kapan List</h4>
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

                        <span id="menu-navi"
                            class="d-sm-flex flex-wrap text-center text-sm-start justify-content-sm-between">
                            <div class="gap-1">
                                <a class="btn btn-info waves-effect waves-light" href="{{ route('admin.kapan.create') }}">
                                    <i class="fa fa-plus editable" style="font-size:15px;">&nbsp;ADD</i>
                                </a>
                            </div>

                            <div class="align-self-start mt-3 mt-sm-0 mb-2">
                            </div>
                        </span>

                    </div>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Kapan Name</th>
                            <th>Kapan Weight</th>
                            <th>Kapan Quantity</th>
                            <th>Active/De-active</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($kapans as $kapan)
                        <tr>
                            <td>
                                <a href="{{ route('admin.kapan.edit', $kapan->id) }}" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.kapan.destroy', $kapan->id) }}" onclick="return confirm('Sure ! You want to delete ?');" class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-trash"></i></a>
                            </td>
                            <td>{{ $kapan->kapan_name }}</td>
                            <td>{{ $kapan->kapan_weight }}</td>
                            <td>{{ $kapan->kapan_quantity }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status-switch" type="checkbox"
                                        id="toggleSwitch{{ $kapan->id }}" data-id="{{ $kapan->id }}"
                                        {{ $kapan->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="toggleSwitch{{ $kapan->id }}">
                                        {{ $kapan->is_active ? 'Show' : 'Hide' }}
                                    </label>
                                </div>
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
</script>
@endsection