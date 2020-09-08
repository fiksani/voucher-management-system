<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->prefix('v1')->group( function () {
	Route::prefix('campaign')->group( function () {
		Route::get('/', 'Api\V1\VoucherNameController@index')->name('campaign.index');
		Route::post('/', 'Api\V1\VoucherNameController@store')->name('campaign.store');
		Route::patch('{id}/', 'Api\V1\VoucherNameController@update')->name('campaign.update');
		Route::post('{id}/vouchers', 'Api\V1\VoucherController@register')->name('voucher.register');
	});

	Route::prefix('customer')->group( function () {
		Route::get('/', 'Api\V1\CustomerController@index')->name('customer.index');
		Route::get('/{id}/vouchers', 'Api\V1\CustomerController@vouchers')->name('customer.vouchers');
	});

	Route::post('/redeem', 'Api\V1\VoucherController@redeem')->name('voucher.redeem');
});