$(function(){

	$("#tt_taxable_value").val('');
	$("#tt_taxable_value").prop('disabled', true);
	$("#tt_cgst_amount").val('');
	$("#tt_cgst_amount").prop('disabled', true);
	$("#tt_sgst_amount").val('');
	$("#tt_sgst_amount").prop('disabled', true);
	$("#tt_igst_amount").val('');
	$("#tt_igst_amount").prop('disabled', true);
	$("#tt_cess_amount").val('');
	$("#tt_cess_amount").prop('disabled', true);
	$("#tt_total").val('');
	$("#tt_total").prop('disabled', true);

	var business_id = $("#business_id").val();

	getStates();
	getContact(business_id);

	if (typeof $.cookie('token') === 'undefined' && typeof $.cookie('tokenId') === 'undefined'){
		window.location.href = SERVER_NAME;
	}

	$(".item_name").change(function(event){
		var place_of_supply = $("#place_of_supply").val();
		/*if(place_of_supply == ''){
			alert('Please select place of supply first');
			$(".item_name").val('');
			$(".item_name").prop('disabled', true);
		}*/
	});

	$(".place_of_supply").change(function(event){
		var place_of_supply = $("#place_of_supply").val();
		if(place_of_supply != ''){
			$(".item_name").prop('disabled', false);
		}else{
			alert('Please select place of supply first');
			$(".item_name").prop('disabled', true);
		}

		var customer_state = $("#customer_state").val();
		if(place_of_supply == customer_state){
			$(".cgst_percentage").val('');
			$(".cgst_percentage").prop('disabled', false);
			$(".cgst_amount").val('');
			$(".cgst_amount").prop('disabled', false);
			$(".sgst_percentage").val('');
			$(".sgst_percentage").prop('disabled', false);
			$(".sgst_amount").val('');
			$(".sgst_amount").prop('disabled', false);
			$(".igst_percentage").val('');
			$(".igst_percentage").prop('disabled', true);
			$(".igst_amount").val('');
			$(".igst_amount").prop('disabled', true);
		}else{
			$(".cgst_percentage").val('');
			$(".cgst_percentage").prop('disabled', true);
			$(".cgst_amount").val('');
			$(".cgst_amount").prop('disabled', true);
			$(".sgst_percentage").val('');
			$(".sgst_percentage").prop('disabled', true);
			$(".sgst_amount").val('');
			$(".sgst_amount").prop('disabled', true);
			$(".igst_percentage").val('');
			$(".igst_percentage").prop('disabled', false);
			$(".igst_amount").val('');
			$(".igst_amount").prop('disabled', false);
		}
	});

	$("#same_address").change(function(event){
		if (this.checked){
			var sh_address = $("#bill_address").val();
			var sh_pincode = $("#bill_pincode").val();
			var sh_city = $("#bill_city").val();
			var sh_state = $("#bill_state").val();
			var sh_country = $("#bill_country").val();
			$("#sh_address").val(sh_address);
			$("#sh_pincode").val(sh_pincode);
			$("#sh_city").val(sh_city);
			$("#sh_state").val(sh_state);
			$("#sh_country").val(sh_country);
		} else {
			$("#sh_address").val("");
			$("#sh_pincode").val("");
			$("#sh_city").val("");
			$("#sh_state").val("");
			$("#sh_country").val("");
		}
	});

	$('#advance_setting').change(function() {
		if ($(this).is(':checked')) {
			$("#tt_taxable_value").val('');
			$("#tt_taxable_value").prop('disabled', false);
			$("#tt_cgst_amount").val('');
			$("#tt_cgst_amount").prop('disabled', false);
			$("#tt_sgst_amount").val('');
			$("#tt_sgst_amount").prop('disabled', false);
			$("#tt_igst_amount").val('');
			$("#tt_igst_amount").prop('disabled', false);
			$("#tt_cess_amount").val('');
			$("#tt_cess_amount").prop('disabled', false);
			$("#tt_total").val('');
			$("#tt_total").prop('disabled', false);
		} else {
			$("#tt_taxable_value").val('');
			$("#tt_taxable_value").prop('disabled', true);
			$("#tt_cgst_amount").val('');
			$("#tt_cgst_amount").prop('disabled', true);
			$("#tt_sgst_amount").val('');
			$("#tt_sgst_amount").prop('disabled', true);
			$("#tt_igst_amount").val('');
			$("#tt_igst_amount").prop('disabled', true);
			$("#tt_cess_amount").val('');
			$("#tt_cess_amount").prop('disabled', true);
			$("#tt_total").val('');
			$("#tt_total").prop('disabled', true);
		}
	});

	$('#save_invoice').click(function(){
		saveSalesInvoice();
	});

});


function getContact(business_id){

	$.ajax({
		"async": true,
		"crossDomain": true,
		"url": SERVER_NAME+"/api/getContact/"+business_id,
		"method": "GET",
		"headers": {
			"cache-control": "no-cache",
			"postman-token": "5d6d42d9-9cdb-e834-6366-d217b8e77f59"
		},
		"processData": false,
		"dataType":"JSON",                
		beforeSend:function(){
		},
		success:function(response){
			var data = response['data'];
			var option = "<option value='' selected></option>";
			if(data.length > 0){
				$.each(data, function(i, item) {
					option += "<option value='"+data[i]['contact_name']+"' data-attr='"+data[i]['contact_id']+"'>"+data[i]['contact_name']+"</option>";
				});
			}
			$(".contact_name").html('');
			$(".contact_name").append(option);
		},
		complete:function(){
		}
	}); 
}



function getStates(){

	$.ajax({
		"async": true,
		"crossDomain": true,
		"url": SERVER_NAME+"/api/getStates",
		"method": "GET",
		"headers": {
			"cache-control": "no-cache",
			"postman-token": "5d6d42d9-9cdb-e834-6366-d217b8e77f59"
		},
		"processData": false,
		"dataType":"JSON",                
		beforeSend:function(){
		},
		success:function(response){
			var data = response['data'];
			var option = "<option value=''></option>";
			if(data.length > 0){
				$.each(data, function(i, item) {
					option += "<option value='"+data[i]['state_name']+"'>"+data[i]['state_name']+"</option>";
				});
			}
			$(".place_of_supply").html('');
			$(".place_of_supply").append(option);
		},
		complete:function(){
		}
	}); 
}



function getContactInfo(obj){
	
	var contact_id = $(obj).find(':selected').attr('data-attr');
	
	$.ajax({
		"async": false,
		"crossDomain": true,
		"url": SERVER_NAME+"/api/getContactInfo/"+contact_id,
		"method": "GET",
		"dataType":"JSON",
		beforeSend:function(){
			$("#subcity").html("");
		},
		success:function(response){
			if(response.code == 302){
				$("#bill_address").val(response.data[0]['address']);
				$("#bill_pincode").val(response.data[0]['pincode']);
				$("#bill_city").val(response.data[0]['city']);
				$("#bill_state").val(response.data[0]['state']);
				$("#bill_country").val(response.data[0]['country']);
				$("#contact_gstin").val(response.data[0]['gstin_no']);
				$("#place_of_supply").val(response.data[0]['state']);
				$("#customer_state").val(response.data[0]['state']);

				$(".cgst_percentage").val('');
				$(".cgst_percentage").prop('disabled', false);
				$(".cgst_amount").val('');
				$(".cgst_amount").prop('disabled', false);
				$(".sgst_percentage").val('');
				$(".sgst_percentage").prop('disabled', false);
				$(".sgst_amount").val('');
				$(".sgst_amount").prop('disabled', false);
				$(".igst_percentage").val('');
				$(".igst_percentage").prop('disabled', true);
				$(".igst_amount").val('');
				$(".igst_amount").prop('disabled', true);
			}
		},
		complete:function(){
		}
	});
}



function getItem(business_id){

	$.ajax({
		"async": true,
		"crossDomain": true,
		"url": SERVER_NAME+"/api/getItem/"+business_id,
		"method": "GET",
		"headers": {
			"cache-control": "no-cache",
			"postman-token": "5d6d42d9-9cdb-e834-6366-d217b8e77f59"
		},
		"processData": false,
		"dataType":"JSON",                
		beforeSend:function(){
		},
		success:function(response){
			var data = response['data'];
			var option = "<option value=''></option>";
			if(data.length > 0){
				$.each(data, function(i, item) {
					option += "<option value='"+data[i]['item_description']+"' data-attr='"+data[i]['item_id']+"'>"+data[i]['item_description']+"</option>";
				});
			}
			//$(".item_name").html('');
			$(".item_name").append(option);

		},
		complete:function(){
		}
	}); 
}



function getItemInfo(obj){
	
	var item_id = $(obj).find(':selected').attr('data-attr');
	/*alert(abc);
	return false;*/
	
	$.ajax({
		"async": false,
		"crossDomain": true,
		"url": SERVER_NAME+"/api/getItemInfo/"+item_id,
		"method": "GET",
		"dataType":"JSON",
		beforeSend:function(){
			$("#subcity").html("");
		},
		success:function(response){
			console.log(response);
			//return false;
			var rate = $(obj).closest("tr").find("#rate");
			var hsn_sac_no = $(obj).closest("tr").find("#hsn_sac_no");
			var total = $(obj).closest("tr").find("#total");
			//var discount = $(obj).closest("tr").find("#discount");
			if(response.code == 302){
				$(hsn_sac_no).val(response.data[0]['item_hsn_sac']);
				$(rate).val(response.data[0]['item_sale_price']);
				$(total).val(response.data[0]['item_sale_price']);
				//$(discount).val(response.data[0]['item_discount']);
			}
		},
		complete:function(){
		}
	});
}



function calCgstAmount(obj){

	var rate_element = $(obj).closest("tr").find(".rate");
	var rate = rate_element.val();
	
	var cgst_percentage_element = $(obj).closest("tr").find(".cgst_percentage");
	var cgst_percentage = cgst_percentage_element.val();

	var cgst_amount_element = $(obj).closest("tr").find(".cgst_amount");
	var cgst_amount = (rate / 100) * cgst_percentage;
	cgst_amount_element.val(cgst_amount);

	var sgst_percentage_element = $(obj).closest("tr").find(".sgst_percentage");
	var sgst_percentage = sgst_percentage_element.val();

	var sgst_amount_element = $(obj).closest("tr").find(".sgst_amount");
	var sgst_amount = (rate / 100) * sgst_percentage;
	sgst_amount_element.val(sgst_amount);

	var cess_percentage_element = $(obj).closest("tr").find(".cess_percentage");
	var cess_percentage = cess_percentage_element.val();

	var cess_amount_element = $(obj).closest("tr").find(".cess_amount");
	var cess_amount = (rate / 100) * cess_percentage;
	cess_amount_element.val(cess_amount);

	var total_element = $(obj).closest("tr").find(".total");

	total_element.val(parseInt(rate) + parseInt(cgst_amount) + parseInt(sgst_amount) + + parseInt(cess_amount));

	calculateTotal(obj);

	/*var rate_sum = 0;
	$(".rate").each(function(){
		rate_sum = rate_sum + parseInt($(this).val());
	});

	var cgst_amount_sum = 0;
	$(".cgst_amount").each(function(){
		cgst_amount_sum = cgst_amount_sum + parseInt($(this).val());
	});

	var sgst_amount_sum = 0;
	$(".sgst_amount").each(function(){
		sgst_amount_sum = sgst_amount_sum + parseInt($(this).val());
	});

	$("#total_amount").val(parseInt(cgst_amount_sum) + parseInt(sgst_amount_sum) + parseInt(rate_sum));
	$("#grand_total").val(parseInt(cgst_amount_sum) + parseInt(sgst_amount_sum) + parseInt(rate_sum));*/
}



function calculateCESS(obj){
	var rate_element = $(obj).closest("tr").find(".rate");
	var rate = rate_element.val();

	var cess_percentage = $(obj).closest("tr").find(".cess_percentage").val();
	if(cess_percentage != ''){

		var amount_element = $(obj).closest("tr").find(".cess_amount");
		var amount = (rate / 100) * cess_percentage;
		amount_element.val(amount);

		calculateTotal(obj);
	}
}



function calculateTotal(obj){
	var rate_sum = 0;
	$(".rate").each(function(){
		rate_sum = rate_sum + parseInt($(this).val());
	});

	var cgst_amount_sum = 0;
	$(".cgst_amount").each(function(){
		cgst_amount_sum = cgst_amount_sum + parseInt($(this).val());
	});

	var sgst_amount_sum = 0;
	$(".sgst_amount").each(function(){
		sgst_amount_sum = sgst_amount_sum + parseInt($(this).val());
	});

	var cess_amount_sum = 0;
	$(".cess_amount").each(function(){
		cess_amount_sum = cess_amount_sum + parseInt($(this).val());
	});

	$("#total_amount").val(parseInt(cgst_amount_sum) + parseInt(sgst_amount_sum) + parseInt(cess_amount_sum) + parseInt(rate_sum));
	$("#grand_total").val(parseInt(cgst_amount_sum) + parseInt(sgst_amount_sum) + parseInt(cess_amount_sum) + parseInt(rate_sum));
}



function saveSalesInvoice(){

	/*var flag = true;
	flag = $("#signupForm").valid();
	if(flag==false){
		return false;
	}

	var password = $('#password').val();
	var confirm_password = $('#confirm_password').val();

	if(password != confirm_password){
		swal({
			text : 'Password and confirm password should be same.',
			type : 'warning'
		});
		return false;
	}*/

	/*alert();
	return false;*/

	var data = JSON.stringify($("#invoiceForm").serializeFormJSON());
	
	$.ajax({
		"async": true,
		"crossDomain": true,
		"url": SERVER_NAME+"/api/saveSalesInvoice",
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
			$("#register").prop('disabled', true).text('Please Wait...');
		},
		success:function(response){
			if(response.code == 201){
				swal({
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
			$("#register").prop('disabled', false).text('Join Now');
		}
	});
}