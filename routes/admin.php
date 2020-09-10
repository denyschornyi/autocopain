<?php

/*
  |--------------------------------------------------------------------------
  | Admin Auth Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', 'AdminController@dashboard')->name('index');
Route::get('/dashboard', 'AdminController@dashboard')->name('dashboard');
Route::get('/translation', 'AdminController@translation')->name('translation');

Route::resource('user', 'Resource\UserResource');
Route::get('user/{id}/send-email', 'Resource\UserResource@send_email')->name('send.email');
Route::post('user/send-user-email', 'Resource\UserResource@send_user_email')->name('emailToUser');
Route::get('user/{id}/email-history', 'Resource\UserResource@email_history')->name('email.history');
Route::resource('provider', 'Resource\ProviderResource');
Route::resource('document', 'Resource\DocumentResource');
Route::resource('service', 'Resource\ServiceResource');
Route::resource('category', 'Resource\CategoryResource');
Route::get('service/category', 'Resource\ServiceResource@category')->name('service.category');
Route::resource('promocode', 'Resource\PromocodeResource');
Route::get('list/{id}', 'AdminController@latest_list')->name('list');

Route::group(['as' => 'provider.'], function () {
    Route::get('review/provider', 'AdminController@provider_review')->name('review');
    Route::get('provider/{id}/approve', 'Resource\ProviderResource@approve')->name('approve');
    Route::get('provider/{id}/disapprove', 'Resource\ProviderResource@disapprove')->name('disapprove');
    Route::get('provider/{id}/request', 'Resource\ProviderResource@request')->name('request');
    Route::resource('provider/{provider}/document', 'Resource\ProviderDocumentResource');
    Route::post('provider/{provider}/document/upload/{id}', 'Resource\ProviderResource@update_document')->name('document.update_document');
    Route::get('provider/{id}/statement', 'Resource\ProviderResource@statement')->name('statement');
    Route::get('provider/{id}/bank-details', 'Resource\ProviderResource@bank_details')->name('bank.details');
    Route::post('provider/add-bank-details', 'Resource\ProviderResource@add_bank_details')->name('add_bank_details');
    Route::get('provider/{id}/send-email', 'Resource\ProviderResource@send_email')->name('send.email');
    Route::post('provider/send-provider-email', 'Resource\ProviderResource@send_provider_email')->name('emailToProvider');
    Route::get('provider/{id}/email-history', 'Resource\ProviderResource@email_history')->name('email.history');
    Route::post('provider/rib-store', 'Resource\ProviderResource@rib_store')->name('rib_store');
    Route::get('provider/{id}/validate', 'Resource\ProviderResource@validate_doc')->name('validate');
    Route::get('provider/{id}/reject', 'Resource\ProviderResource@reject_doc')->name('reject');
});

Route::get('email', 'AdminController@showSendEmailForm')->name('email');
Route::get('email-history', 'AdminController@showEmailDetails')->name('email.history');
Route::post('send-email', 'AdminController@sendEmailsToUsers')->name('emailToUsers');
Route::get('cancel_report', 'AdminController@cancel_report')->name('cancel_report');
Route::get('provider_score', 'AdminController@provider_score')->name('provider_score');
Route::get('provider/delete/allocation/', 'AdminController@destory_allocation')->name('destory.allocation');
Route::get('review/user', 'AdminController@user_review')->name('user.review');
Route::get('user/{id}/request', 'Resource\UserResource@request')->name('user.request');
Route::get('map/user', 'AdminController@user_map')->name('user.map');
Route::get('map/provider', 'AdminController@provider_map')->name('provider.map');
Route::get('setting', 'AdminController@setting')->name('setting');
Route::post('setting/store', 'AdminController@setting_store')->name('setting.store');
Route::get('profile', 'AdminController@profile')->name('profile');
Route::post('profile/update', 'AdminController@profile_update')->name('profile.update');
Route::get('password', 'AdminController@password')->name('password');
Route::post('password/update', 'AdminController@password_update')->name('password.update');
Route::get('payment', 'AdminController@payment')->name('payment');
Route::get('payment/setting', 'AdminController@payment_setting')->name('payment.setting');
Route::get('help', 'AdminController@help')->name('help');
Route::get('/privacy', 'AdminController@privacy')->name('privacy');
Route::post('/pages', 'AdminController@pages')->name('pages.update');
Route::get('request', 'AdminController@request_history')->name('request.history');
Route::get('scheduled/request', 'AdminController@scheduled_request')->name('scheduled.request');
Route::get('request/{id}/details', 'AdminController@request_details')->name('request.details');
Route::get('destory/{id}/service', 'AdminController@destory_provider_service')->name('destory.service');

// statements

Route::get('/statement', 'AdminController@statement')->name('ride.statement');
Route::get('/statement/provider', 'AdminController@statement_provider')->name('ride.statement.provider');
Route::get('/statement/today', 'AdminController@statement_today')->name('ride.statement.today');
Route::get('/statement/monthly', 'AdminController@statement_monthly')->name('ride.statement.monthly');
Route::get('/statement/yearly', 'AdminController@statement_yearly')->name('ride.statement.yearly');
Route::get('/statement/payouts', 'AdminController@payouts')->name('ride.statement.payouts');

// settlement
Route::get('/transfer/provider', 'AdminController@transferlist')->name('ride.statement.providersettlements');
Route::get('/transfer/create', 'AdminController@view_transfer')->name('ride.statement.providersettlements.view');
Route::post('/transfer/create', 'AdminController@create_transfer')->name('ride.statement.providersettlements.create');
Route::get('/transfer/search', 'AdminController@search')->name('transfersearch');
Route::get('/transfer/{id}/approve', 'AdminController@approve')->name('approve');
Route::get('/transfer/cancel', 'AdminController@requestcancel')->name('cancel');
Route::get('/transactions', 'AdminController@transactions')->name('transactions');
