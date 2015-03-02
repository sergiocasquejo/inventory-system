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
		$data['earning_graph'] = \Sale::select(\DB::raw('YEAR(date_of_sale) as the_year,
								  SUM(total_amount) as total_amount,
								  (SUM(CASE WHEN MONTH(date_of_sale) = 1 THEN total_amount - (supplier_price * quantity) ELSE 0 END) / total_amount) * 100 AS Total_Jan,
								  (SUM(CASE WHEN MONTH(date_of_sale) = 2 THEN total_amount  - (supplier_price * quantity) ELSE 0 END) / total_amount) * 100 AS Total_Feb,
								  SUM(CASE WHEN MONTH(date_of_sale) = 3 THEN total_amount  - (supplier_price * quantity) ELSE 0 END) AS Total_Mar,
								  SUM(CASE WHEN MONTH(date_of_sale) = 4 THEN total_amount  - (supplier_price * quantity) ELSE 0 END) AS Total_Apr,
								  SUM(CASE WHEN MONTH(date_of_sale) = 5 THEN total_amount - (supplier_price * quantity) ELSE 0 END) AS Total_May,
								  SUM(CASE WHEN MONTH(date_of_sale) = 6 THEN total_amount - (supplier_price * quantity) ELSE 0 END) AS Total_Jun,
								  SUM(CASE WHEN MONTH(date_of_sale) = 7 THEN total_amount - (supplier_price * quantity) ELSE 0 END) AS Total_Jul,
								  SUM(CASE WHEN MONTH(date_of_sale) = 8 THEN total_amount - (supplier_price * quantity) ELSE 0 END) AS Total_Aug,
								  SUM(CASE WHEN MONTH(date_of_sale) = 9 THEN total_amount - (supplier_price * quantity) ELSE 0 END) AS Total_Sep,
								  SUM(CASE WHEN MONTH(date_of_sale) = 10 THEN total_amount - (supplier_price * quantity) ELSE 0 END) AS Total_Oct,
								  SUM(CASE WHEN MONTH(date_of_sale) = 11 THEN total_amount  - (supplier_price * quantity) ELSE 0 END) AS Total_Nov,
								  SUM(CASE WHEN MONTH(date_of_sale) = 12 THEN total_amount  - (supplier_price * quantity) ELSE 0 END) AS Total_Dec'))
	
								->groupBy('the_year')->first();
		dd($data['earning_graph']->Total_Feb);

		return View::make('admin.dashboard.index', $data);
	}
}