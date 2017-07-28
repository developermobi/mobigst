<?php

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

/*Route::get('/business', function () {
	return view('gst.business');
});*/

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
