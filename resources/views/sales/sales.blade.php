@extends('gst.layouts.main')

@section('title', 'MobiTAX GST')

@section('content')

<style type="text/css">
	a:hover, a:link{
		text-decoration: none;
	}
	.error{
		display: inline-block;
		max-width: 100%;
		margin-bottom: 5px;
		font-weight: 400;
		color: #d24c2d !important;
	}
</style>

<div class="content">
	<div class="train w3-agile">
		<div class="container">
			<h2>Sales Invoices</h2>
			<div class="row">
				<div class="col-md-4" style="padding: 20px 14px;">
					<a href="../selectSalesInvoice/{{$data['gstin_id']}}">
						<button class="btn btn-success" type="button" style="float: left;"> + New Sales Invoice</button>
					</a>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-bordered">
					<tr>
						<td rowspan="2">Summary</td>
						<td>Total Transactions</td>
						<td>Total SGST</td>
						<td>Total CGST</td>
						<td>Total IGST</td>
						<td>Total Cess</td>
						<td>Total Value</td>
					</tr>
					<tr>
						@if(!empty($data['data']['total']))
						<td> {{$data['data']['total']['totalTransactions']}}</td>
						<td> <i class="fa fa-inr" aria-hidden="true"></i> {{$data['data']['total']['totalSGST']}}</td>
						<td> <i class="fa fa-inr" aria-hidden="true"></i> {{$data['data']['total']['totalCGST']}}</td>
						<td> <i class="fa fa-inr" aria-hidden="true"></i> {{$data['data']['total']['totalIGST']}}</td>
						<td> <i class="fa fa-inr" aria-hidden="true"></i> {{$data['data']['total']['totalCESS']}}</td>
						<td> <i class="fa fa-inr" aria-hidden="true"></i> {{$data['data']['total']['totalValue']}}</td>
						@else
						<td> 0</td>
						<td> <i class="fa fa-inr" aria-hidden="true"></i> 0</td>
						<td> <i class="fa fa-inr" aria-hidden="true"></i> 0</td>
						<td> <i class="fa fa-inr" aria-hidden="true"></i> 0</td>
						<td> <i class="fa fa-inr" aria-hidden="true"></i> 0</td>
						<td> <i class="fa fa-inr" aria-hidden="true"></i> 0</td>
						@endif
					</tr>
				</table>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Invoice ID</th>
							<th>Contact</th>
							<th>Created Date</th>
							<th>Due Date</th>
							<th>Total Amount</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($data['data']['salesInvoiceData']))
						@foreach($data['data']['salesInvoiceData'] as $key => $value)
						<tr>
							<td>{{$value->invoice_no}}</td>
							<td>{{$value->contact_name}}</td>
							<td>{{$value->created_date}}</td>
							<td>{{$value->due_date}}</td>
							<td>{{$value->total_amount}}</td>
							<td>
								@if($value->status == '0')
								Cancelled
								@else
								Active
								@endif
							</td>
							<td>
								<a class='btn btn-sm btn-info' href="editSalesInvoice/{{encrypt($value->si_id)}}">Edit</a>
								<a class='btn btn-sm btn-warning' onclick=cancelInvoice(this); data-id='{{$value->si_id}}'>Cancel</a>
							</td>
						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="7">No Invoice found. Click on add sales invoice button to add one.</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="{{URL::asset('app/js/sales.js')}}"></script>

@endsection