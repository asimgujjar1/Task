<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});
Route::get('register', function () {
    return view('register');
});
Route::post('signup', 'AuthController@signup');
Route::GET('email_verification/{user_id}', 'AuthController@email_verification');
Route::POST('verification', 'AuthController@verification');
Route::post('submit_login', 'AuthController@submit_login');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'superadmin'], function () {
    Route::GET('dashboard', 'SuperAdminController@dashboard');
    Route::POST('send_invite', 'SuperAdminController@send_invite');
    });

    Route::group(['middleware' => 'user'], function () {
    Route::get('user_profile', 'UserController@user_profile');
    });
});
