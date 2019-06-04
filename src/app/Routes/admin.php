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

Route::group(['prefix'=>'admin'], function(){
    
    Route::get('search-product/{id?}', 'CustomAdminController@searchProduct');
    Route::get('generate-barcodes-pdf', 'CustomAdminController@generateBarcodesPdf');
    Route::get('check-product/{id}/{currency_id}', 'CustomAdminController@getCheckProduct');

});