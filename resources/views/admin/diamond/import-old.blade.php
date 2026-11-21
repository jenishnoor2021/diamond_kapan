@extends('layouts.admin')
@section('style')
@endsection
@section('content')
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <h4 class="mb-0 font-size-18">Diamonds</h4>
      </div>
    </div>
  </div>
</div>
<!-- end page title -->

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        @include('includes.flash_message')

        <form action="{{ route('admin.importPriceUpdate') }}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="import_file" class="form-label">Import File</label>
            <input type="file" name="import_file" id="import_file" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary">Import</button>
        </form>

      </div>
    </div>
  </div>
</div>
<!-- end row -->
@endsection

@section('script')

@endsection