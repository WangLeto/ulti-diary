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

Route::group([], function() {
	Route::post('askSession', 'AuthController@askSession');
	
	Route::post('uploadFile', 'DiaryController@uploadFile');
});

Route::group(['middleware' => 'jwt.auth'], function() {
	Route::post('queryDiary', 'DiaryController@queryDiary');
	Route::post('submitDiary', 'DiaryController@submitDiary');
	Route::post('submitRename', 'DiaryController@submitRename');
	Route::post('submitStar', 'DiaryController@submitStar');
});
