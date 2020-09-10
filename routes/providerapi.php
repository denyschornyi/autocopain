<?php

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

// Authentication
Route::post('/register', 'ProviderAuth\TokenController@register');
Route::post('/oauth/token', 'ProviderAuth\TokenController@authenticate');
Route::get('/oauth/refreshtoken', 'ProviderAuth\TokenController@refreshtoken');

Route::post('/forgot/password', 'ProviderAuth\TokenController@forgot_password');
Route::post('/reset/password', 'ProviderAuth\TokenController@reset_password');
Route::post('/logout', 'ProviderAuth\TokenController@logout');

Route::group(['middleware' => ['provider.api']], function () {

    Route::group(['prefix' => 'profile'], function () {

        Route::get('/', 'ProviderResources\ProfileController@index');
        Route::post('/', 'ProviderResources\ProfileController@update');
        Route::post('/password', 'ProviderResources\ProfileController@password');
        Route::post('/location', 'ProviderResources\ProfileController@location');
        Route::post('/available', 'ProviderResources\ProfileController@available');
    });

    Route::get('/target', 'ProviderApiController@target');

    Route::get('/services', 'ProviderApiController@services');
    Route::get('/servicess/{Category_id}', 'ProviderApiController@servicess');
    Route::get('/user', 'ProviderApiController@user');
    Route::post('/update/service', 'ProviderApiController@update_services');
    Route::post('/update2/service', 'ProviderApiController@update2_services');
    // Route::post('/logout', 'ProviderAuth\TokenController@logout');
    Route::resource('trip', 'ProviderResources\TripController');
    Route::post('cancel', 'ProviderResources\TripController@cancel');
    Route::get('summary', 'ProviderResources\TripController@summary');
    Route::get('help', 'ProviderResources\TripController@help_details');

    Route::group(['prefix' => 'trip'], function () {

        Route::post('{id}', 'ProviderResources\TripController@accept');
        Route::post('{id}/rate', 'ProviderResources\TripController@rate');
        Route::post('{id}/message', 'ProviderResources\TripController@message');
    });

    Route::group(['prefix' => 'requests'], function () {

        Route::get('/upcoming', 'ProviderApiController@upcoming_request');
        Route::get('/history', 'ProviderResources\TripController@history');
        Route::get('/history/details', 'ProviderResources\TripController@history_details');
        Route::get('/upcoming/details', 'ProviderResources\TripController@upcoming_details');
    });

    Route::get('/categories', 'UserApiController@categories');

    // documents
    Route::get('documents', 'ProviderResources\ProfileController@documents');
    Route::post('documents', 'ProviderResources\ProfileController@update_document');

    Route::post('wallet/history', 'ProviderResources\TripController@wallet_history');
    Route::post('wallet/detail', 'ProviderResources\TripController@wallet_detail');
    Route::post('wallet/transaction/request', 'ProviderResources\TripController@transaction_request');
    Route::post('wallet/transaction/history', 'ProviderResources\TripController@transaction_history');
    // test
    Route::post('aaaabbbb', 'ProviderResources\TripController@migratingWalletData');

    // cards
    Route::resource('card', 'Resource\ProviderCardResource');
    Route::post('/card/default', 'ProviderApiController@setDefaultCard');
    Route::post('/money/add', 'ProviderApiController@add_money');

});
