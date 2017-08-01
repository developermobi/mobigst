@extends('gst.layouts.main')

@section('title', 'MobiTAX GST')

@section('content')

<style type="text/css">
	a:hover, a:link{
		text-decoration: none;
		color: #fff;
	}
	.image-upload > input{
		display: none;
	}
	.image-upload img{
		width: 80px;
		cursor: pointer;
	}
</style>

<div class="train w3-agile">
	<div class="container">
		<div class="train-grids">
			<div class="latest-top" >
				<div class="row"  style="padding: 200px 0px;">
					<center>
						@if($data['code'] == '200')
						<div class="alert alert-success">
							<strong>Success!</strong> {{$data['numbers']}} items added successfully. Wailt while we redirect you to another page...
						</div>
						@else
						<div class="alert alert-danger">
							<strong>Fail!</strong> Something went wrong while adding items. Please try again.
						</div>
						@endif
					</center>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var delay = 2000; 
	setTimeout(function(){ window.location = SERVER_NAME+"/importitem"; }, delay);
</script>

@endsection