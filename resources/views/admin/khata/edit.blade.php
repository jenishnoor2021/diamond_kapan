@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Edit Party</h4>
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

            {!! Form::model($khata, ['method'=>'PATCH', 'action'=> ['AdminKhataController@update', $khata->id],'files'=>true,'class'=>'form-horizontal', 'name'=>'editkhataform']) !!}
            @csrf

            <div class="row">
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="type" class="form-label">Type</label>
                     <select name="type" class="form-control" required>
                        <option value="income" {{ $khata->type == 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ $khata->type == 'expense' ? 'selected' : '' }}>Expense</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="fname" class="form-label">First Name</label>
                     <input type="text" name="fname" class="form-control" id="fname" placeholder="Enter First name" onkeypress='return (event.charCode != 32)' value="{{$khata->fname}}" required>
                     @if($errors->has('fname'))
                     <div class="error text-danger">{{ $errors->first('fname') }}</div>
                     @endif
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="lname" class="form-label">Last Name</label>
                     <input type="text" name="lname" class="form-control" id="lname" placeholder="Enter Last Name" onkeypress='return (event.charCode != 32)' value="{{$khata->lname}}" required>
                     @if($errors->has('lname'))
                     <div class="error text-danger">{{ $errors->first('lname') }}</div>
                     @endif
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="party_code">Khata Code</label>
                     <input type="text" name="party_code" class="form-control" id="party_code" placeholder="Enter party code" onkeypress='return (event.charCode != 32)' value="{{$khata->party_code}}">
                     @if($errors->has('party_code'))
                     <div class="error text-danger">{{ $errors->first('party_code') }}</div>
                     @endif
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-lg-4">
                  <div class="mb-3">
                     <label for="mobile">Mobile no</label>
                     <input type="number" name="mobile" class="form-control" id="mobile" placeholder="Enter number" value="{{$khata->mobile}}">
                     @if($errors->has('mobile'))
                     <div class="error text-danger">{{ $errors->first('mobile') }}</div>
                     @endif
                  </div>
               </div>
            </div>

            <div class="d-flex gap-2">
               <button type="submit" class="btn btn-primary w-md">Update</button>
               <a class="btn btn-light w-md" href="{{ URL::to('/admin/party') }}">Back</a>
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

      $("form[name='editpartyform']").validate({
         rules: {
            fname: {
               required: true,
            },
            lname: {
               required: true,
            },
            // address: {
            //    required: true,
            // },
            // mobile: {
            //    required: true,
            // },
            party_code: {
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