<?php

Route::group(['prefix' => 'admin'], function() {
	Route::get('login', ['uses' => 'Admin\AdminController@login', 'as' => 'admin_login']);
	Route::post('login', ['uses' => 'Admin\AdminController@doLogin', 'as' => 'admin_dologin']);
	Route::get('logout', ['uses' => 'Admin\AdminController@logout', 'as' => 'admin_logout']);
});

// Confide routes
Route::get('users/create', 'UsersController@create');
Route::post('users', 'UsersController@store');
Route::get('users/login', ['uses' => 'Admin\AdminController@login', 'as' => 'admin_login']);
Route::post('users/login', ['uses' => 'Admin\AdminController@doLogin', 'as' => 'admin_dologin']);
Route::get('users/confirm/{code}', 'UsersController@confirm');
Route::get('users/forgot_password', 'UsersController@forgotPassword');
Route::post('users/forgot_password', 'UsersController@doForgotPassword');
Route::get('users/reset_password/{token}', 'UsersController@resetPassword');
Route::post('users/reset_password', 'UsersController@doResetPassword');
Route::get('users/logout', 'UsersController@logout');