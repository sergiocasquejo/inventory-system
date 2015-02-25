<?php

Route::group(['before' => 'auth|admin', 'prefix' => 'admin'], function() {
	
	$prefixResourceNamespace = function($prefix) {
		return [
	            'index'   => $prefix.'.index',
	            'create'  => $prefix.'.create',
	            'store'   => $prefix.'.store',
	            'show'    => $prefix.'.show',
	            'edit'    => $prefix.'.edit',
	            'update'  => $prefix.'.update',
	            'restore' => $prefix.'.restore',
	            'destroy' => $prefix.'.destroy'
	        ];

	};


	// Dashboard routes
	Route::get('/', ['uses' => 'Admin\DashboardController@index', 'as' => 'admin_dashboard.index']);
	Route::get('dashboard', ['uses' => 'Admin\DashboardController@index', 'as' => 'admin_dashboard.index']);
	// branches routes
	Route::post('branches/{id}/restore', ['uses' => 'Admin\BranchesController@restore', 'as' => 'admin_branches.restore']);
	Route::resource('branches', 'Admin\BranchesController', ['names' => $prefixResourceNamespace('admin_branches'), 'except' => ['show']]);
	// Users routes
	Route::resource('users', 'Admin\UsersController', ['names' => $prefixResourceNamespace('admin_users'), 'except' => ['show']]);
	// Categories routes
	Route::group(['prefix' => 'products'], function() use($prefixResourceNamespace) {
		Route::resource('categories', 'Admin\CategoriesController', ['names' => $prefixResourceNamespace('admin_products_categoriess'), 'except' => ['show']]);
	});
	Route::group(['prefix' => 'products/{pid}'], function() use($prefixResourceNamespace) {
		Route::resource('stocks', 'Admin\StockOnHandController', ['names' => $prefixResourceNamespace('admin_product_stocks'), 'except' => ['show']] );
		Route::resource('prices', 'Admin\PricesController', ['names' => $prefixResourceNamespace('admin_product_prices'), 'except' => ['show']] );
	});
	// Products routes
	Route::post('products/{id}/restore', ['uses' => 'Admin\ProductsController@restore', 'as' => 'admin_products.restore']);
	Route::resource('products', 'Admin\ProductsController', ['names' => $prefixResourceNamespace('admin_products'), 'except' => ['show']]);
	// Brands routes
	Route::get('brands/{id}/categories', ['uses' => 'Admin\BrandsController@getCategories', 'as' => 'admin_brands.categories']);
	Route::resource('brands', 'Admin\BrandsController', ['names' => $prefixResourceNamespace('admin_brands'), 'except' => ['show']]);
	// Categories routes
	Route::resource('categories', 'Admin\CategoriesController', ['names' => $prefixResourceNamespace('admin_categories'), 'except' => ['show']]);
	// Credits routes
	Route::resource('credits', 'Admin\CreditsController', ['names' => $prefixResourceNamespace('admin_credits'), 'except' => ['show']]);
	// Expenses routes
	Route::resource('expenses', 'Admin\ExpensesController', ['names' => $prefixResourceNamespace('admin_expenses'), 'except' => ['show']]);
	// Sales routes
	Route::resource('sales', 'Admin\SalesController', ['names' => $prefixResourceNamespace('admin_sales'), 'except' => ['show']]);

});
