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

Route::get('/auth/login/', 'Auth\AuthController@getLogin');
Route::post('/auth/login', 'Auth\AuthController@postLogin');
Route::get('/logout', 'Auth\AuthController@getLogout');

//Route::group(['prefix' => 'manager'], function(){
//    Route::group(['middleware' => 'check.manager.login'], function(){
//        Route::get('/welcome', 'Manager\IndexController@toWelcome');
//        Route::get('/index', 'Manager\IndexController@toIndex');
//        Route::get('/account_manage', 'Manager\AccountController@toList');
//        Route::get('/account_add', 'Manager\AccountController@toAdd');
//        Route::post('/account_add', 'Manager\AccountController@add');
//        Route::get('/update_password', 'Manager\AccountController@toUpdatePassword');
//        Route::post('/update_password', 'Manager\AccountController@updatePassword');
//        Route::get('delete_account', 'Manager\AccountController@delete');
//    });
//
//    Route::group(['middleware' => 'check.user.login'], function(){
//        Route::get('/project_list', 'Manager\ProjectController@toList');
//        Route::get('/project_add', 'Manager\ProjectController@toAdd');
//        Route::post('/project_add', 'Manager\ProjectController@add');
//        Route::get('/customer_list', 'Manager\CustomerController@toList');
//        Route::get('/customer_add', 'Manager\CustomerController@toAdd');
//        Route::post('/customer_add', 'Manager\CustomerController@add');
//        Route::get('/customer_delete', 'Manager\CustomerController@delete');
//        Route::get('/customer_details', 'Manager\CustomerController@getCustomerDetails');
//        Route::post('/customer_update', 'Manager\CustomerController@update');
//    });
//});

Route::group(['middleware' => ['auth'],
    'namespace' => 'admin'], function() {
    Route::get('/', 'IndexController@toIndex');
    Route::get('/welcome', 'IndexController@toWelcome');

    Route::group(['middleware' => ['role:admin'],
        'namespace' => 'manager',
        'prefix' => 'manager'], function () {
        Route::resource('user', 'UserController');
        Route::resource('customer', 'CustomerController');
    });

    Route::group(['middleware' => ['role:programmer'],
        'namespace' => 'programmer',
        'prefix' => 'programmer'], function () {

    });

    Route::group(['middleware' => ['role:designer'],
        'namespace' => 'designer',
        'prefix' => 'designer'], function () {

    });

    Route::group(['middleware' => ['role:commonUser'],
        'namespace' => 'commonUser',
        'prefix' => 'commonUser'], function () {

    });
    }
);
