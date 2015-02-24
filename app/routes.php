<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
require_once 'routes/auth.php';
require_once 'routes/errors.php';
require_once 'routes/admin.php';

Route::get('/', function()
{
	if (Auth::check())
		return Redirect::route('admin_login');
	else
		return Redirect::to('users/login');
});
