@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Edit income</h4>
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

            {!! Form::model($income, ['method'=>'PATCH', 'action'=> ['AdminPolishController@update', $income->id],'files'=>true,'class'=>'form-horizontal', 'name'=>'editkhataform']) !!}
            @csrf

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="khatas_id" class="form-label">Khata</label>
                     <select name="khatas_id" class="form-control" required>
                        @foreach($khatas as $khata)
                        <option value="{{$khata->id}}" {{$khata->id == $income->khatas_id ? 'selected' : '' }}>{{$khata->fname}} - {{$khata->lname}}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="amount" class="form-label">Amount</label>
                     <input type="number" name="amount" class="form-control" id="amount" placeholder="Enter amount" value="{{ $income->amount }}" required>
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="income_date" class="form-label">Date</label>
                     <input type="date" name="income_date" class="form-control" id="income_date" placeholder="Enter date" value="{{ $income->income_date }}" required>
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="income_type" class="form-label">Income Type</label>
                     <select name="income_type" class="form-control" required>
                        <option value="cash" {{$income->income_type == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="online" {{$income->income_type == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="cheque" {{$income->income_type == 'cheque' ? 'selected' : '' }}>Cheque</option>
                     </select>
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="note" class="form-label">Note</label>
                     <textarea type="text" name="note" class="form-control" id="note" placeholder="Enter note">{{ $income->note }}</textarea>
                  </div>
               </div>
            </div>

            <div class="d-flex gap-2">
               <button type="submit" class="btn btn-primary w-md">Update</button>
               <a class="btn btn-light w-md" href="{{ URL::to('/admin/income') }}">Back</a>
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