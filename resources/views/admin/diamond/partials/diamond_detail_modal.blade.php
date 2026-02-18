<h4 class="text-primary mb-3">Diamond Information</h4>

<table class="table table-sm table-bordered">
  <tr>
    <th width="30%">Diamond Name</th>
    <td>{{ $diamond->diamond_name }}</td>
  </tr>
  <tr>
    <th>Kapan</th>
    <td>{{ $diamond->kapan->kapan_name ?? '-' }}</td>
  </tr>
  <tr>
    <th>Kapan Part</th>
    <td>{{ $diamond->kapanPart->name ?? '-' }}</td>
  </tr>
  <tr>
    <th>Janger No</th>
    <td>{{ $diamond->janger_no ?? '-' }}</td>
  </tr>
  <tr>
    <th>Barcode</th>
    <td>{{ $diamond->barcode_number ?? '-' }}</td>
  </tr>
  <tr>
    <th>Weight</th>
    <td>{{ $diamond->weight }}</td>
  </tr>
  <tr>
    <th>Prediction Weight</th>
    <td>{{ $diamond->prediction_weight }}</td>
  </tr>
  <tr>
    <th>Shape</th>
    <td>{{ $diamond->shape }}</td>
  </tr>
  <tr>
    <th>Color</th>
    <td>{{ $diamond->color }}</td>
  </tr>
  <tr>
    <th>Clarity</th>
    <td>{{ $diamond->clarity }}</td>
  </tr>
  <tr>
    <th>Cut</th>
    <td>{{ $diamond->cut }}</td>
  </tr>
  <tr>
    <th>Polish</th>
    <td>{{ $diamond->polish }}</td>
  </tr>
  <tr>
    <th>Symmetry</th>
    <td>{{ $diamond->symmetry }}</td>
  </tr>
  <tr>
    <th>Status</th>
    <td><span class="badge bg-dark">{{ ucfirst($diamond->status) }}</span></td>
  </tr>
  <tr>
    <th>Delivery Date</th>
    <td>{{ $diamond->delevery_date ?? '-' }}</td>
  </tr>
</table>


@if($diamond->issues->count())
<h4 class="text-warning mt-4">Issue History</h4>

@foreach($diamond->issues as $issue)
<div class="border rounded p-2 mb-3">

  <table class="table table-sm table-bordered">
    <tr>
      <th width="30%">Issue Date</th>
      <td>{{ $issue->issue_date }}</td>
    </tr>
    <tr>
      <th>Return Date</th>
      <td>{{ $issue->return_date ?? '-' }}</td>
    </tr>
    <tr>
      <th>Issue Weight</th>
      <td>{{ $issue->issue_weight }}</td>
    </tr>
    <tr>
      <th>Return Weight</th>
      <td>{{ $issue->return_weight }}</td>
    </tr>
    <tr>
      <th>Worker ID</th>
      <td>{{ $issue->worker_id }}</td>
    </tr>
    <tr>
      <th>Shape</th>
      <td>{{ $issue->r_shape }}</td>
    </tr>
    <tr>
      <th>Color</th>
      <td>{{ $issue->r_color }}</td>
    </tr>
    <tr>
      <th>Clarity</th>
      <td>{{ $issue->r_clarity }}</td>
    </tr>
    <tr>
      <th>Cut</th>
      <td>{{ $issue->r_cut }}</td>
    </tr>
    <tr>
      <th>Polish</th>
      <td>{{ $issue->r_polish }}</td>
    </tr>
    <tr>
      <th>Symmetry</th>
      <td>{{ $issue->r_symmetry }}</td>
    </tr>
    <tr>
      <th>Lab</th>
      <td>{{ $issue->lab }}</td>
    </tr>
    <tr>
      <th>Certificate No</th>
      <td>{{ $issue->certi_no }}</td>
    </tr>
    <tr>
      <th>Price</th>
      <td>{{ $issue->price }}</td>
    </tr>
    <tr>
      <th>Total Price</th>
      <td>{{ $issue->total_price }}</td>
    </tr>
    <tr>
      <th>City</th>
      <td>{{ $issue->city }}</td>
    </tr>
    <tr>
      <th>State</th>
      <td>{{ $issue->state }}</td>
    </tr>
    <tr>
      <th>Country</th>
      <td>{{ $issue->country }}</td>
    </tr>
  </table>

</div>
@endforeach
@endif


@if($diamond->purchase)
<h4 class="text-info mt-4">Purchase Details</h4>

<table class="table table-sm table-bordered">
  <tr>
    <th width="30%">Purchase ID</th>
    <td>{{ $diamond->purchase->id }}</td>
  </tr>
  <tr>
    <th>Is Sold</th>
    <td>{{ $diamond->purchase->is_sell ? 'Yes' : 'No' }}</td>
  </tr>
  <tr>
    <th>Created At</th>
    <td>{{ $diamond->purchase->created_at }}</td>
  </tr>
</table>
@endif


@if($diamond->sell)
<h4 class="text-success mt-4">Sell Details</h4>

<table class="table table-sm table-bordered">
  <tr>
    <th width="30%">Party</th>
    <td>{{ $diamond->sell->parties_name }}</td>
  </tr>
  <tr>
    <th>Rate Per Ct</th>
    <td>{{ $diamond->sell->rate_per_ct }}</td>
  </tr>
  <tr>
    <th>Dollar Rate</th>
    <td>{{ $diamond->sell->dollar_rate }}</td>
  </tr>
  <tr>
    <th>Total Amount</th>
    <td>{{ $diamond->sell->total_amount }}</td>
  </tr>
  <tr>
    <th>Brokerage</th>
    <td>{{ $diamond->sell->less_brokerage }}</td>
  </tr>
  <tr>
    <th>Final Amount</th>
    <td>{{ $diamond->sell->final_amount }}</td>
  </tr>
  <tr>
    <th>Payment Type</th>
    <td>{{ $diamond->sell->payment_type }}</td>
  </tr>
  <tr>
    <th>Payment Status</th>
    <td>
      <span class="badge {{ $diamond->sell->payment_status=='paid'?'bg-success':'bg-danger' }}">
        {{ ucfirst($diamond->sell->payment_status) }}
      </span>
    </td>
  </tr>
  <tr>
    <th>Sell Date</th>
    <td>{{ $diamond->sell->sell_date }}</td>
  </tr>
  <tr>
    <th>Due Date</th>
    <td>{{ $diamond->sell->due_date }}</td>
  </tr>
  <tr>
    <th>Broker</th>
    <td>{{ $diamond->sell->broker_name }}</td>
  </tr>
  <tr>
    <th>Mobile</th>
    <td>{{ $diamond->sell->mobile_no }}</td>
  </tr>
  <tr>
    <th>Note</th>
    <td>{{ $diamond->sell->note }}</td>
  </tr>
</table>
@endif