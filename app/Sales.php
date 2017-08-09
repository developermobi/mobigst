<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
DB::enableQueryLog();


class Sales extends Model{


	public static function salesInvoiceData($gstin_id){
		$from_date = date('Y')."-04-01";
		$to_date = date('Y', strtotime('+1 years'))."-03-31";
		$salesInvoiceData = DB::table('sales_invoice')
		->select('sales_invoice.*')
		->where('sales_invoice.gstin_id',$gstin_id)
		->where('sales_invoice.status',1)
		->whereBetween('sales_invoice.created_date', [$from_date, $to_date])
		->get();

		return $salesInvoiceData;
	}



	public static function getBusinessByGstin($gstin_id){

		$business = DB::table('gstin')
		->select('business_id')
		->where('gstin_id',$gstin_id)
		->where('status',1)
		->get();

		return $business;
	}



	public static function getContact($business_id){

		$contact = DB::table('contact')
		->select('contact_id','contact_name')
		->where('business_id',$business_id)
		->where('status',1)
		->get();

		return $contact;
	}



	public static function getStates(){

		$states = DB::table('states')
		->get();
		return $states;
	}



	public static function getContactInfo($contact_id){

		$getContactInfo = DB::table('contact')
		->where('contact_id',$contact_id)
		->where('status',1)
		->get();

		return $getContactInfo;
	}



	public static function getItem($business_id){

		$item = DB::table('item')
		->select('item_id','item_description')
		->where('business_id',$business_id)
		->where('status',1)
		->get();

		return $item;
	}



	public static function insertSalesInvoice($salesInvoiceData){

		$salesInvoiceData['created_at'] = date('Y-m-d H:i:s');
		$insertSalesInvoice = DB::table('sales_invoice')
		->insertGetId($salesInvoiceData);

		return $insertSalesInvoice;
	}



	public static function insertInvoiceDetails($invoiceDetailData){
		
		$invoiceDetailData['created_at'] = date('Y-m-d H:i:s');
		$insertInvoiceDetails = DB::table('invoice_details')
		->insertGetId($invoiceDetailData);

		return $insertInvoiceDetails;
	}

}