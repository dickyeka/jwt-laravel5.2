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

Route::get('/', function () {
    return 'tes';
});

Route::group(['prefix' => 'api'], function()
{
	Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
	Route::post('authenticate', 'AuthenticateController@authenticate');
});

Event::listen('tymon.jwt.invalid',function(){
	$response = response()->json([
        'code'      => 401,
        'error'     => true,            
        'message'   => 'Invalid Token'],
        401
    );
    $response->header('Content-Type', 'application/json');
    return $response;
});




Event::listen('tymon.jwt.absent',function(){
	$response = response()->json([
        'code'      => 400,
        'error'     => true,            
        'message'   => 'Token Not Provided'],
        400
    );
    $response->header('Content-Type', 'application/json');
    return $response;
});