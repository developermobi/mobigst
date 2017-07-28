<?php

namespace App\Http\Controllers\Api\V1;
use Illuminate\Http\Request;
use App\Http\Requests;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
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


}
