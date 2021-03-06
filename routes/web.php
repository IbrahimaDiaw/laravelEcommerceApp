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
    return view('welcome');
});

Route::post('user/login', 'Auth\User\LoginController@login');
Route::post('register', 'Auth\User\LoginController@userRegister');

Route::post('auth/login', 'Auth\Vendor\LoginController@login');
Route::post('auth/register', 'Auth\Vendor\LoginController@vendorRegister');

Route::post('auth/admin/login', 'Auth\Admin\LoginController@login');
Route::post('auth/admin/register', 'Auth\Admin\LoginController@adminRegister');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('admin', 'Auth\Admin\LoginController@getAuthAdmin');
});

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('user', 'Auth\User\LoginController@getAuthUser');
});

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('vendor', 'Auth\Vendor\LoginController@getAuthVendor');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
