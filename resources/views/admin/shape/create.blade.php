@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">ADD Shape</h4>

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

            {!! Form::open(['method'=>'POST', 'action'=> 'AdminShapeController@store','files'=>true,'class'=>'form-horizontal','name'=>'addshapeform']) !!}
            @csrf

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="shape_type" class="form-label">Type</label>
                     <input type="text" name="shape_type" class="form-control" id="shape_type" placeholder="Enter type" value="{{ old('shape_type') }}" required>
                     @if($errors->has('shape_type'))
                     <div class="error text-danger">{{ $errors->first('shape_type') }}</div>
                     @endif
                  </div>
               </div>
            </div>

            <div class="d-flex gap-2">
               <button type="submit" class="btn btn-primary w-md">Submit</button>
               <a class="btn btn-light w-md" href="{{ URL::to('/admin/shape') }}">Back</a>
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

      $("form[name='addshapeform']").validate({
         rules: {
            shape_type: {
               required: true,
            },
         },
         submitHandler: function(form) {
            form.submit();
         }
      });
   });
</script>
@endsection