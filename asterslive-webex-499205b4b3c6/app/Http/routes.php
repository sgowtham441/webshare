<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['namespace' => 'Admin','prefix' => 'ALadmin', 'middleware' => ['auth','authrole']], function()
{


	Route::get('/', 'UsermanagementController@getIndex');
	Route::get('user', 'UsermanagementController@getIndex');
	Route::get('user/add', 'UsermanagementController@getAdd');
	Route::get('user/edit/{id}', 'UsermanagementController@getEdit');
	Route::get('user/delete/{id}', 'UsermanagementController@getDelete');
	Route::post('user/saveuser', 'UsermanagementController@postSaveuser');
	Route::get('user/changepassword/{id}', 'UsermanagementController@getChangepassword');
	Route::post('user/savepassword/{id}', 'UsermanagementController@postSavepassowrd');
	Route::post('user/saveprofile/{id}', 'UsermanagementController@postSaveprofile');
});

Route::group(['namespace' => 'Groupuser', 'middleware' => 'auth'], function()
{

	
	Route::get('/', 'GroupuserController@getIndex');
	Route::get('manage', 'GroupuserController@getUrlmanagement');
	Route::get('generate', 'GroupuserController@getUrlgenerate');
	
});
Route::get('meeting/{id}', 'MeetingController@getIndex');
Route::post('join/{id}', 'MeetingController@postJoin');
Route::get('viewscreen', 'MeetingController@getViewscreen');
Route::get('viewerscreen', 'MeetingController@getViewerscreen');
Route::get('screenlogout', 'MeetingController@getScreenlogout');
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
