<?php

use Illuminate\Support\Facades\Input;
use App\Gst;

$api = app('Dingo\Api\Routing\Router');

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
	return view('gst.index');
});

Route::get('/index', function () {
	return view('gst.index');
});

Route::get('/login', function () {
	return view('gst.login');
});

Route::get('/signup', function () {
	return view('gst.signup');
});

Route::get('/welcome', function () {
	return view('gst.welcome');
});

Route::get('/importcontact', function () {
	return view('gst.importcontact');
});

Route::get('/importitem', function () {
	return view('gst.importitem');
});

Route::get('business', [
	'as' => 'gst.business', 'uses' => 'Api\V1\GstController@business'
	]);

Route::get('setting', [
	'as' => 'gst.setting', 'uses' => 'Api\V1\GstController@setting'
	]);

Route::get('gstn/{id}', [
	'as' => 'gstn/{id}', 'uses' => 'Api\V1\GstController@getBusinessGstin'
	]);

Route::get('/addCustomer', function () {
	return view('gst.addCustomer');
});

Route::get('/addItem', function () {
	return view('gst.addItem');
});

Route::post('importContactFile', [
	'as' => 'gst.importContactFile', 'uses' => 'Api\V1\GstController@importContactFile'
	]);

Route::post('importItemFile', [
	'as' => 'gst.importItemFile', 'uses' => 'Api\V1\GstController@importItemFile'
	]);

Route::post ( '/items', function () {
	$business_id = Input::get ('business_id');
	$data = Gst::items($business_id);
	return view('gst.items',['data'=>$data]);
} );

Route::get('editItem/{id}', [
	'as' => 'editItem/{id}', 'uses' => 'Api\V1\GstController@editItem'
	]);

Route::get('contacts/{id}', [
	'as' => 'gst.contacts/{id}', 'uses' => 'Api\V1\GstController@contacts'
	]);

Route::get('editCustomer/{id}', [
	'as' => 'editCustomer/{id}', 'uses' => 'Api\V1\GstController@editContact'
	]);

$api->version('v1', function ($api) {
	$api->post('signup', 'App\Http\Controllers\Api\V1\GstController@signup');
});

$api->version('v1', function ($api) {
	$api->post('viewUsers', 'App\Http\Controllers\Api\V1\GstController@viewUsers');
});

$api->version('v1', function ($api) {
	$api->post('updateUser/{id}', 'App\Http\Controllers\Api\V1\GstController@updateUser');
});

$api->version('v1', function ($api) {
	$api->post('login', 'App\Http\Controllers\Api\V1\GstController@login');
});

$api->version('v1', function ($api) {
	$api->post('logout', 'App\Http\Controllers\Api\V1\GstController@logout');
});

$api->version('v1', function ($api) {
	$api->post('addBusiness', 'App\Http\Controllers\Api\V1\GstController@addBusiness');
});

$api->version('v1', function ($api) {
	$api->get('getBusinessData/{id}', 'App\Http\Controllers\Api\V1\GstController@getBusinessData');
});

$api->version('v1', function ($api) {
	$api->post('updateBusiness/{id}', 'App\Http\Controllers\Api\V1\GstController@updateBusiness');
});

$api->version('v1', function ($api) {
	$api->post('deleteBusiness/{id}', 'App\Http\Controllers\Api\V1\GstController@deleteBusiness');
});

$api->version('v1', function ($api) {
	$api->get('getBusinessGstin/{id}', 'App\Http\Controllers\Api\V1\GstController@getBusinessGstin');
});

$api->version('v1', function ($api) {
	$api->post('addGstin', 'App\Http\Controllers\Api\V1\GstController@addGstin');
});

$api->version('v1', function ($api) {
	$api->get('getGstinData/{id}', 'App\Http\Controllers\Api\V1\GstController@getGstinData');
});

$api->version('v1', function ($api) {
	$api->post('updateGstin/{id}', 'App\Http\Controllers\Api\V1\GstController@updateGstin');
});

$api->version('v1', function ($api) {
	$api->post('deleteGstin/{id}', 'App\Http\Controllers\Api\V1\GstController@deleteGstin');
});

$api->version('v1', function ($api) {
	$api->post('getBusiness', 'App\Http\Controllers\Api\V1\GstController@getBusiness');
});

$api->version('v1', function ($api) {
	$api->post('addCustomer', 'App\Http\Controllers\Api\V1\GstController@addCustomer');
});

$api->version('v1', function ($api) {
	$api->post('deleteContact/{id}', 'App\Http\Controllers\Api\V1\GstController@deleteContact');
});

$api->version('v1', function ($api) {
	$api->post('updateContact/{id}', 'App\Http\Controllers\Api\V1\GstController@updateContact');
});

$api->version('v1', function ($api) {
	$api->post('addItem', 'App\Http\Controllers\Api\V1\GstController@addItem');
});

$api->version('v1', function ($api) {
	$api->post('deleteItem/{id}', 'App\Http\Controllers\Api\V1\GstController@deleteItem');
});

$api->version('v1', function ($api) {
	$api->post('updateItem/{id}', 'App\Http\Controllers\Api\V1\GstController@updateItem');
});








/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
