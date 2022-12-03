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

Route::group(['namespace' => 'Api', 'prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    //
    Route::get('', function () {
        return \Response::json(\Auth::guard('api')->user());
    });

    // Simple authentication
    Route::get('me', function () {
        return \Response::json(\Auth::guard('api')->user());
    });

    // List
    Route::delete('lists/{uid}', 'MailListController@delete');
    Route::post('lists/{uid}/add-field', 'MailListController@addField');
    Route::resource('lists', 'MailListController');

    // Campaign
    Route::post('campaigns/{uid}/pause', 'CampaignController@pause');
    Route::resource('campaigns', 'CampaignController');

    // Subscriber
    Route::post('subscribers/{uid}/add-tag', 'SubscriberController@addTag');
    Route::get('subscribers/email/{email}', 'SubscriberController@showByEmail');
    Route::patch('lists/{list_uid}/subscribers/{uid}/subscribe', 'SubscriberController@subscribe');
    Route::patch('lists/{list_uid}/subscribers/{uid}/unsubscribe', 'SubscriberController@unsubscribe');
    Route::delete('subscribers/{uid}', 'SubscriberController@delete');

    Route::resource('subscribers', 'SubscriberController');

    // Automation
    Route::post('automations/{uid}/api/call', 'AutomationController@apiCall');

    // Sending server
    Route::resource('sending_servers', 'SendingServerController');

    // Plan
    Route::resource('plans', 'PlanController');

    // Customer
    Route::match(['get','post'], 'login-token', 'CustomerController@loginToken');
    Route::post('customers/{uid}/assign-plan/{plan_uid}', 'CustomerController@assignPlan');
    Route::patch('customers/{uid}/disable', 'CustomerController@disable');
    Route::patch('customers/{uid}/enable', 'CustomerController@enable');
    Route::resource('customers', 'CustomerController');

    // Subscription
    Route::resource('subscriptions', 'SubscriptionController');

    // File
    Route::post('file/upload', 'FileController@upload');

    // File
    Route::post('automations/{uid}/execute', 'AutomationController@execute')->name('automation_execute');

    // Subscription
    Route::resource('notification', 'NotificationController');
});
