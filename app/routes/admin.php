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
	Route::get('/', ['uses' => 'Admin\DashboardController@index', 'as' => 'admin_dashboard.index']);
	Route::get('dashboard', ['uses' => 'Admin\DashboardController@index', 'as' => 'admin_dashboard.index']);
	// branches routes
	Route::resource('braches', 'Admin\BranchesController', ['names' => $prefixResourceNamespace('admin_branches'), 'except' => ['show']]);
	// Users routes
	Route::resource('users', 'Admin\UsersController', ['names' => $prefixResourceNamespace('admin_users'), 'except' => ['show']]);
	// Categories routes
	Route::group(['prefix' => 'products'], function() use($prefixResourceNamespace) {
		Route::resource('categories', 'Admin\CategoriesController', ['names' => $prefixResourceNamespace('admin_products_categoriess'), 'except' => ['show']]);
	});
	// Products routes
	Route::resource('products', 'Admin\ProductsController', ['names' => $prefixResourceNamespace('admin_products'), 'except' => ['show']]);
	// Expenses routes
	Route::resource('expenses', 'Admin\ExpensesController', ['names' => $prefixResourceNamespace('admin_expenses'), 'except' => ['show']]);
	// Sales routes
	Route::resource('sales', 'Admin\SalesController', ['names' => $prefixResourceNamespace('admin_sales'), 'except' => ['show']]);

});