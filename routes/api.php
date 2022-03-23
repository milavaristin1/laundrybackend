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

Route::post('/login', 'AuthController@login');
Route::get('tes', 'TesController@coba');
Route::post('user/tambah', 'UserController@store');
Route::get('dashboard', 'DasboardController@index');

Route::group(['middleware' => ['api', 'jwt.verify:admin,kasir,owner']], function () {
  // KELOMPOK ROUTE KHUSUS UNTUK ADMIN DAN KASIR
  Route::get('login/check', 'AuthController@loginCheck');
  Route::post('/logout', 'AuthController@logout');
});

Route::group(['middleware' => ['api', 'jwt.verify:admin']], function () {
  // KELOMPOK ROUTE KHUSUS UNTUK ADMIN DAN KASIR
  // Route::post('user/tambah', 'AuthController@register');
});

Route::group(['middleware' => ['api', 'jwt.verify:admin,owner,kasir']], function () {
  //MEMBER
  Route::post('tambahmember', 'MemberController@store');
  Route::get('tampilmember', 'MemberController@getAll');
  // Route: :get('tampilmember/{id}', 'MemberController@getById');
  Route::get('tampilmember/{id}', 'MemberController@getdata');
  Route::put('updatemember/{id}', 'MemberController@update');
  Route::delete('deletemember/{id}', 'MemberController@delete');

  //REPORT
  Route::post('report', 'TransaksiController@report');
});

Route::group(['middleware' => ['jwt.verify:admin,kasir']], function () {
    //user
    Route::post('user', 'UserController@store');
    Route::get('user', 'UserController@getAll');
    Route::get('user/{id}', 'UserController@getById');
    Route::put('user/{id}', 'UserController@update');
    Route::delete('user/{id}', 'UserController@delete');
});

Route::group(['middleware' => ['jwt.verify:admin,kasir,owner']], function(){
  //PAKET
  Route::post('tambahpaket', 'PaketController@store');
  Route::get('tampilpaket', 'PaketController@getAll');
  Route::get('tampilpaket/{id}', 'PaketController@getById');
  Route::put('updatepaket/{id}', 'PaketController@update');
  Route::delete('deletepaket/{id}', 'PaketController@delete');
  });

Route::group(['middleware' => ['jwt.verify:admin,kasir']], function(){
  //TRANSAKSI
  Route::post('tambahtransaksi', 'TransaksiController@store');
  Route::get('tampiltransaksi', 'TransaksiController@getAll');
  Route::get('tampiltransaksi/{id}', 'TransaksiController@getById');
  Route::put('updatetransaksi/{id}', 'TransaksiController@update');
  Route::post('transaksi/bayar/{id}', 'TransaksiController@bayar');
  Route::post('/transaksi/status/{id}', 'TransaksiController@changeStatus');

  // DETAIL TRANSAKSI
  Route::post('transaksi/detail', 'DetailTransaksiController@store');
  Route::get('transaksi/detail', 'DetailTransaksiController@showAllDetail');
  Route::get('transaksi/detail/{id}', 'DetailTransaksiController@showDetailById');
  Route::get('transaksi/total/{id}', 'DetailTransaksiController@getTotal');
  });

  // OUTLET
  Route::group(['middleware' => ['jwt.verify:admin,kasir']], function () {
    //OUTLET
    Route::post('outlet', 'OutletController@store');
    Route::get('outlet', 'OutletController@getAll');
    Route::get('outlet/{id}', 'OutletController@getById');
    Route::put('outlet/{id}', 'OutletController@update');
    Route::delete('outlet/{id}', 'OutletController@delete');
});
