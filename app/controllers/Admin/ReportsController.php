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

        $branch = array_get($input, 'branch');
		$from = array_get($input, 'date_from');
		$to = array_get($input, 'date_to');

		$branch_where = $sales_where = $credits_where = $expense_where = '';

        if ($branch) {
            $branch_where = " AND a.id = $branch";
        }

		if ($from && $to) {
			$from = date('Y-m-d', strtotime($from));
			$to = date('Y-m -d', strtotime($to));

			$sales_where = "AND (date_of_sale >= '{$from}' AND date_of_sale <= '{$to}')";
			$credits_where = "AND (date_of_sale >= '{$from}' AND date_of_sale <= '{$to}')";
			$expense_where = "AND date_of_expense >= '{$from}'  AND date_of_expense <=  '{$to}'";
		}





		$prefix = \DB::getTablePrefix();


        /*
         * $reports = \DB::select(\DB::raw("SELECT a.id, CONCAT(a.name, '(', a.address,')') as name,
					(SELECT SUM(total_amount) FROM {$prefix}expenses WHERE branch_id = a.id $expense_where ) AS expenses,
					(SELECT SUM(total_amount - (supplier_price * quantity)) FROM {$prefix}sales WHERE sale_type= 'CREDIT' AND branch_id = a.id $credits_where ) AS credits,
					(SELECT SUM(total_amount - (supplier_price * quantity)) FROM {$prefix}sales WHERE sale_type= 'SALE' AND branch_id = a.id $sales_where ) AS sales
					FROM {$prefix}branches as a WHERE a.deleted_at IS NULL AND a.status = 1 $branch_where group by a.id"));
         */



		$reports = \DB::select(\DB::raw("SELECT a.id, CONCAT(a.name, '(', a.address,')') as name,
					(SELECT SUM(total_amount) FROM {$prefix}expenses WHERE branch_id = a.id $expense_where ) AS expenses,
					(SELECT SUM(total_amount) FROM {$prefix}sales WHERE sale_type= 'CREDIT' AND branch_id = a.id $credits_where ) AS credits,
					(SELECT SUM(total_amount) FROM {$prefix}sales WHERE sale_type= 'SALE' AND branch_id = a.id $sales_where ) AS sales
					FROM {$prefix}branches as a WHERE a.deleted_at IS NULL AND a.status = 1 $branch_where  group by a.id"));

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
			->with('branches', array_add(\Branch::filterBranch()->select(\DB::raw('CONCAT(name, " ", address) as name'), 'id')->lists('name', 'id'), '', 'Select Branch'))
			->with('reports', $reports);
	}


	// public function stocks() {
	// 	$input = \Input::all();

	// 	$stocks = \StockOnHand::filter($input)
	// 		->select('stocks_on_hand.stock_on_hand_id', 'stocks_on_hand.uom', 
	// 			'stocks_on_hand.total_stocks',
	// 			'products.name as product_name', 
	// 			'branches.name as branch_name', 'branches.address')
	// 		->join('products', 'stocks_on_hand.product_id', '=', 'products.id')
	// 		->join('branches', 'branches.id', '=', 'stocks_on_hand.branch_id')
	// 		->orderBy('branch_id', 'asc')->get();

	// 	$newStocks = [];
	// 	foreach ($stocks as $stock) {
	// 		$prod_name = $stock->product_name;
	// 		$branch_name = $stock->branch_name.'('.$stock->address.')';

			


	// 		$total_stocks = $stockStr = $stock->total_stocks.' '. $stock->uom;
	// 		$sackStr = 'N/A';

	// 		if ($stock->uom == 'kg') {
	// 			//1 Sack equivalent
	// 			$sackEqui = \Config::get('agrivet.equivalent_measure.sacks.per');

	// 			$sack = 0;
	// 			$quantity = (float)$stock->total_stocks / (float)$sackEqui;

	// 			$total_stocks = '';

	// 			if ($stock->total_stocks  >= $sackEqui) {
	// 				$sack = floor( $quantity );
	// 				$total_stocks = $sackStr = $sack .' sack(s)';
	// 			}

				
	// 			$kg = ($quantity - $sack) * $sackEqui;


	// 			if ($kg != 0) {
	// 				$stockStr = $kg .' '. $stock->uom;
	// 				$total_stocks .= ($sack != 0) ? ' and '. $stockStr :$stockStr;
	// 			} else {
	// 				$stockStr = $kg .' '. $stock->uom;
	// 			}

				 
	// 		}

	// 		$newStocks[] = [
	// 			'branch' => $branch_name,
	// 			'product_name' => $prod_name,
	// 			'other_stock' => $stockStr,
	// 			'sack_stock' => $sackStr,
	// 			'total_stocks' => $total_stocks,
	// 		];
	// 	}


	// 	return \View::make('admin.report.stock')
	// 		->with('branches', array_add(\Branch::dropdown()->lists('name', 'id'), '', 'Select Branch'))
	// 		->with('stocks', $newStocks);
	// }




}
