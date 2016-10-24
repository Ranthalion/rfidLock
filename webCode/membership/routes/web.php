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
Route::post('/confirm', 'HomeController@confirmMember')->name('home.confirm');
Route::post('/store', 'HomeController@storeMember')->name('home.store');

Auth::routes();

Route::get('/home', 'MemberController@index');

Route::get('members/inactive', 'MemberController@inactive')->name('members.inactive');
Route::post('members/{member}/restore', 'MemberController@restore')->name('members.restore');

Route::resource('members', 'MemberController', ['except' => ['show']]);

Route::resource('resources', 'ResourceController');

