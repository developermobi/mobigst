@extends('gst.layouts.main')

@section('title', 'MobiTAX GST')

@section('content')

<style type="text/css">
	a:hover, a:link{
		text-decoration: none;
		color: #fff;
	}
</style>

<div class="train w3-agile">
	<div class="container">
		<h2>Import Contacts</h2>
		<div class="train-grids">
			<div class="latest-top" >
				<div class="train-grid wow fadeInLeft animated animated" data-wow-delay="0.4s">
					<div class="col-md-6 about-grid1 wow fadeInLeft animated animated" data-wow-delay="0.4s">
						<p>Select Business </p>
						<select class="selectpicker form-control" style="width:60%">
							<option>Mobisoft Technology Pvt. Ltd.</option>
							<option>Sarveshwar Co.</option>
							<option>MobiTax</option>
						</select> 
					</div>
					<div class="col-md-6 about-grid1 wow fadeInLeft animated animated" data-wow-delay="0.4s">
						<p>To know the structure of <strong>Contact Master</strong> </p>
						<a href="https://d494qy7qcliw5.cloudfront.net/cleargst-templates/contact_master_template.xlsx" download="file_name">
							<button class="btn btn-success" type="button">Download Sample</button>
						</a>
					</div>	
					<img src="images/web_grey.png"/>
					<h4>Drag & Drop</h4>
					<p>Import your contact or <a href="trial.html"> browse</a></p>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

@endsection