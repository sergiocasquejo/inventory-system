<?php

Route::group(['prefix' => 'admin'], function() {
	Route::get('login', ['uses' => 'Admin\AdminController@login', 'as' => 'admin_login']);
	Route::post('login', ['uses' => 'Admin\AdminController@doLogin', 'as' => 'admin_dologin']);
});