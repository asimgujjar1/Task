<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::POST('login', 'ApiController@login');
Route::POST('signup', 'ApiController@signup');
Route::POST('verify_otp', 'ApiController@verify_otp');

Route::group(['prefix' => '/', 'middleware' => 'auth:api'], function(){
    Route::post('send_invite', 'ApiController@send_invite'); 
    Route::GET('view_profile', 'ApiController@view_profile'); 
    Route::POST('update_profile', 'ApiController@update_profile');
});
