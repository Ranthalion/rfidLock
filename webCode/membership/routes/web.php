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

Auth::routes();

Route::get('/', 'MemberController@index');

Route::get('/home', 'MemberController@index');

Route::get('members/inactive', 'MemberController@inactive')->name('members.inactive');
Route::post('members/{member}/restore', 'MemberController@restore')->name('members.restore');

Route::resource('members', 'MemberController',
	['except' => ['show']]);

Route::resource('resources', 'ResourceController');

/*
Route::get('/addMember', 'MemberController@create');

Route::post('/addMember', 'MemberController@store');
*/