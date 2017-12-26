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
    
    // Módulo de Proyectos
    Route::get('business-dashboard', 'CustomAdminController@getIndex');
    Route::get('businesss', 'CustomAdminController@allBusinesss');
    Route::get('business/{id}/{subpage?}', 'CustomAdminController@findBusiness');
    Route::get('business-task/{id}', 'CustomAdminController@findBusinessTask');
    Route::get('business-issue/{id}', 'CustomAdminController@findBusinessIssue');
    Route::get('wikis/{category?}', 'CustomAdminController@allWikis');
    Route::get('wiki/{id}', 'CustomAdminController@findWiki');

    // Módulo de Reportes
    //Route::get('business-report', 'ReportController@getBusinessReport');

});