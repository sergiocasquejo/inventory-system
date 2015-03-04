<?php namespace Admin;

class ReportsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$input = \Input::all();

		$from = array_get($input, 'date_from');
		$to = array_get($input, 'date_to');

		$sales_where = $credits_where = $expense_where = '';

		if ($from && $to) {
			$from = date('Y-m-d', strtotime($from));
			$to = date('Y-m -d', strtotime($to));

			$sales_where = "AND (date_of_sale >= '{$from}' AND date_of_sale <= '{$to}')";
			$credits_where = "AND date_of_credit >=  '{$from}'  AND date_of_credit <=  '{$to}'";
			$expense_where = "AND date_of_expense >= '{$from}'  AND date_of_expense <=  '{$to}'";
		}

		$prefix = \DB::getTablePrefix();
		$reports = \DB::select(\DB::raw("SELECT a.id, a.name,
					(SELECT SUM(total_amount) FROM {$prefix}expenses WHERE branch_id = a.id $expense_where ) AS expenses,
					(SELECT SUM(total_amount) FROM {$prefix}credits WHERE branch_id = a.id $credits_where ) AS credits,
					(SELECT SUM(total_amount - (supplier_price * quantity)) FROM {$prefix}sales WHERE branch_id = a.id $sales_where ) AS sales
					FROM sales_branches as a group by a.id"));

		$total_sales = $total_credits = $total_expenses = 0;

		foreach ($reports as $report) {
			$total_sales += $report->sales;
			$total_credits += $report->credits;
			$total_expenses += $report->expenses;
		}

		return \View::make('admin.report.index')
			->with('total_sales', $total_sales)
			->with('total_credits', $total_credits)
			->with('total_expenses', $total_expenses)
			->with('reports', $reports);
	}


	public function stocks() {
		$input = \Input::all();

		$stocks = \StockOnHand::filter($input)->get();
		return \View::make('admin.report.stock')
			->with('branches', array_add(\Branch::filterBranch()->active()->lists('name', 'id'), '', 'Select Branch'))
			->with('stocks', $stocks);
	}




}
