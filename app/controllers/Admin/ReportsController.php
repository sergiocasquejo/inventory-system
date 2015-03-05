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
		$reports = \DB::select(\DB::raw("SELECT a.id, CONCAT(a.name, '(', a.address,')') as name,
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



		// $stocks = \DB::select(\DB::raw(''))

		$stocks = \StockOnHand::filter($input)->orderBy('branch_id', 'asc')->get();

		$newStocks = [];
		$oldname = '';
		$oldbranch = '';
		foreach ($stocks as $stock) {
			$prod_name = $stock->product->name;
			$branch_name = $stock->branch->name.'('.$stock->branch->address.')';

			if ($oldname == $prod_name) {
				$prod_name = '.....';
			} else {
				$oldname = $prod_name;
			}
			
			if ($oldbranch == $branch_name) {
				$branch_name = '.....';
			} else {
				$oldbranch = $branch_name;
			}


			$newStocks[] = [
				'branch' => $branch_name,
				'product_name' => $prod_name,
				'stocks' =>\Helper::nf($stock->total_stocks, 2, '.', ', ', false).' '.$stock->uom,
			];
		}


		return \View::make('admin.report.stock')
			->with('branches', array_add(\Branch::dropdown(), '', 'Select Branch'))
			->with('stocks', $newStocks);
	}




}
