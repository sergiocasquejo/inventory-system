<?php namespace Admin;
use View;

class DashboardController extends \BaseController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public static function index() {
		$data = [];



		$data['total_users']  = \User::count();
		$data['total_expense']  = \Expense::sum('total_amount');
		$data['total_sales']  = \Sale::sum('total_amount');
		

		return View::make('admin.dashboard.index', $data);
	}
}