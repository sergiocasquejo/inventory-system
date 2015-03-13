<?php namespace Admin;
use View;

class DashboardController extends \BaseController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public static function index() {

		if (!\Confide::user()->isAdmin()) {
			return \Redirect::route('admin_users.show', \Confide::user()->id);
		}

		$data = [];


		$data['weekly_expenses'] = \Expense::select(\DB::raw('DAY(date_of_expense) as day_of_expense, TRUNCATE(SUM(total_amount), 2) as weekly_expense'))->groupBy('day_of_expense')->whereRaw('WEEK(date_of_expense) = WEEK(CURDATE())')->lists('weekly_expense');
		$data['weekly_sales'] = \Sale::select(\DB::raw('DAY(date_of_sale) as day_of_sale, TRUNCATE(SUM(total_amount - (supplier_price * quantity)), 2) as weekly_sale'))->groupBy('day_of_sale')->whereRaw('WEEK(date_of_sale) = WEEK(CURDATE())')->lists('weekly_sale');

		$data['total_users']  = \User::count();
		$data['total_expense']  = \Expense::sum('total_amount');
		$data['total_sales'] = \Sale::select(\DB::raw('TRUNCATE(SUM(total_amount - (supplier_price * quantity)), 2) as total_sale'))->pluck('total_sale');

		$data['earning'] = \Sale::select(\DB::raw('YEAR(date_of_sale) as the_year,
TRUNCATE(SUM(total_amount - (supplier_price * quantity)), 2) as total_amount,
TRUNCATE((SUM(CASE WHEN MONTH(date_of_sale) = 1 THEN total_amount - (supplier_price * quantity) ELSE 0 END) / total_amount) * 100, 2) AS Total_Jan,
TRUNCATE((SUM(CASE WHEN MONTH(date_of_sale) = 2 THEN total_amount  - (supplier_price * quantity) ELSE 0 END) / total_amount) * 100, 2) AS Total_Feb,
TRUNCATE((SUM(CASE WHEN MONTH(date_of_sale) = 3 THEN total_amount  - (supplier_price * quantity) ELSE 0 END)/ total_amount) * 100, 2) AS Total_Mar,
TRUNCATE((SUM(CASE WHEN MONTH(date_of_sale) = 4 THEN total_amount  - (supplier_price * quantity) ELSE 0 END)/ total_amount) * 100, 2) AS Total_Apr,
TRUNCATE((SUM(CASE WHEN MONTH(date_of_sale) = 5 THEN total_amount - (supplier_price * quantity) ELSE 0 END)/ total_amount) * 100, 2) AS Total_May,
TRUNCATE((SUM(CASE WHEN MONTH(date_of_sale) = 6 THEN total_amount - (supplier_price * quantity) ELSE 0 END)/ total_amount) * 100, 2) AS Total_Jun,
TRUNCATE((SUM(CASE WHEN MONTH(date_of_sale) = 7 THEN total_amount - (supplier_price * quantity) ELSE 0 END)/ total_amount) * 100, 2) AS Total_Jul,
TRUNCATE((SUM(CASE WHEN MONTH(date_of_sale) = 8 THEN total_amount - (supplier_price * quantity) ELSE 0 END) / total_amount) * 100, 2) AS Total_Aug,
TRUNCATE((SUM(CASE WHEN MONTH(date_of_sale) = 9 THEN total_amount - (supplier_price * quantity) ELSE 0 END)/ total_amount) * 100, 2) AS Total_Sep,
TRUNCATE((SUM(CASE WHEN MONTH(date_of_sale) = 10 THEN total_amount - (supplier_price * quantity) ELSE 0 END) / total_amount) * 100, 2) AS Total_Oct,
TRUNCATE((SUM(CASE WHEN MONTH(date_of_sale) = 11 THEN total_amount  - (supplier_price * quantity) ELSE 0 END)/ total_amount) * 100, 2) AS Total_Nov,
TRUNCATE((SUM(CASE WHEN MONTH(date_of_sale) = 12 THEN total_amount  - (supplier_price * quantity) ELSE 0 END)/ total_amount) * 100, 2) AS Total_Dec'))
	
								->groupBy('the_year')->first();

		// SELECT SUM(total_amount), (SELECT SUM(total_amount) FROM sales_expenses WHERE DAY(date_of_expense) = DAY(sales_sales.date_of_sale) GROUP BY DAY(date_of_expense)) as ab FROM `sales_sales` GROUP BY DAY(date_of_sale)

		// dd($data['weekly_sales']);

		return View::make('admin.dashboard.index', $data);
	}
}