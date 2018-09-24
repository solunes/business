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

Route::group(['prefix'=>'process'], function(){
    Route::get('/calculate-shipping/{shipping_id}/{city_id}/{weight}', 'ProcessController@getCalculateShipping');
    Route::get('/customer-logout', 'ProcessController@getCustomerLogout');
    Route::post('/customer-register', 'ProcessController@postCustomerRegistration');
});

Route::group(['prefix'=>'gitlab'], function(){
    Route::get('/group-businesss/{group_name}', 'GitlabController@getGroupBusinesss');
    Route::get('/business/{business_name}/{group_name}', 'GitlabController@getBusiness');
    Route::get('/business-commits/{business_name}/{group_name}', 'GitlabController@getBusinessCommits');
});

Route::group(['prefix'=>'hubspot'], function(){
    Route::get('/import-companies/{count?}', 'Integrations\HubspotController@getImportCompanies');
    Route::get('/import-contacts/{count?}', 'Integrations\HubspotController@getImportContacts');
    Route::get('/import-deals/{count?}', 'Integrations\HubspotController@getImportDeals');
    Route::post('/webhook', 'Integrations\HubspotController@postHubspotWebhook');
});