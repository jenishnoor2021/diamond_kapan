@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Edit Cut</h4>
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

            {!! Form::model($cut, ['method'=>'PATCH', 'action'=> ['AdminCutController@update', $cut->id],'files'=>true,'class'=>'form-horizontal', 'name'=>'editcutform']) !!}
            @csrf

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="name" class="form-label">Name</label>
                     <input type="text" name="name" class="form-control" id="name" placeholder="Enter name" onkeypress='return (event.charCode != 32)' value="{{$cut->name}}" required>
                     @if($errors->has('name'))
                     <div class="error text-danger">{{ $errors->first('name') }}</div>
                     @endif
                  </div>
               </div>
            </div>

            <div class="d-flex gap-2">
               <button type="submit" class="btn btn-primary w-md">Update</button>
               <a class="btn btn-light w-md" href="{{ URL::to('/admin/cut') }}">Back</a>
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

      $("form[name='editcutform']").validate({
         rules: {
            name: {
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