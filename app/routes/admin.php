<?php

Route::group(['before' => 'auth', 'prefix' => 'admin'], function() {
	
	$prefixResourceNamespace = function($prefix) {
		return [
	            'index'   => $prefix.'.index',
	            'create'  => $prefix.'.create',
	            'store'   => $prefix.'.store',
	            'show'    => $prefix.'.show',
	            'edit'    => $prefix.'.edit',
	            'update'  => $prefix.'.update',
	            'destroy' => $prefix.'.destroy'
	        ];

	};


	// Dashboard routes
	Route::get('/', ['uses' => 'Admin/DashboardController@index', 'as' => 'admin_dashboard.index']);
	Route::get('dashboard', ['uses' => 'Admin/DashboardController@index', 'as' => 'admin_dashboard.index']);
	// Users routes
	Route::resource('users', 'Admin/UsersController', ['names' => $prefixResourceNamespace('admin_users'), 'except' => ['show']]);


});