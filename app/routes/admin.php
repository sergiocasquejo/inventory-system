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
	            'restore' => $prefix.'.restore',
	            'destroy' => $prefix.'.destroy'
	        ];

	};

	// Dashboard routes
	Route::get('/', ['uses' => 'Admin\DashboardController@index', 'as' => 'admin_dashboard.index']);
	Route::get('dashboard', ['uses' => 'Admin\DashboardController@index', 'as' => 'admin_dashboard.index']);

	Route::group(['before' => 'owner'], function() use ($prefixResourceNamespace){	
		// branches routes
		Route::post('branches/{id}/restore', ['uses' => 'Admin\BranchesController@restore', 'as' => 'admin_branches.restore']);
		Route::resource('branches', 'Admin\BranchesController', ['names' => $prefixResourceNamespace('admin_branches'), 'except' => ['show']]);
		
		// Categories routes
		Route::group(['prefix' => 'products'], function() use($prefixResourceNamespace) {
			Route::resource('categories', 'Admin\CategoriesController', ['names' => $prefixResourceNamespace('admin_products_categoriess'), 'except' => ['show']]);
		});
		Route::group(['prefix' => 'products/{pid}'], function() use($prefixResourceNamespace) {
			Route::resource('stocks', 'Admin\StockOnHandController', ['names' => $prefixResourceNamespace('admin_product_stocks'), 'except' => ['show']] );
			Route::resource('prices', 'Admin\PricesController', ['names' => $prefixResourceNamespace('admin_product_prices'), 'except' => ['show']] );
		});
		// Products routes
        Route::post('products/suppliers-product/', ['uses' => 'Admin\ProductsController@getBySupplier', 'as' => 'admin_products.by_supplier']);
		Route::post('products/{id}/restore', ['uses' => 'Admin\ProductsController@restore', 'as' => 'admin_products.restore']);
		Route::resource('products', 'Admin\ProductsController', ['names' => $prefixResourceNamespace('admin_products'), 'except' => ['show']]);
		// Brands routes
		Route::get('brands/{id}/categories', ['uses' => 'Admin\BrandsController@getCategories', 'as' => 'admin_brands.categories']);
		Route::resource('brands', 'Admin\BrandsController', ['names' => $prefixResourceNamespace('admin_brands'), 'except' => ['show']]);
		// Categories routes
		Route::resource('categories', 'Admin\CategoriesController', ['names' => $prefixResourceNamespace('admin_categories'), 'except' => ['show']]);
		
		Route::resource('uoms', 'Admin\UnitOfMeasuresController', ['names' => $prefixResourceNamespace('admin_uoms'), 'except' => ['show']]);

        //Supplier Routes
        Route::post('suppliers/list-by-branch', ['uses' => 'Admin\SuppliersController@getByBranch', 'as' => 'admin_suppliers.by_branch']);
        Route::resource('suppliers', 'Admin\SuppliersController', ['names' => $prefixResourceNamespace('admin_suppliers')]);

	});
    // Customers routeslist
    Route::get('customers/lists', ['uses' => 'Admin\CustomersController@lists', 'as' => 'admin_customers.list']);
    Route::resource('customers', 'Admin\CustomersController', ['names' => $prefixResourceNamespace('admin_customers')]);
	Route::get('products/dropdown', ['uses' => 'Admin\ProductsController@dropdown', 'as' => 'admin_products.dropdown']);
	Route::get('uoms/dropdown', ['uses' => 'Admin\UnitOfMeasuresController@dropdown', 'as' => 'admin_uoms.dropdown']);
	
	Route::get('products/{id}/measures', ['uses' => 'Admin\ProductsController@measures', 'as' => 'admin_products.measures']);
	Route::get('products/{id}/uom', ['uses' => 'Admin\ProductsController@uom', 'as' => 'admin_products.uom']);
	Route::get('products/{id}/get', ['uses' => 'Admin\ProductsController@get', 'as' => 'admin_products.get']);
	// Credits routes

    Route::post('credits/partial-payment', ['uses' => 'Admin\CreditsController@partialPayment', 'as' => 'admin_credits.partails'])->before('csrf');
    Route::get('credits/info-by-customer/{id}', ['uses' => 'Admin\CreditsController@infoByCusId', 'as' => 'admin_credits.info_by_cusid']);
    Route::post('credits/payables-partial-payment', ['uses' => 'Admin\PayablesController@partialPayablePayment', 'as' => 'admin_credits.partail_payables'])->before('csrf');
    Route::get('credits/info-by-supplier/{id}', ['uses' => 'Admin\PayablesController@infoBySupplierId', 'as' => 'admin_credits.info_by_supplier']);
    Route::put('credits/payables/{id}/paid', ['uses' => 'Admin\PayablesController@paid', 'as' => 'admin_credits.payables_paid']);
    Route::put('credits/payables/{id}/update', ['uses' => 'Admin\PayablesController@update', 'as' => 'admin_credits.payables_update']);
    Route::get('credits/payables/{id}/edit', ['uses' => 'Admin\PayablesController@edit', 'as' => 'admin_credits.payables_edit']);
    Route::delete('credits/payables/{id}/destroy', ['uses' => 'Admin\PayablesController@destroy', 'as' => 'admin_credits.payables_destroy']);

    Route::get('credits/payables/details', ['uses' => 'Admin\PayablesController@details', 'as' => 'admin_credits.payable_details']);
    Route::get('credits/payables', ['uses' => 'Admin\PayablesController@index', 'as' => 'admin_credits.payables']);
	Route::post('credits/save-review', ['uses' => 'Admin\CreditsController@saveReview', 'as' => 'admin_credits.saveReview']);
	Route::get('credits/{id}/delete-review', ['uses' => 'Admin\CreditsController@deleteReview', 'as' => 'admin_credits.deleteReview']);
	Route::post('credits/{id}/restore', ['uses' => 'Admin\CreditsController@restore', 'as' => 'admin_credits.restore']);
	Route::resource('credits', 'Admin\CreditsController', ['names' => $prefixResourceNamespace('admin_credits'), 'except' => ['show']]);
	// Expenses routes
	Route::post('expenses/save-review', ['uses' => 'Admin\ExpensesController@saveReview', 'as' => 'admin_expenses.saveReview']);
	Route::get('expenses/{id}/delete-review', ['uses' => 'Admin\ExpensesController@deleteReview', 'as' => 'admin_expenses.deleteReview']);
	Route::post('expenses/{id}/restore', ['uses' => 'Admin\ExpensesController@restore', 'as' => 'admin_expenses.restore']);
	Route::resource('expenses', 'Admin\ExpensesController', ['names' => $prefixResourceNamespace('admin_expenses'), 'except' => ['show']]);
	// Sales routes
	Route::post('sales/save-review', ['uses' => 'Admin\SalesController@saveReview', 'as' => 'admin_sales.saveReview']);
	Route::get('sales/{id}/delete-review', ['uses' => 'Admin\SalesController@deleteReview', 'as' => 'admin_sales.deleteReview']);
	Route::post('sales/{id}/restore', ['uses' => 'Admin\SalesController@restore', 'as' => 'admin_sales.restore']);
	Route::resource('sales', 'Admin\SalesController', ['names' => $prefixResourceNamespace('admin_sales'), 'except' => ['show']]);

	// Route::get('reports/stocks', ['uses' => 'Admin\ReportsController@stocks', 'as' => 'admin_reports.stocks']);
	Route::get('reports', ['uses' => 'Admin\ReportsController@index', 'as' => 'admin_reports.index']);

	Route::resource('stocks', 'Admin\StockOnHandController', ['names' => $prefixResourceNamespace('admin_stocks'), 'except' => ['show']]);


	// Users routes
	Route::post('users/{id}/restore', ['uses' => 'Admin\UsersController@restore', 'as' => 'admin_users.restore']);
	Route::resource('users', 'Admin\UsersController', ['names' => $prefixResourceNamespace('admin_users')]);
});
