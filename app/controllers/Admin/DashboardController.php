<?php namespace Admin

class DashboardController extends \BaseController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public static function index() {
		return View::make('admin.dashboard.index');
	}
}