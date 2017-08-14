<?php

namespace App\Http\Controllers\Api\V1;
use Illuminate\Http\Request;
use App\Http\Requests;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use League\Csv\Reader;
use File;
use Mail;
use App\Sales;
use Session;
use View;
use DB;


class SalesController extends Controller{


	public function sales($id){
		$gstin_id = decrypt($id);

		$salesInvoiceData = Sales::salesInvoiceData($gstin_id);
		if(sizeof($salesInvoiceData) > 0){
			$totalSGST = 0;
			$totalCGST = 0;
			$totalIGST = 0;
			$totalCESS = 0;
			$totalValue = 0;
			foreach ($salesInvoiceData as $key => $value) {
				$totalSGST += $value->total_sgst_amount;
				$totalCGST += $value->total_cgst_amount;
				$totalIGST += $value->total_igst_amount;
				$totalCESS += $value->total_cess_amount;
				$totalValue += $value->total_amount;
			}
			$total = array();
			$total['totalTransactions'] = sizeof($salesInvoiceData);
			$total['totalSGST'] = $totalSGST;
			$total['totalCGST'] = $totalCGST;
			$total['totalIGST'] = $totalIGST;
			$total['totalCESS'] = $totalCESS;
			$total['totalValue'] = $totalValue;

			$data = array();
			$data['total'] = $total;
			$data['salesInvoiceData'] = $salesInvoiceData;

			$returnResponse['status'] = "success";
			$returnResponse['code'] = "302";
			$returnResponse['gstin_id'] = $id;
			$returnResponse['message'] = "All Transactions.";
			$returnResponse['data'] = $data;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['gstin_id'] = $id;
			$returnResponse['message'] = "No Data Found.";
			$returnResponse['data'] = '';
		}
		return view('sales.sales')->with('data', $returnResponse);
	}



	public function selectSalesInvoice($id){
		$gstin_id = decrypt($id);
		return view('sales.selectSalesInvoice')->with('data', $gstin_id);
	}



	public function goodsSalesInvoice($id){
		$gstin_id = decrypt($id);

		$data = array();
		$getBusinessByGstin = Sales::getBusinessByGstin($gstin_id);
		$getInvoiceCount = Sales::getInvoiceCount($gstin_id);

		if(sizeof($getInvoiceCount) > 0){
			$data['invoice_no'] = "INV".($getInvoiceCount[0]->count + 1);
		}else{
			$data['invoice_no'] = "INV1";
		}

		if(sizeof($getBusinessByGstin) > 0){
			$data['gstin_id'] = $gstin_id;
			$data['business_id'] = $getBusinessByGstin[0]->business_id;
		}
		return view('sales.goodsSalesInvoice')->with('data', $data);
	}



	public function getContact($business_id){

		$getContact = Sales::getContact($business_id);
		if(sizeof($getContact) > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "302";
			$returnResponse['message'] = "Data Found.";
			$returnResponse['data'] = $getContact;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "No Content.";
			$returnResponse['data'] = $getContact;
		}
		return $returnResponse;
	}



	public function getStates(){

		$getStates = Sales::getStates();
		if(sizeof($getStates) > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "302";
			$returnResponse['message'] = "Data Found.";
			$returnResponse['data'] = $getStates;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "No Content.";
			$returnResponse['data'] = $getStates;
		}
		return $returnResponse;
	}



	public function getContactInfo($contact_id){

		$getContactInfo = Sales::getContactInfo($contact_id);
		if(sizeof($getContactInfo) > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "302";
			$returnResponse['message'] = "Data Found.";
			$returnResponse['data'] = $getContactInfo;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "No Content.";
			$returnResponse['data'] = $getContactInfo;
		}
		return $returnResponse;
	}



	public function getItem($business_id){

		$getItem = Sales::getItem($business_id);
		if(sizeof($getItem) > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "302";
			$returnResponse['message'] = "Data Found.";
			$returnResponse['data'] = $getItem;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "No Content.";
			$returnResponse['data'] = $getItem;
		}
		return $returnResponse;
	}



	public function getItemInfo($item_id){

		$getItemInfo = Sales::getItemInfo($item_id);
		if(sizeof($getItemInfo) > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "302";
			$returnResponse['message'] = "Data Found.";
			$returnResponse['data'] = $getItemInfo;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "No Content.";
			$returnResponse['data'] = $getItemInfo;
		}
		return $returnResponse;
	}



	public function saveSalesInvoice(Request $request){
		$input = $request->all();

		$salesInvoiceData = array();
		$salesInvoiceData['gstin_id'] = $input['gstin_id'];
		$salesInvoiceData['invoice_no'] = $input['invoice_no'];
		$salesInvoiceData['invoice_date'] = $input['invoice_date'];
		$salesInvoiceData['reference'] = $input['reference'];
		$salesInvoiceData['contact_gstin'] = $input['contact_gstin'];
		$salesInvoiceData['place_of_supply'] = $input['place_of_supply'];
		$salesInvoiceData['due_date'] = $input['due_date'];
		$salesInvoiceData['contact_name'] = $input['contact_name'];
		$salesInvoiceData['bill_address'] = $input['bill_address'];
		$salesInvoiceData['bill_pincode'] = $input['bill_pincode'];
		$salesInvoiceData['bill_city'] = $input['bill_city'];
		$salesInvoiceData['bill_state'] = $input['bill_state'];
		$salesInvoiceData['bill_country'] = $input['bill_country'];
		$salesInvoiceData['sh_address'] = $input['sh_address'];
		$salesInvoiceData['sh_pincode'] = $input['sh_pincode'];
		$salesInvoiceData['sh_city'] = $input['sh_city'];
		$salesInvoiceData['sh_state'] = $input['sh_state'];
		$salesInvoiceData['sh_country'] = $input['sh_country'];
		$salesInvoiceData['total_discount'] = $input['total_discount'];
		$salesInvoiceData['total_cgst_amount'] = isset($input['total_cgst_amount']) ? $input['total_cgst_amount'] : "0";
		$salesInvoiceData['total_sgst_amount'] = isset($input['total_sgst_amount']) ? $input['total_sgst_amount'] : "0";
		$salesInvoiceData['total_igst_amount'] = isset($input['total_igst_amount']) ? $input['total_igst_amount'] : "0";
		$salesInvoiceData['total_cess_amount'] = isset($input['total_cess_amount']) ? $input['total_cess_amount'] : "0";
		$salesInvoiceData['total_amount'] = $input['total_amount'];
		$salesInvoiceData['tt_taxable_value'] = isset($input['tt_taxable_value']) ? $input['tt_taxable_value'] : "0";
		$salesInvoiceData['tt_cgst_amount'] = isset($input['tt_cgst_amount']) ? $input['tt_cgst_amount'] : "0";
		$salesInvoiceData['tt_sgst_amount'] = isset($input['tt_sgst_amount']) ? $input['tt_sgst_amount'] : "0";
		$salesInvoiceData['tt_igst_amount'] = isset($input['tt_igst_amount']) ? $input['tt_igst_amount'] : "0";
		$salesInvoiceData['tt_cess_amount'] = isset($input['tt_cess_amount']) ? $input['tt_cess_amount'] : "0";
		$salesInvoiceData['tt_total'] = isset($input['tt_total']) ? $input['tt_total'] : "0";
		$salesInvoiceData['grand_total'] = $input['grand_total'];
		
		$insertSalesInvoice = Sales::insertSalesInvoice($salesInvoiceData);
		if($insertSalesInvoice > 0){
			$invoiceDetailData = array();

			if(is_array($input['total'])){
				foreach ($input['total'] as $key => $value) {
					$invoiceDetailData['invoice_no'] = $insertSalesInvoice;
					$invoiceDetailData['invoice_type'] = '1';
					$invoiceDetailData['item_name'] = $input['item_name'][$key];
					$invoiceDetailData['item_type'] = "Goods";
					$invoiceDetailData['hsn_sac_no'] = $input['hsn_sac_no'][$key];
					$invoiceDetailData['quantity'] = $input['quantity'][$key];
					$invoiceDetailData['rate'] = $input['rate'][$key];
					$invoiceDetailData['discount'] = $input['discount'][$key];
					$invoiceDetailData['cgst_percentage'] = isset($input['cgst_percentage'][$key]) ? $input['cgst_percentage'][$key] : "0";
					$invoiceDetailData['cgst_amount'] = isset($input['cgst_amount'][$key]) ? $input['cgst_amount'][$key] : "0";
					$invoiceDetailData['sgst_percentage'] = isset($input['sgst_percentage'][$key]) ? $input['sgst_percentage'][$key] : "0";
					$invoiceDetailData['sgst_amount'] = isset($input['sgst_amount'][$key]) ? $input['sgst_amount'][$key] : "0";
					$invoiceDetailData['igst_percentage'] = isset($input['igst_percentage'][$key]) ? $input['igst_percentage'][$key] : "0";
					$invoiceDetailData['igst_amount'] = isset($input['igst_amount'][$key]) ? $input['igst_amount'][$key] : "0";
					$invoiceDetailData['cess_percentage'] = isset($input['cess_percentage'][$key]) ? $input['cess_percentage'][$key] : "0";
					$invoiceDetailData['cess_amount'] = isset($input['cess_amount'][$key]) ? $input['cess_amount'][$key] : "0";
					$invoiceDetailData['total'] = $input['total'][$key];
					$insertInvoiceDetails = Sales::insertInvoiceDetails($invoiceDetailData);
				}
				$returnResponse['status'] = "success";
				$returnResponse['code'] = "201";
				$returnResponse['message'] = "Invoice created successfully.";
				$returnResponse['data'] = $insertSalesInvoice;
				return $returnResponse;
			}else{
				$invoiceDetailData['invoice_no'] = $insertSalesInvoice;
				$invoiceDetailData['invoice_type'] = '1';
				$invoiceDetailData['item_name'] = $input['item_name'];
				$invoiceDetailData['item_type'] = "Goods";
				$invoiceDetailData['hsn_sac_no'] = $input['hsn_sac_no'];
				$invoiceDetailData['quantity'] = $input['quantity'];
				$invoiceDetailData['rate'] = $input['rate'];
				$invoiceDetailData['discount'] = $input['discount'];
				$invoiceDetailData['cgst_percentage'] = isset($input['cgst_percentage'][$key]) ? $input['cgst_percentage'][$key] : "0";
				$invoiceDetailData['cgst_amount'] = isset($input['cgst_amount'][$key]) ? $input['cgst_amount'][$key] : "0";
				$invoiceDetailData['sgst_percentage'] = isset($input['sgst_percentage'][$key]) ? $input['sgst_percentage'][$key] : "0";
				$invoiceDetailData['sgst_amount'] = isset($input['sgst_amount'][$key]) ? $input['sgst_amount'][$key] : "0";
				$invoiceDetailData['igst_percentage'] = isset($input['igst_percentage'][$key]) ? $input['igst_percentage'][$key] : "0";
				$invoiceDetailData['igst_amount'] = isset($input['igst_amount'][$key]) ? $input['igst_amount'][$key] : "0";
				$invoiceDetailData['cess_percentage'] = isset($input['cess_percentage'][$key]) ? $input['cess_percentage'][$key] : "0";
				$invoiceDetailData['cess_amount'] = isset($input['cess_amount'][$key]) ? $input['cess_amount'][$key] : "0";
				$invoiceDetailData['total'] = $input['total'];
				$insertInvoiceDetails = Sales::insertInvoiceDetails($invoiceDetailData);

				$returnResponse['status'] = "success";
				$returnResponse['code'] = "201";
				$returnResponse['message'] = "Invoice created successfully.";
				$returnResponse['data'] = $insertSalesInvoice;
				return $returnResponse;
			}
		}else{
			$returnResponse['status'] = "failed";
			$returnResponse['code'] = "400";
			$returnResponse['message'] = "Error while creating invoice. Please try again.";
			$returnResponse['data'] = $insertSalesInvoice;
			return $returnResponse;
		}
		return $returnResponse;
	}


}
