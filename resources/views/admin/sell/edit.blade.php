@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Edit Sell</h4>
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

            {!! Form::model($sell, ['method'=>'PATCH', 'action'=> ['AdminDiamondController@sellUpdate', $sell->id],'files'=>true,'class'=>'form-horizontal', 'name'=>'editsellform', 'id' => 'sellEditForm']) !!}
            @csrf

            <input type="hidden" name="purchase_id" id="purchase_id" value="{{$sell->purchase_id}}">
            <input type="hidden" name="diamonds_id" id="diamonds_id" value="{{$sell->diamonds_id}}">

            <div class="row">

               <div class="col-md-4 mb-2">
                  <label>Diamond Weight</label>
                  <input type="number" step="0.01" name="pWeight" id="pWeight" value="{{$diamond->prediction_weight}}" class="form-control" disabled>
               </div>

               <!-- <div class="col-md-4 mb-2">
                  <label>Party Name</label>
                  <select name="parties_id" class="form-control">
                     <option value="">Select Party</option>
                     @foreach($partys as $party)
                     <option value="{{$party->id}}" {{$party->id == $sell->parties_id ? 'selected' : '' }}>{{$party->fname}} - {{$party->lname}}</option>
                     @endforeach
                  </select>
               </div> -->

               <div class="col-md-4 mb-2">
                  <label>Party Name</label>
                  <input type="text" name="parties_name" id="parties_name" class="form-control" value="{{$sell->parties_name}}">
               </div>

               <div class="col-md-4 mb-2">
                  <label>mobile no</label>
                  <input type="text" name="mobile_no" id="mobile_no" class="form-control" value="{{$sell->mobile_no}}">
               </div>

               <div class="col-md-4 mb-2">
                  <label>Ct Rate ($)</label>
                  <input type="number" step="0.01" name="rate_per_ct" id="rate_per_ct" class="form-control" value="{{$sell->rate_per_ct}}" required>
               </div>

               <!--  weightXratePerCt = pWeight * rate_per_ct  -->
               <div class="col-md-4 mb-2">
                  <label>Total $</label>
                  <input type="number" step="0.01" name="weightXratePerCt" id="weightXratePerCt" class="form-control" disabled>
               </div>

               <div class="col-md-4 mb-2">
                  <label>Today $ Rate</label>
                  <input type="number" step="0.01" name="dollar_rate" id="dollar_rate" class="form-control" value="{{$sell->dollar_rate}}" required>
               </div>

               <!-- final_amount = dollar_rate * weightXratePerCt -->
               <div class="col-md-4 mb-2">
                  <label>Total Amount (INR)</label>
                  <input type="number" step="0.01" name="final_amount" id="final_amount" class="form-control" value="{{$sell->final_amount}}" style="background-color:#f8f8fb" required readonly>
               </div>

               <!-- <div class="col-md-4 mb-2">
                  <label>Broker Name</label>
                  <select name="broker_id" class="form-control">
                     <option value="">Select Broker</option>
                     @foreach($brokers as $broker)
                     <option value="{{$broker->id}}" {{$broker->id == $sell->broker_id ? 'selected' : '' }}>{{$broker->fname}} - {{$broker->lname}}</option>
                     @endforeach
                  </select>
               </div> -->

               <div class="col-md-4 mb-2">
                  <label>Broker Name</label>
                  <input type="text" name="broker_name" id="broker_name" class="form-control" value="{{$sell->broker_name}}">
               </div>

               <div class="col-md-4 mb-2">
                  <label>Brokerage (%)</label>
                  <input type="number" step="0.01" name="less_brokerage" id="less_brokerage" value="{{$sell->less_brokerage}}" class="form-control">
               </div>

               <!-- brokerage_amount = final_amount * less_brokerage  / for ex. 18000* 2% = 360  -->
               <div class="col-md-4 mb-2">
                  <label>Brokerage Amount</label>
                  <input type="number" step="0.01" name="brokerage_amount" id="brokerage_amount" class="form-control" style="background-color:#f8f8fb" required disabled>
               </div>

               <!-- total_amount = final_amount - brokerage_amount -->
               <div class="col-md-4 mb-2">
                  <label>Income Amount</label>
                  <input type="number" step="0.01" name="total_amount" id="total_amount" value="{{$sell->total_amount}}" class="form-control" style="background-color:#f8f8fb" required readonly>
               </div>

               <div class="col-md-4 mb-2">
                  <label>Payment Type</label>
                  <select name="payment_type" class="form-control" required>
                     <option value="cash" {{ $sell->payment_type == 'cash' ? 'selected' : '' }}>Cash</option>
                     <option value="online" {{ $sell->payment_type == 'online' ? 'selected' : '' }}>Online</option>
                     <option value="cheque" {{ $sell->payment_type == 'cheque' ? 'selected' : '' }}>Cheque</option>
                  </select>
               </div>

               <div class="col-md-4 mb-2">
                  <label>Status</label>
                  <select name="payment_status" class="form-control" required>
                     <option value="unpaid" {{ $sell->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                     <option value="paid" {{ $sell->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                  </select>
               </div>

               <div class="col-md-4 mb-2">
                  <label>Sell Date</label>
                  <input type="date" name="sell_date" value="{{$sell->sell_date}}" class="form-control" required>
               </div>

               <div class="col-md-4 mb-2">
                  <label>Due Date</label>
                  <input type="date" name="due_date" value="{{$sell->due_date}}" class="form-control" required>
               </div>

               <div class="col-md-4 mb-2">
                  <label>Note</label>
                  <textarea type="text" name="note" class="form-control">{{$sell->note}}</textarea>
               </div>

            </div>

            <div class="d-flex gap-2">
               <button type="submit" class="btn btn-primary w-md">Update</button>
               <a class="btn btn-light w-md" href="{{ URL::to('/admin/sell') }}">Back</a>
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

      $("form[name='editsellform']").validate({
         rules: {
            rate_per_ct: {
               required: true,
            },
            dollar_rate: {
               required: true,
            },
            total_amount: {
               required: true,
            },
            less_brokerage: {
               required: true,
            },
            final_amount: {
               required: true,
            },
            // parties_id: {
            //    required: true,
            // },
            payment_type: {
               required: true,
            },
            payment_status: {
               required: true,
            },
            sell_date: {
               required: true,
            },
         },
         submitHandler: function(form) {
            form.submit();
         }
      });
   });
</script>

<script>
   calculateAmounts();

   function calculateAmounts() {

      let pWeight = parseFloat(document.getElementById('pWeight').value) || 0;
      let ratePerCt = parseFloat(document.getElementById('rate_per_ct').value) || 0;
      let dollarRate = parseFloat(document.getElementById('dollar_rate').value) || 0;
      let brokeragePer = parseFloat(document.getElementById('less_brokerage').value) || 0;

      // 1. Weight × Rate per Ct
      let weightXrate = pWeight * ratePerCt;
      document.getElementById('weightXratePerCt').value = weightXrate.toFixed(2);

      // 2. Final Amount (INR)
      let finalAmount = weightXrate * dollarRate;
      document.getElementById('final_amount').value = finalAmount.toFixed(2);

      // 3. Brokerage Amount
      let brokerageAmount = (finalAmount * brokeragePer) / 100;
      document.getElementById('brokerage_amount').value = brokerageAmount.toFixed(2);

      // 4. Income Amount
      let totalAmount = finalAmount - brokerageAmount;
      document.getElementById('total_amount').value = totalAmount.toFixed(2);
   }

   // Trigger calculation on input change
   document.querySelectorAll(
      '#pWeight, #rate_per_ct, #dollar_rate, #less_brokerage'
   ).forEach(el => {
      el.addEventListener('input', calculateAmounts);
   });

   document.getElementById('sellEditForm').addEventListener('submit', function(e) {

      // let party = document.querySelector('[name="parties_id"]').value;
      // let broker = document.querySelector('[name="broker_id"]').value;

      let party = document.querySelector('[name="parties_name"]').value;
      let broker = document.querySelector('[name="broker_name"]').value;

      if (!party && !broker) {
         e.preventDefault(); // form submit rok do
         // alert('Please select at least Party or Broker.');
         showAlert('Please enter at least Party or Broker.', 'danger');
         return false;
      }
   });
</script>
@endsection