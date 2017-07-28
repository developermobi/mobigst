<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
DB::enableQueryLog();


class Gst extends Model{


	public static function mail_available($email){
		$result = DB::table('user')
		->where('email',$email)
		->where('status',1)
		->get();

		return $result;
	}



	public static function addUser($input){
		$input['created_at'] = date('Y-m-d H:i:s');

		$addUser = DB::table('user')
		->insert($input);

		return $addUser;
	}



	public static function viewUsers(){
		
		$viewUsers = DB::table('user')
		->where('status',1)
		->get();

		return $viewUsers;
	}



	public static function updateUser($data,$id){

		$updateData = DB::table('user')
		->where('user_id', $id)
		->update($data);

		return  $updateData;
	}



	public static function login($input){

		$login = DB::table('user')
		->where('email', $input['email'])
		->where('password', $input['password'])
		->where('status',1)
		->get();

		return  $login;
	}



	public static function updateUserToken($data){

		$updateData = DB::table('user')
		->where('user_id',"=",$data['user_id'])
		->update($data);

		return  $updateData;
	}



	public static function logout($input){

		$logout = DB::table('user')
		->where('user_id', $input['tokenId'])
		->where('remember_token', $input['token'])
		->where('status',1)
		->get();

		return  $logout;
	}



	public static function business($user_id){

		$getData = DB::table('business')
		->select('business_id','name','pan')
		->where('user_id',$user_id)
		->where('status',1)
		->get();

		return $getData;
	}



	public static function gstin($business_id){

		$getData = DB::table('gstin')
		->select('gstin_id','gstin_no','display_name')
		->where('business_id',$business_id)
		->where('status',1)
		->get();

		return $getData;
	}



	public static function addBusiness($input){
		$input['created_at'] = date('Y-m-d H:i:s');

		$addBusiness = DB::table('business')
		->insertGetId($input);

		return $addBusiness;
	}



	public static function addGstin($input){
		$input['created_at'] = date('Y-m-d H:i:s');

		$addGstin = DB::table('gstin')
		->insert($input);

		return $addGstin;
	}



	public static function getBusinessData($business_id){

		$getData = DB::table('business')
		->select('business_id','name','pan')
		->where('business_id',$business_id)
		->where('status',1)
		->get();

		return $getData;
	}



	public static function updateBusiness($data,$id){

		$updateData = DB::table('business')
		->where('business_id', $id)
		->update($data);

		return  $updateData;
	}



	public static function deleteBusiness($business_id){

		$data['status'] = '0';
		$updateData = DB::table('business')
		->where('business_id', $business_id)
		->update($data);

		return  $updateData;
	}



	public static function getGstinData($gstin_id){

		$getData = DB::table('gstin')
		->select('gstin_id','gstin_no','display_name')
		->where('gstin_id',$gstin_id)
		->where('status',1)
		->get();

		return $getData;
	}



	public static function updateGstin($data,$id){

		$updateData = DB::table('gstin')
		->where('gstin_id', $id)
		->update($data);

		return  $updateData;
	}



	public static function deleteGstin($gstin_id){

		$data['status'] = '0';
		$updateData = DB::table('gstin')
		->where('gstin_id', $gstin_id)
		->update($data);

		return  $updateData;
	}






}