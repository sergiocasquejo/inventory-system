<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
| http://screencast.com/t/7TJ0aRPMcxhD
| http://screencast.com/t/QLQAZ8wsPWb3
| http://screencast.com/t/Y86ytze5jBu
| http://kmagrivet.ianquijano.com/
*/
require_once 'routes/auth.php';
require_once 'routes/admin.php';
require_once 'routes/errors.php';

Route::get('/', function()
{
	if (Auth::check())
		return Redirect::route('admin_login');
	else
		return Redirect::to('admin/login');
});
