<?php

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

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
//for Partners
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', 'HomeController@index')->name('home');
    Route::resource('hotelguest', 'HotelGuestController');
    Route::get('/notifications', 'UserController@notifications');
    Route::get('/threads', 'UserController@threads');
    Route::resource('profile', 'ProfileController');
    Route::resource('partnertypes' , 'PartnerTypesController');
    Route::resource('usergroups' , 'UserGroupController');
    Route::resource('users' , 'UserController');
    Route::resource('partners' , 'PartnersController');
    Route::resource('doctors', 'DoctorController');
    Route::get('doctors/viewCard/{id}', 'DoctorController@viewCard');
    Route::resource('patients', 'PatientController');
    Route::resource('nurses', 'NurseController');
    Route::resource('products', 'ProductController');
    Route::resource('orders', 'OrderController');
    Route::group(['prefix' => 'messages'], function () {
        Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
        Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
        Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
        Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
        Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
    });
    Route::get('/get-user-group/{id}', 'UserController@getUserGroups')->name("get-user-group");
    Route::get('/get-nurse/{id}', 'DoctorController@getNurses')->name("get-nurse");
Route::get('/getall/{id}', 'OrderController@getAll')->name("getall");
});
