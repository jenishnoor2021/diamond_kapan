@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Edit Detail</h4>
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

            {!! Form::model($data, ['method'=>'PATCH', 'action'=> ['AdminDiamondController@purchaseUpdate', $data->id],'files'=>true,'class'=>'form-horizontal', 'name'=>'editPurchaseform']) !!}
            @csrf

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Certificate #</label>
                     <input type="text" name="certi_no" id="certi_no" class="form-control" value="{{$data->certi_no}}" placeholder="Enter certi no" required>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Certificate Url</label>
                     <input type="text" name="Certificate_url" class="form-control" value="{{$data->Certificate_url}}" placeholder="Enter Certificate Url" required>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Availability</label>
                     <input type="text" name="availability" id="availability" class="form-control" placeholder="Enter ceavailability" value="{{$data->availability}}" required>
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Return Weight</label>
                     <input type="number"
                        step="0.01"
                        name="return_weight"
                        class="form-control return-weight"
                        id="return_weight"
                        value="{{$data->return_weight}}"
                        max=""
                        data-issue-weight=""
                        placeholder="returm weight"
                        required>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Return Date</label>
                     <input type="date"
                        name="return_date"
                        class="form-control return-date"
                        value="{{$data->return_date}}"
                        min=""
                        data-issue-date=""
                        required>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Shape</label>
                     <select name="r_shape" id="r_shape" class="form-select" required>
                        @foreach($shapes as $shape)
                        <option value="{{$shape->shape_type}}" {{$shape->shape_type == $data->r_shape ? 'selected' : '' }}>{{$shape->shape_type}}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Color</label>
                     <select name="r_color" id="r_color" class="form-select" required>
                        @foreach($colors as $color)
                        <option value="{{$color->c_name}}" {{$color->c_name == $data->r_color ? 'selected' : '' }}>{{$color->c_name}}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Clarity</label>
                     <select name="r_clarity" id="r_clarity" class="form-select" required>
                        @foreach($clarity as $clar)
                        <option value="{{$clar->name}}" {{$clar->name == $data->r_clarity ? 'selected' : '' }}>{{$clar->name}}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Polish</label>
                     <select name="r_polish" id="r_polish" class="form-select" required>
                        @foreach($polish as $pol)
                        <option value="{{$pol->name}}" {{$data->r_polish == $pol->name ? 'selected' : '' }}>{{$pol->name}}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Symmetry</label>
                     <select name="r_symmetry" id="r_symmetry" class="form-select" required>
                        @foreach($symmetry as $symme)
                        <option value="{{$symme->name}}" {{$symme->name == $data->r_symmetry ? 'selected' : '' }}>{{$symme->name}}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Price</label>
                     <input type="number" step="0.01" name="price" id="price" value="{{$data->price}}" class="form-control" placeholder="Enter Price">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Total price</label>
                     <input type="number" step="0.01" name="total_price" id="total_price" value="{{$data->total_price}}" class="form-control" placeholder="Enter total price">
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Image Link</label>
                     <input type="text" name="image_link" class="form-control" value="{{$data->image_link}}" placeholder="Enter image link">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>video Link</label>
                     <input type="text" name="video_link" class="form-control" value="{{$data->video_link}}" placeholder="Enter video link">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Depth Percent</label>
                     <input type="number" step="0.01" name="depth_percent" value="{{$data->depth_percent}}" class="form-control" placeholder="Enter depth percent">
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Table Percent</label>
                     <input type="number" step="0.01" name="table_percent" value="{{$data->table_percent}}" class="form-control" placeholder="Enter table percent">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Fluorescence Intensity</label>
                     <input type="text" name="fluorescence_intensity" value="{{$data->fluorescence_intensity}}" class="form-control" placeholder="Enter fluorescence intensity">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Lab</label>
                     <input type="text" name="lab" class="form-control" value="{{$data->lab}}" placeholder="Enter Lab">
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Measurements</label>
                     <input type="text" name="measurements" class="form-control" value="{{$data->measurements}}" placeholder="Enter measurements">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>BGM</label>
                     <input type="text" name="bgm" class="form-control" value="{{$data->bgm}}" placeholder="None">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>H&A</label>
                     <select name="h_and_a" id="h_and_a" class="form-select">
                        <option value="No" {{$data->h_and_a == 'No' ? 'selected' : '' }}>No</option>
                        <option value="Yes" {{$data->h_and_a == 'Yes' ? 'selected' : '' }}>Yes</option>
                     </select>
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>City</label>
                     <select name="city" id="city" class="form-select" required>
                        <option value="SURAT">SURAT</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>State</label>
                     <select name="state" id="state" class="form-select" required>
                        <option value="GUJARAT">GUJARAT</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Country</label>
                     <select name="country" id="country" class="form-select">
                        <option value="India">India</option>
                     </select>
                  </div>
               </div>
            </div>


            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Eye Clean</label>
                     <select name="eye_clean" id="eye_clean" class="form-select">
                        <option value="Yes" {{$data->eye_clean == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{$data->eye_clean == 'No' ? 'selected' : '' }}>No</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label>Growth Type</label>
                     <input type="text" name="growth_type" class="form-control" value="{{$data->growth_type}}" placeholder="CVD">
                  </div>
               </div>
            </div>

            <div class="d-flex gap-2">
               <button type="submit" class="btn btn-primary w-md">Update</button>
               <a class="btn btn-light w-md" href="{{ URL::to('/admin/purchase') }}">Back</a>
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
   $('#return_weight, #price').on('input', function() {
      let weight = parseFloat($('#return_weight').val()) || 0;
      let price = parseFloat($('#price').val()) || 0;

      $('#total_price').val((weight * price).toFixed(2));
   });
</script>
@endsection