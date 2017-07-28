$(function(){

	if (typeof $.cookie('token') === 'undefined' && typeof $.cookie('tokenId') === 'undefined'){
		window.location.href = SERVER_NAME;
	}

	$('#addBusinessButton').click(function(){
		addBusiness();
		//alert();
	});

	$('#addGstinButton').click(function(){
		addGstin();
	});

	/*jQuery.validator.addMethod("gstin", function(value, element) {
		return this.optional( element ) || /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/.test( value );
	}, 'Please enter a valid gstin number.');*/

	$("#businessForm").validate({
		rules: {    
			name:{
				required: true,
			},
			pan_no:{
				required: true,
			},
			gstin_no:{
				required: true,
				//gstin:true,
			},
			display_name:{
				required: true,
			},
		},
		messages: {    
			name:"Please enter password.",
			pan_no:"Please enter pan no.",
			gstin_no:"Please enter valid gstin no.",
			display_name:"Please enter display name.",
		}
	});


	$("#gstinForm").validate({
		rules: {    
			gstin_no:{
				required: true,
			},
			display_name:{
				required: true,
			},
		},
		messages: {    
			gstin_no:"Please enter valid gstin no.",
			display_name:"Please enter display name.",
		}
	});

});


function addBusiness(){

	var flag = true;
	flag = $("#businessForm").valid();
	if(flag==false){
		return false;
	}

	var data = JSON.stringify($("#businessForm").serializeFormJSON());

	$.ajax({
		"async": true,
		"crossDomain": true,
		"url": SERVER_NAME+"/api/addBusiness",
		type:"POST",

		"headers": {
			"content-type": "application/json",
			"cache-control": "no-cache",
			"postman-token": "5d6d42d9-9cdb-e834-6366-d217b8e77f59"
		},
		"processData": false,
		"data":data,
		"dataType":"JSON",
		beforeSend:function(){
			$("#addBusinessButton").prop('disabled', true).text('Adding. Please Wait...');
		},
		success:function(response){
			if(response.code == 201){
				swal({
					title: "Success !",
					text: response.message,
					type: "success",
					confirmButtonText: "OK",
					width:'400px',
				}).then(function () {
					window.location.href = window.location.href;
				});
			}else{
				swal({
					title: "Failed!",
					text: response.message,
					type: "error",
					confirmButtonText: "Close",
				});
			}
		},
		complete:function(){
			$("#addBusinessButton").prop('disabled', false).text('Add');
		}
	});
}



function addGstin(){

	var flag = true;
	flag = $("#gstinForm").valid();
	if(flag==false){
		return false;
	}

	var data = JSON.stringify($("#gstinForm").serializeFormJSON());

	$.ajax({
		"async": true,
		"crossDomain": true,
		"url": SERVER_NAME+"/api/addGstin",
		type:"POST",

		"headers": {
			"content-type": "application/json",
			"cache-control": "no-cache",
			"postman-token": "5d6d42d9-9cdb-e834-6366-d217b8e77f59"
		},
		"processData": false,
		"data":data,
		"dataType":"JSON",
		beforeSend:function(){
			$("#addBusinessButton").prop('disabled', true).text('Adding. Please Wait...');
		},
		success:function(response){
			if(response.code == 201){
				swal({
					title: "Success !",
					text: response.message,
					type: "success",
					confirmButtonText: "OK",
					width:'400px',
				}).then(function () {
					window.location.href = window.location.href;
				});
			}else{
				swal({
					title: "Failed!",
					text: response.message,
					type: "error",
					confirmButtonText: "Close",
				});
			}
		},
		complete:function(){
			$("#addBusinessButton").prop('disabled', false).text('Add');
		}
	});
}