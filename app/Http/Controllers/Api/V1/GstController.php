<?php

namespace App\Http\Controllers\Api\V1;
use Illuminate\Http\Request;
use App\Http\Requests;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use League\Csv\Reader;
use File;
use App\Gst;
use Session;
use View;
use DB;


class GstController extends Controller{



	public function signup(Request $request){
		$input = $request->all();

		$data = array();
		$data['email'] = $input['email'];
		$data['password'] = $input['password'];

		$mail_available = Gst::mail_available($input['email']);
		
		if(sizeof($mail_available) > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "302";
			$returnResponse['message'] = "Email address already exists. Please enter another email address. ";
			$returnResponse['data'] = $mail_available;
		}else{
			$addUser = Gst::addUser($data);

			if($addUser > 0){
				$returnResponse['status'] = "success";
				$returnResponse['code'] = "201";
				$returnResponse['message'] = "You have signed up Sucessfully.";
				$returnResponse['data'] = $addUser;
			}else{
				$returnResponse['status'] = "failed";
				$returnResponse['code'] = "302";
				$returnResponse['message'] = "Error while signing up. Please try again.";
				$returnResponse['data'] = $addUser;
			}
		}
		return response()->json($returnResponse);
	}



	public function viewUsers(){
		$viewUsers = Gst::viewUsers();

		if($viewUsers > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "All users.";
			$returnResponse['data'] = $viewUsers;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "No users found.";
			$returnResponse['data'] = $viewUsers;
		}
		return response()->json($returnResponse);
	}



	public function updateUser(Request $request, $id){
		$input = $request->all();

		$updateUser = Gst::updateUser($input,$id);

		if($updateUser > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "User updated successfully.";
			$returnResponse['data'] = $updateUser;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "Not updated.";
			$returnResponse['data'] = $updateUser;
		}
		return response()->json($returnResponse);
	}



	public function login(Request $request){
		$input = $request->all();

		$login = Gst::login($input);

		if(sizeof($login) > 0){
			Session::regenerate();

			$data['remember_token'] = Session::getId();
			$data['user_id'] = $login[0]->user_id; 

			$UpdateToken = Gst::updateUserToken($data);

			if($UpdateToken > 0){
				$login = Gst::login($input);

				$returnResponse['status'] = "success";
				$returnResponse['code'] = "200";
				$returnResponse['message'] = "You have logged in Sucessfully.";
				$returnResponse['data'] = $login;
			}else{
				$returnResponse['status'] = "failed";
				$returnResponse['code'] = "204";
				$returnResponse['message'] = "Session not generated. Please try again.";
				$returnResponse['data'] = $login;
			}
			return response()->json($returnResponse);
		}else{
			$returnResponse['status'] = "failed";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "Email or password is incorrect.";
			$returnResponse['data'] = $login;
		}
		return response()->json($returnResponse);
	}



	public function logout(Request $request){
		$input = $request->all();

		$logout = Gst::logout($input);

		if(sizeof($logout) > 0){

			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "You have logged out Sucessfully.";
			$returnResponse['data'] = $logout;
		}else{
			$returnResponse['status'] = "failed";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "Something went wrong. Plaese try again.";
			$returnResponse['data'] = $logout;
		}
		return response()->json($returnResponse);
	}



	public function business(Request $request){
		$user_id = $request->cookie('tokenId');

		$business =  Gst::business($user_id);

		if (sizeof($business) > 0) {
			foreach ($business as $key => $value) {
				$gstin = Gst::gstin($business[$key]->business_id);
				$business[$key]->details = $gstin;
			}
		}else{
			$data['status'] = "success";
			$data['code'] = "204";
			$data['message'] = "No data found.";
			$data['data'] = '';
		}
		return view('gst.business')->with('data', $business);
	}



	public function addBusiness(Request $request){
		$input = $request->all();

		$business_data = array();
		$business_data['user_id'] = $request->cookie('tokenId');
		$business_data['name'] = $input['name'];
		$business_data['pan'] = $input['pan_no'];

		$addBusiness = Gst::addBusiness($business_data);

		if($addBusiness > 0){

			$gstin_data = array();
			$gstin_data['business_id'] = $addBusiness;
			$gstin_data['gstin_no'] = $input['gstin_no'];
			$gstin_data['display_name'] = $input['display_name'];

			$addBusiness = Gst::addGstin($gstin_data);

			if($addBusiness > 0){
				$returnResponse['status'] = "success";
				$returnResponse['code'] = "201";
				$returnResponse['message'] = "Business added Sucessfully.";
				$returnResponse['data'] = $addBusiness;
			}else{
				$returnResponse['status'] = "failed";
				$returnResponse['code'] = "302";
				$returnResponse['message'] = "Error while adding business. Please try again.";
				$returnResponse['data'] = $addBusiness;
			}
		}else{
			$returnResponse['status'] = "failed";
			$returnResponse['code'] = "302";
			$returnResponse['message'] = "Error while adding business. Please try again.";
			$returnResponse['data'] = $addUser;
		}
		return response()->json($returnResponse);
	}



	public function addGstin(Request $request){
		$input = $request->all();
		
		$addGstin = Gst::addGstin($input);

		if($addGstin > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "201";
			$returnResponse['message'] = "GSTIN number added Sucessfully.";
			$returnResponse['data'] = $addGstin;
		}else{
			$returnResponse['status'] = "failed";
			$returnResponse['code'] = "302";
			$returnResponse['message'] = "Error while adding gstin no. Please try again.";
			$returnResponse['data'] = $addGstin;
		}
		return response()->json($returnResponse);
	}



	public function setting(Request $request){
		$user_id = $request->cookie('tokenId');

		$business =  Gst::business($user_id);

		if (sizeof($business) > 0) {
			$data['status'] = "success";
			$data['code'] = "200";
			$data['message'] = "Data found.";
			$data['data'] = $business;
		}else{
			$data['status'] = "success";
			$data['code'] = "204";
			$data['message'] = "No data found.";
			$data['data'] = '';
		}
		
		return view('gst.setting')->with('data', $data);
	}



	public function getBusinessData($id){
		$getData = Gst::getBusinessData($id);

		if (sizeof($getData) > 0) {
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "Data found.";
			$returnResponse['data'] = $getData;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "No data found.";
			$returnResponse['data'] = $getData;
		}
		return response()->json($returnResponse);
	}



	public function updateBusiness(Request $requestData,$id){
		$input = $requestData->all();

		$updateBusiness = Gst::updateBusiness($input,$id);

		if($updateBusiness > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "Business details updated successfully.";
			$returnResponse['data'] = $updateBusiness;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "Not updated.";
			$returnResponse['data'] = $updateBusiness;
		}
		return response()->json($returnResponse);
	}



	public function deleteBusiness($id){
		$getData = Gst::deleteBusiness($id);

		if (sizeof($getData) > 0) {
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "Business deleted successfully.";
			$returnResponse['data'] = $getData;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "Something went wrong while deleting business.";
			$returnResponse['data'] = $getData;
		}
		return response()->json($returnResponse);
	}



	public function getBusinessGstin($id){
		$business_id = decrypt($id);
		$getData = Gst::gstin($business_id);

		if (sizeof($getData) > 0) {
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "Data found.";
			$returnResponse['data'] = $getData;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "No data found.";
			$returnResponse['data'] = $getData;
		}
		return view('gst.gstin')->with('data', $returnResponse);
	}



	public function getGstinData($id){
		$getData = Gst::getGstinData($id);

		if (sizeof($getData) > 0) {
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "Data found.";
			$returnResponse['data'] = $getData;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "No data found.";
			$returnResponse['data'] = $getData;
		}
		return response()->json($returnResponse);
	}



	public function updateGstin(Request $requestData,$id){
		$input = $requestData->all();

		$updateGstin = Gst::updateGstin($input,$id);

		if($updateGstin > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "GSTIN details updated successfully.";
			$returnResponse['data'] = $updateGstin;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "Not updated.";
			$returnResponse['data'] = $updateGstin;
		}
		return response()->json($returnResponse);
	}



	public function deleteGstin($id){
		$getData = Gst::deleteGstin($id);

		if (sizeof($getData) > 0) {
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "GSTIN deleted successfully.";
			$returnResponse['data'] = $getData;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "Something went wrong while deleting GSTIN.";
			$returnResponse['data'] = $getData;
		}
		return response()->json($returnResponse);
	}



	public function getBusiness(Request $request){
		$user_id = $request->cookie('tokenId');

		$business =  Gst::business($user_id);

		if (sizeof($business) > 0) {
			$data['status'] = "success";
			$data['code'] = "200";
			$data['message'] = "Data found.";
			$data['data'] = $business;
		}else{
			$data['status'] = "success";
			$data['code'] = "204";
			$data['message'] = "No data found.";
			$data['data'] = '';
		}
		
		return response()->json($data);
	}



	public function importContactFile(Request $request){	

		$input = $request->all();
		if(isset($input['contact_csv'])){

			$file1 = $input['contact_csv'];
			
			$file_name1 = basename($file1->getClientOriginalName(), '.'.$file1->getClientOriginalExtension());
			$fileName1 = $file_name1.time().'.'.$file1->getClientOriginalExtension();
			$fileName1 = str_replace(' ', '', $fileName1);
			$fileName1 = preg_replace('/\s+/', '', $fileName1);
			$file_upload1 = $file1->move(
				base_path() . '/public/Contact/', $fileName1
				);
			$fileName['contact_csv'] = $file1;
			$input['contact_csv']=$file1;
		}
		$full_url = base_path() . '/public/Contact/'.$fileName1;
		$csv = Reader::createFromPath($full_url);

		$headers = $csv->fetchOne();

		$res = $csv->setOffset(1)->fetchAll();
		$key_value=array();
		$group_id = 1;
		$user_id = 2;
		foreach($res as $key => $val){
			$key_value[$key]['unique_id'] = $res[$key][0];
			$key_value[$key]['contact_type'] = $res[$key][1];
			$key_value[$key]['business_name'] = $res[$key][2];
			$key_value[$key]['gstin_no'] = $res[$key][3];
			$key_value[$key]['contact_person'] = $res[$key][4];
			$key_value[$key]['email'] = $res[$key][5];
			$key_value[$key]['pan_no'] = $res[$key][6];
			$key_value[$key]['phone_no'] = $res[$key][7];
			$key_value[$key]['alternate_no'] = $res[$key][8];
			$key_value[$key]['address'] = $res[$key][9];
			$key_value[$key]['city'] = $res[$key][10];
			$key_value[$key]['state'] = $res[$key][11];
			$key_value[$key]['pincode'] = $res[$key][12];
			$key_value[$key]['created_at'] = date('Y-m-d H:i:s');
		}
		$key_value;
		$start_time = date("h:i:sa");

		$collection = collect($key_value); 
		$infoFileInsertedData = Gst::addContactFromCSV($collection->toArray());  
		
		if($infoFileInsertedData){
			$response['status'] = "success";
			$response['code'] = 200;
			$response['message'] = "OK";
			$response['data'] = $infoFileInsertedData;
			$response['strat_time'] = $start_time;
			$response['end_time'] = date("h:i:sa");
			unlink($full_url);
		}else{
			$response['status'] = "fail";
			$response['code'] = 400;
			$response['message'] = "Bad Request";
			$response['data'] = $infoFileInsertedData;
			unlink($full_url);
		}
		return view('gst.importitem')->with('data', $response);
	}



	public function addCustomer(Request $request){
		$input = $request->all();

		//$addCustomer = Gst::addCustomer($input);
		//return $input['email'];
		//if($addCustomer > 0){

			if($input['email'] != ''){
				$mailInfo = array();
				/*$mailInfo['email'] = $email;
				$mailInfo['name'] = $name;
				$mailInfo['show_name'] = $showDetail[0]->name;*/

				$res = Mail::send('gst.gstinMail',['mailInfo' => $mailInfo], function($message) use ($mailInfo){

					$message->from('no-reply@mobisofttech.co.in', 'Mobi GST');
					$message->to($mailInfo['email'])->subject('MobiGST Customer Mail');
					$message->cc('prajwalweb@gmail.com');
				});
			}

			$returnResponse['status'] = "success";
			$returnResponse['code'] = "201";
			$returnResponse['message'] = "Customer added Sucessfully.";
			$returnResponse['data'] = $addCustomer;
		/*}else{
			$returnResponse['status'] = "failed";
			$returnResponse['code'] = "302";
			$returnResponse['message'] = "Error while adding customer. Please try again.";
			$returnResponse['data'] = $addCustomer;
		}
		return response()->json($returnResponse);*/
	}



	public function contacts($id){
		$id = decrypt($id);
		$contacts =  Gst::contacts($id);

		if (sizeof($contacts) > 0) {
			$data['status'] = "success";
			$data['code'] = "200";
			$data['message'] = "Data found.";
			$data['data'] = $contacts;
		}else{
			$data['status'] = "success";
			$data['code'] = "204";
			$data['message'] = "No data found.";
			$data['data'] = '';
		}
		
		return view('gst.contacts')->with('data', $data);
	}



	public function editContact($id){
		$item = decrypt($id);
		$getData = Gst::getContactData($item);

		if (sizeof($getData) > 0) {
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "Data found.";
			$returnResponse['data'] = $getData;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "No data found.";
			$returnResponse['data'] = $getData;
		}
		return view('gst.editCustomer')->with('data', $returnResponse);
	}



	public function updateContact(Request $request, $id){
		$input = $request->all();

		$updateContact = Gst::updateContact($input,$id);

		if($updateContact > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "201";
			$returnResponse['message'] = "Contact updated successfully.";
			$returnResponse['data'] = $updateContact;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "Not updated.";
			$returnResponse['data'] = $updateContact;
		}
		return response()->json($returnResponse);
	}



	public function deleteContact($id){
		$getData = Gst::deleteContact($id);

		if (sizeof($getData) > 0) {
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "Contact deleted successfully.";
			$returnResponse['data'] = $getData;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "Something went wrong while deleting contact.";
			$returnResponse['data'] = $getData;
		}
		return response()->json($returnResponse);
	}



	public function importItemFile(Request $request){	

		$input = $request->all();
		if(isset($input['item_csv'])){

			$file1 = $input['item_csv'];
			
			$file_name1 = basename($file1->getClientOriginalName(), '.'.$file1->getClientOriginalExtension());
			$fileName1 = $file_name1.time().'.'.$file1->getClientOriginalExtension();
			$fileName1 = str_replace(' ', '', $fileName1);
			$fileName1 = preg_replace('/\s+/', '', $fileName1);
			$file_upload1 = $file1->move(
				base_path() . '/public/Contact/', $fileName1
				);
			$fileName['item_csv'] = $file1;
			$input['item_csv']=$file1;
		}
		$full_url = base_path() . '/public/Contact/'.$fileName1;
		$csv = Reader::createFromPath($full_url);

		$headers = $csv->fetchOne();

		$res = $csv->setOffset(1)->fetchAll();
		$key_value=array();
		$group_id = 1;
		$user_id = 2;
		foreach($res as $key => $val){
			$key_value[$key]['business_id'] = $input['business_id'];
			$key_value[$key]['item_sku'] = $res[$key][0];
			$key_value[$key]['item_type'] = $res[$key][1];
			$key_value[$key]['item_hsn_sac'] = $res[$key][2];
			$key_value[$key]['item_description'] = $res[$key][3];
			$key_value[$key]['item_unit'] = $res[$key][4];
			$key_value[$key]['item_sale_price'] = $res[$key][5];
			$key_value[$key]['item_purchase_price'] = $res[$key][6];
			$key_value[$key]['item_discount'] = $res[$key][7];
			$key_value[$key]['item_notes'] = $res[$key][8];
			$key_value[$key]['created_at'] = date('Y-m-d H:i:s');
		}
		$key_value;
		$start_time = date("h:i:sa");

		$collection = collect($key_value); 
		$infoFileInsertedData = Gst::addItemFromCSV($collection->toArray());  
		
		if($infoFileInsertedData){
			$response['numbers'] = sizeof($collection);
			$response['status'] = "success";
			$response['code'] = 200;
			$response['message'] = "OK";
			$response['data'] = $infoFileInsertedData;
			$response['strat_time'] = $start_time;
			$response['end_time'] = date("h:i:sa");
			unlink($full_url);
		}else{
			$response['numbers'] = sizeof($collection);
			$response['status'] = "fail";
			$response['code'] = 400;
			$response['message'] = "Bad Request";
			$response['data'] = $infoFileInsertedData;
			unlink($full_url);
		}
		return view('gst.importmsg')->with('data', $response);
	}



	public function addItem(Request $request){
		$input = $request->all();

		$addItem = Gst::addItem($input);

		if($addItem > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "201";
			$returnResponse['message'] = "Item added Sucessfully.";
			$returnResponse['data'] = $addItem;
		}else{
			$returnResponse['status'] = "failed";
			$returnResponse['code'] = "302";
			$returnResponse['message'] = "Error while adding item. Please try again.";
			$returnResponse['data'] = $addItem;
		}
		
		return response()->json($returnResponse);
	}



	/*public function items($id){

		$items =  Gst::items($id);

		if (sizeof($items) > 0) {
			$data['status'] = "success";
			$data['code'] = "200";
			$data['message'] = "Data found.";
			$data['data'] = $items;
		}else{
			$data['status'] = "success";
			$data['code'] = "204";
			$data['message'] = "No data found.";
			$data['data'] = '';
		}
		
		return view('gst.items')->with('data', $data);
	}*/



	public function editItem($id){
		$item = decrypt($id);
		$getData = Gst::getItemData($item);

		if (sizeof($getData) > 0) {
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "Data found.";
			$returnResponse['data'] = $getData;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "No data found.";
			$returnResponse['data'] = $getData;
		}
		return view('gst.editItem')->with('data', $returnResponse);
	}



	public function updateItem(Request $request, $id){
		$input = $request->all();

		$updateItem = Gst::updateItem($input,$id);

		if($updateItem > 0){
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "201";
			$returnResponse['message'] = "Item updated successfully.";
			$returnResponse['data'] = $updateItem;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "Not updated.";
			$returnResponse['data'] = $updateItem;
		}
		return response()->json($returnResponse);
	}



	public function deleteItem($id){
		$getData = Gst::deleteItem($id);

		if (sizeof($getData) > 0) {
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "200";
			$returnResponse['message'] = "Item deleted successfully.";
			$returnResponse['data'] = $getData;
		}else{
			$returnResponse['status'] = "success";
			$returnResponse['code'] = "204";
			$returnResponse['message'] = "Something went wrong while deleting item.";
			$returnResponse['data'] = $getData;
		}
		return response()->json($returnResponse);
	}


}
