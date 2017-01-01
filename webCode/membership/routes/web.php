<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'HomeController@addMember');
Route::post('/validate', 'HomeController@checkForPayment')->name('home.validate');
Route::get('/confirm', 'HomeController@confirmMember')->name('home.confirm');
Route::post('/store', 'HomeController@storeMember')->name('home.store');

Auth::routes();

Route::get('/home', 'MemberController@index');

Route::get('members/inactive', 'MemberController@inactive')->name('members.inactive');
Route::post('members/{member}/restore', 'MemberController@restore')->name('members.restore');

Route::get('members/{member}/changeKey', 'MemberController@changeKey')->name('members.changeKey');
Route::post('members/{member}/updateKey', 'MemberController@updateKey')->name('members.updateKey');

Route::get('reports/phpInfo', 'ReportsController@phpInfo')->name('reports.phpInfo');
Route::get('reports/payments', 'ReportsController@payments')->name('reports.payments');

Route::resource('members', 'MemberController', ['except' => ['show']]);

Route::resource('resources', 'ResourceController');

