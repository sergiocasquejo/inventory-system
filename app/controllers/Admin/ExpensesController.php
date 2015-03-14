<?php namespace Admin;

class ExpensesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$input = \Input::all();


		$expenses = \Expense::withTrashed()
		->filter($input)
		->search($input)
		->owned()
		->orderBy('expense_id', 'desc')
		->paginate(intval(array_get($input, 'records_per_page', 10)));
		


		$totalRows = \Expense::owned()->count();

		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		$countries = \Config::get('agrivate.countries');


		$yearly = \Expense::owned()->whereRaw('YEAR(date_of_expense) = YEAR(CURDATE())')->sum('total_amount');
		$monthly = \Expense::owned()->whereRaw('MONTH(date_of_expense) = MONTH(CURDATE())')->sum('total_amount');
		$weekly = \Expense::owned()->whereRaw('WEEK(date_of_expense) = WEEK(CURDATE())')->sum('total_amount');
		$daily = \Expense::owned()->whereRaw('DAY(date_of_expense) = DAY(CURDATE())')->sum('total_amount');


		$branches = \DB::table('expenses')->join('branches', 'expenses.branch_id', '=', 'branches.id')
					->select(\DB::raw('CONCAT('.\DB::getTablePrefix().'branches.address," ", '.\DB::getTablePrefix().'branches.city) AS name, '.\DB::getTablePrefix().'branches.id'));


					
		// Filter branch if user is not owner
		if (!\Confide::user()->isAdmin()) {
			$branches = $branches->where('branches.id', \Confide::user()->branch_id);
		}


		$all = [
			'daily' => $daily,
			'weekly' => $weekly,
			'monthly' => $monthly,
			'yearly' => $yearly,
			'branches' =>array_add($branches->lists('name', 'id'), '', 'Branch'),
			'totals' => array_add(\Expense::filterBranch()->lists('total_amount', 'total_amount'), '', 'Amount'),
			'days' => array_add(\Expense::filterBranch()->select(\DB::raw('DAY(date_of_expense) as day'))->lists('day', 'day'), '', 'Day'),
			'months' => array_add(\Expense::filterBranch()->select(\DB::raw('DATE_FORMAT(date_of_expense, "%b") as month, MONTH(date_of_expense) as month_no'))->lists('month', 'month_no'), '', 'Month'),
			'years' => array_add(\Expense::filterBranch()->select(\DB::raw('YEAR(date_of_expense) as year'))->lists('year', 'year'), '', 'Year'),
			'statuses' => array_add(\Expense::filterBranch()->select(\DB::raw('status, IF (status = 1, \'Active\', \'Inactive\') as name'))->lists('name', 'status'), '', 'Status'),
		];


		return \View::make('admin.expense.index', $all)
			->with('expenses', $expenses)
			->with('appends', $appends)
			->with('totalRows', $totalRows);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		return \View::make('admin.expense.create')
			->with('reviews', \Session::get('expensesReview'))
			->with('branches', \Branch::filterBranch()->dropdown()->lists('name', 'id'))
			->with('measures', array_add(\UnitOfMeasure::all()->lists('label', 'name'), '', 'Select Measure'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = \Input::all();

		if (array_get($input, 'action') == 'review') {
			$reviewId = false;
			if (array_get($input, 'review_id')) {
				$reviewId = array_get($input, 'review_id');
			}
			return $this->review($reviewId);
		}


		$rules = \Expense::$rules;
		if (!\Confide::user()->isAdmin()) {
			$input['branch_id'] = \Confide::user()->branch_id;
		}

		if (array_get($input, 'expense_type') == 'STORE EXPENSES') {
			$rules['uom'] = 'whole_number:quantity';
		}

		$input['encoded_by'] = \Confide::user()->id;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$expense = new \Expense;

				if ($expense->doSave($expense, $input)) {
					return \Redirect::route('admin_expenses.index')->with('success', \Lang::get('agrivate.created'));
				}

				return \Redirect::back()->withErrors($expense->errors())->withInput();
			} catch(\Exception $e) {
				return \Redirect::back()->withErrors((array)$e->getMessage())->withInput();
			}
		}
	}



	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

		try {
			$expense = \Expense::findOrFail($id);
		} catch(\Exception $e) {
			return \Redirect::back()->with('info', \Lang::get('agrivate.errors.restore'));
		}

		return \View::make('admin.expense.edit')
			->with('expense', $expense)
			->with('branches', \Branch::filterBranch()->dropdown()->lists('name', 'id'))
			->with('measures', array_add(\UnitOfMeasure::all()->lists('label', 'name'), '', 'Select Measure'));
	}




	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{


		$input = \Input::all();

		if (!\Confide::user()->isAdmin()) {
			$input['branch_id'] = \Confide::user()->branch_id;
		}

		if (array_get($input, 'expense_type') == 'STORE EXPENSES') {
			$rules['uom'] = 'whole_number:quantity';
		}

		$rules = \Expense::$rules;

		$input['encoded_by'] = \Confide::user()->id;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$expense = \Expense::findOrFail($id);
				
				if ($expense->doSave($expense, $input)) {
					return \Redirect::route('admin_expenses.index')->with('success', \Lang::get('agrivate.updated'));
				}

				return \Redirect::back()->withErrors($expense->errors())->withInput();
			} catch(\Exception $e) {
				return \Redirect::back()->withErrors((array)$e->getMessage())->withInput();
			}
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$expense = \Expense::withTrashed()->where('expense_id', $id)->first();
		$message = \Lang::get('agrivate.trashed');
		if ($expense->trashed() || \Input::get('remove') == 1) {
            $expense->forceDelete();
            $message = \Lang::get('agrivate.deleted');
        } else {
            $expense->delete();
        }

        // Session::set('success', 'Successfully deleted');
        return \Redirect::route('admin_expenses.index')->with('success', $message);
        
	}


	/**
	 * Restore deleted resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function restore($id) {
		$expense = \Expense::withTrashed()->where('expense_id', $id)->first();
		if (!$expense->restore()) {
			return \Redirect::back()->withErrors($expense->errors());			
		}

		return \Redirect::back()->with('success', \Lang::get('agrivate.restored'));
	}


	public function review($reviewId = false, $remove = false) {
		$input = \Input::all();


		$rules = \Sale::$rules;

		if (!\Confide::user()->isAdmin()) {
			$input['branch_id'] = \Confide::user()->branch_id;
		}

		$input['encoded_by'] = \Confide::user()->id;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {

				$review = [];

				if (\Session::has('salesReview')) {
					$review = \Session::get('salesReview');	
				}
				
				if (!$reviewId) {
					$reviewId = time();
				}

				$review[$reviewId] = array_add(\Input::only(
						'branch_id', 
						'product_id', 
						'uom', 
						'quantity', 
						'total_amount', 
						'comments',
						'date_of_sale',
						'status'), 'branch_id', $input['branch_id']);


				\Session::put('salesReview', $review);
				

				return \Redirect::route('admin_sales.create')->with('success', \Lang::get('agrivate.add_to_review'));

			} catch(\Exception $e) {
				return \Redirect::back()->withErrors((array)$e->getMessage())->withInput($input);
			}
		}
	}

	public function deleteReview($reviewId) {
		\Session::forget("salesReview.$reviewId");

		return \Redirect::route('admin_sales.create')->with('success', \Lang::get('agrivate.deleted'));
	}

	public function saveReview() {
		try {
			$reviews = \Session::get('salesReview');

			foreach ($reviews as $key => $input) {
				$errors = [];
				$input['encoded_by'] = \Confide::user()->id;
				\DB::transaction(function() use(&$input, &$errors) {
					

					// Get user branch
					$branch_id = array_get($input, 'branch_id');
					$uom = array_get($input, 'uom');
					$product = array_get($input, 'product_id');
					$quantity = array_get($input, 'quantity');
					$oldMeasure = '';

					$p = \Product::find($product);

					// Convert sack to kg
					if (strpos($uom,'sack') !== false) {
						$oldMeasure = $input['uom'];
						$input['quantity'] = $quantity * \Config::get('agrivate.equivalent_measure.sacks.per');
						$input['uom'] = $uom = 'kg';
					}

					$stock = \StockOnHand::where('product_id', $product)
									->where('branch_id', $branch_id)
									->where('uom', $uom)
									->first();

		
					if ($stock && $stock->total_stocks > 0) {

						if ($stock->total_stocks >= array_get($input, 'quantity', 0)) {
							$branch = \ProductPricing::whereRaw("branch_id = {$branch_id} AND product_id = {$product}  AND per_unit = '{$uom}'")->first();

							$input['supplier_price'] = 	$branch->supplier_price;
							$input['selling_price'] = 	$branch->selling_price;
							$input['total_amount'] = 	$branch->selling_price * $input['quantity'];


							$sale = new \Sale;

							if (!$sale->doSave($sale, $input)) {			
								$errors[] = $sale->errors();
							} else {
								$stock->total_stocks = $stock->total_stocks - array_get($input, 'quantity');
								$stock->save();
							}
						} else {
							if (strpos($oldMeasure, 'sack') !== false) $input['uom'] = $oldMeasure;
							$errors[] = [$p->name.' '.\Lang::get('agrivate.errors.insufficient_stocks', ['stocks' => $stock->total_stocks .' '.$uom])];
						}

					} else {
						if (strpos($oldMeasure,'sack') !== false) $input['uom'] = $oldMeasure;
						$errors[] = [$p->name.' '.\Lang::get('agrivate.errors.out_of_stocks')];

					}
				});
				
				if (count($errors) == 0) {
					\Session::forget("salesReview.$key");
				}

			}
				

			if (count($errors) == 0) {
				return \Redirect::route('admin_sales.create')->with('success', \Lang::get('agrivate.created'));
			} else {

				return \Redirect::back()->withErrors($errors);
			}


		} catch (\Exception $e) {
			return \Redirect::back()->withErrors((array)$e->getMessage());
		}
	}

}
