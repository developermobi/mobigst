@extends('gst.layouts.main')

@section('title', 'MobiTAX GST')

@section('content')

<style type="text/css">
	.error{
		display: inline-block;
		max-width: 100%;
		margin-bottom: 5px;
		font-weight: 400;
		color: #d24c2d !important;
	}
</style>

<div class="benefits w3l">
	<div class="container">
		<div class="benefits-grids">
			<div class="col-md-6 benefits-grid wow fadeInLeft animated animated" data-wow-delay="0.4s">
				<h3>benefits join today</h3>
				<div class="benefit-top">
					<div class="benefit-left">
						<h4>1</h4>
					</div>
					<div class="benefit-right">
						<p>Learn about GST from A-Z. GST registration, invoicing, compliance, return filing and reconciliation all at one place.</p>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="benefit-top">
					<div class="benefit-left">
						<h4>2</h4>
					</div>
					<div class="benefit-right">
						<p>Find out different types of returns to be filed under the GST regime. Get details of what to file and when to file.</p>
					</div>
					<div class="clearfix"></div>
				</div><div class="benefit-top">
				<div class="benefit-left">
					<h4>3</h4>
				</div>
				<div class="benefit-right">
					<p>Completing a lot of tasks and in time become difficult with traditional desktop softwares</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="benefit-top">
				<div class="benefit-left">
					<h4>4</h4>
				</div>
				<div class="benefit-right">
					<p> Manual invoicing becomes inefficient for compliance and reconciliation - 20+ mandatory data fields in each invoice</p>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		@if($data['code'] == '200')
		<div class="col-md-6 benefits-grid1 wow fadeInRight animated animated" data-wow-delay="0.4s">
			<h4>Welcome to MobiTax GST</h4>
			<br>
			<h4>Reset Your Passord</h4>
			<form id="resetForm" role="form">
				<input type="hidden" class="form-control" id="user_id"  value="{{$data['data'][0]->user_id}}">
				<input type="password" name="password" id="password" placeholder="Password" required>
				<input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
				<button type="button" id="resetButton" class="btn btn-block btn-info">Reset</button>
			</form>
		</div>
		@else
		<div class="col-md-6 benefits-grid1 wow fadeInRight animated animated" data-wow-delay="0.4s">
		<h4>This reset password link has expired.</h4>
		</div>
		@endif
		<div class="clearfix"></div>
	</div>
</div>
</div>

<script src="{{URL::asset('app/js/account.js')}}"></script>

@endsection