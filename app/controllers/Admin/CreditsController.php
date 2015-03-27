<?php namespace Admin;

class CreditsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{


		

		$input = \Input::all();


		$credits = \Credit::withTrashed()->join('sales', 'credits.sale_id', '=', 'sales.sale_id')
			->filter($input)
			->search($input)
			->owned()
			->orderBy('credit_id', 'desc')
			->paginate(intval(array_get($input, 'records_per_page', 10)));
		
		$totalRows = \Credit::withTrashed()->owned()->count();

		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		// $countries = \Config::get('agrivet.countries');



		$yearly = \Credit::owned()->join('sales', 'credits.sale_id', '=', 'sales.sale_id')->whereRaw('sale_type = "CREDIT" AND YEAR(date_of_sale) = YEAR(CURDATE())')->sum('total_amount');
		$monthly = \Credit::owned()->join('sales', 'credits.sale_id', '=', 'sales.sale_id')->whereRaw('sale_type = "CREDIT" AND MONTH(date_of_sale) = MONTH(CURDATE())')->sum('total_amount');
		$weekly = \Credit::owned()->join('sales', 'credits.sale_id', '=', 'sales.sale_id')->whereRaw('sale_type = "CREDIT" AND WEEK(date_of_sale) = WEEK(CURDATE())')->sum('total_amount');
		$daily = \Credit::owned()->join('sales', 'credits.sale_id', '=', 'sales.sale_id')->whereRaw('sale_type = "CREDIT" AND DAY(date_of_sale) = DAY(CURDATE())')->sum('total_amount');

		$branches = \DB::table('expenses')->join('branches', 'expenses.branch_id', '=', 'branches.id')
					->select(\DB::raw('CONCAT(SUBSTRING('.\DB::getTablePrefix().'branches.name, 1, 20),"...") AS name, '.\DB::getTablePrefix().'branches.id'));


					
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
			'totals' => array_add(\Credit::filterBranch()->join('sales', 'credits.sale_id', '=', 'sales.sale_id')->lists('total_amount', 'total_amount'), '', 'Amount'),
			'days' => array_add(\Credit::filterBranch()->join('sales', 'credits.sale_id', '=', 'sales.sale_id')->select(\DB::raw('DAY(date_of_sale) as day'))->lists('day', 'day'), '', 'Day'),
			'months' => array_add(\Credit::filterBranch()->join('sales', 'credits.sale_id', '=', 'sales.sale_id')->select(\DB::raw('DATE_FORMAT(date_of_sale, "%b") as month, MONTH(date_of_sale) as month_no'))->lists('month', 'month_no'), '', 'Month'),
			'years' => array_add(\Credit::filterBranch()->join('sales', 'credits.sale_id', '=', 'sales.sale_id')->select(\DB::raw('YEAR(date_of_sale) as year'))->lists('year', 'year'), '', 'Year'),
			'statuses' => array_add(\Credit::filterBranch()->select(\DB::raw('is_paid, IF (is_paid = 1, \'Paid\', \'Not Paid\') as name'))->lists('name', 'is_paid'), '', 'Is Paid?'),
		];



		return \View::make('admin.credit.index', $all)
			->with('credits', $credits)
			->with('branches', \Branch::filterBranch()->select(\DB::raw('CONCAT(address, " ", city) as name'), 'id')->lists('name', 'id'))
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
		return \View::make('admin.credit.create')
		->with('reviews', \Session::get('creditsReview'))
		->with('branches', \Branch::filterBranch()->dropdown()->lists('name', 'id'))
		->with('products', array_add(\Product::all()->lists('name', 'id'), '0', 'Select Product'))
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


		$input['encoded_by'] = \Confide::user()->id;

		if (!\Confide::user()->isAdmin()) {
			$input['branch_id'] = \Confide::user()->branch_id;
		}
		$input['is_paid'] = 0;

		$rules = \Credit::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {

				$errors = [];

				\DB::transaction(function() use(&$input, &$errors) {
					

					// Get user branch
					$branch_id = array_get($input, 'branch_id');
					$uom = array_get($input, 'uom');
					$product = array_get($input, 'product_id');
					$quantity = array_get($input, 'quantity');

					// Convert sack to kg
					if (strpos($uom,'sack') !== false) {
						$oldMeasure = $input['uom'];
						$input['quantity'] = $quantity * \Config::get('agrivet.equivalent_measure.sacks.per');
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
							$input['sale_type'] = 	"CREDIT";
							$input['encoded_by'] 	= 	\Confide::user()->id;

							$sale = new \Sale;

							if (!$sale->doSave($sale, $input)) {			
								$errors = $sale->errors();
							} else {
								$stock->total_stocks = $stock->total_stocks - array_get($input, 'quantity');
								$stock->save();

								$credit = new \Credit;
								$input['sale_id'] = $sale->sale_id;
								$credit->doSave($credit, $input);

							}
						} else {
							if (strpos($oldMeasure, 'sack') !== false) $input['uom'] = $oldMeasure;
							$errors = [\Lang::get('agrivet.errors.insufficient_stocks', ['stocks' => $stock->total_stocks .' '.$uom])];
						}

					} else {
						if (strpos($oldMeasure,'sack') !== false) $input['uom'] = $oldMeasure;
						$errors = [\Lang::get('agrivet.errors.out_of_stocks')];

					}
				});
				

				if (count($errors) == 0) {
					return \Redirect::route('admin_credits.index')->with('success', \Lang::get('agrivet.created'));
				} else {
					return \Redirect::back()->withErrors($errors)->withInput($input);
				}


				

				return \Redirect::back()->withErrors($credit->errors())->withInput();
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

		$credit = \Credit::find($id);
		
		return \View::make('admin.credit.edit')->with('credit', $credit)
		->with('branches', \Branch::filterBranch()->dropdown()->lists('name', 'id'))
		->with('products', array_add(\Product::all()->lists('name', 'id'), '0', 'Select Product'))
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
		
		$rules = array_except(\Credit::$rules, 'encoded_by');

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {


				$errors = [];

				\DB::transaction(function() use(&$input,$id, &$errors) {
					

					// Get user branch
					$branch_id = array_get($input, 'branch_id');
					$uom = array_get($input, 'uom');
					$product = array_get($input, 'product_id');
					$quantity = array_get($input, 'quantity', 0);

					// Convert sack to kg
					if (strpos($uom,'sack') !== false) {
						$oldMeasure = $input['uom'];
						$input['quantity'] = $quantity * \Config::get('agrivet.equivalent_measure.sacks.per');
						$input['uom'] = $uom = 'kg';
					}

					$stock = \StockOnHand::where('product_id', $product)
									->where('branch_id', $branch_id)
									->where('uom', $uom)
									->first();

					$branch = \ProductPricing::whereRaw("branch_id = {$branch_id} AND product_id = {$product}  AND per_unit = '{$uom}'")->first();

					$input['supplier_price'] = 	$branch->supplier_price;
					$input['selling_price'] = 	$branch->selling_price;
					$input['sale_type'] 	= 	"CREDIT";
					$input['encoded_by'] 	= 	\Confide::user()->id;


					$credit = \Credit::findOrFail($id);

					$sale = \Sale::findOrFail($credit->sale_id);

					if ($sale->quantity > $quantity) {
							$stock->total_stocks = $stock->total_stocks +  ($sale->quantity - array_get($input, 'quantity'));
							$stock->save();

							


					} else if ($sale->quantity < $quantity) {
						
						$total = $stock->total_stocks -  ($quantity - $sale->quantity);


						// Check if stock is insufficient
						if ($total < 0) {
							$errors = [\Lang::get('agrivet.errors.insufficient_stocks', ['stocks' => $stock->total_stocks .' '.$uom])];
							return;
						}

						$stock->total_stocks = $total;

						$stock->save();

					}

					$input['total_amount'] = 	$branch->selling_price * $input['quantity'];
					if (!$sale->doSave($sale, $input)) {			
						$errors = $sale->errors();

					} else {
						$stock->total_stocks = $stock->total_stocks - $quantity;
						$stock->save();

						$input['sale_id'] = $sale->sale_id;
						$credit->doSave($credit, $input);
					}

				});



				if (count($errors) == 0) {
					return \Redirect::route('admin_credits.index')->with('success', \Lang::get('agrivet.updated'));
				} else {
					return \Redirect::back()->withErrors($credit->errors())->withInput();
				}

				return \Redirect::back()->withErrors($credit->errors())->withInput();
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
		$credit = \Credit::withTrashed()->where('credit_id', $id)->first();
		$message = \Lang::get('agrivet.trashed');
		if ($credit->trashed() || \Input::get('remove') == 1) {
            $credit->sale->forceDelete();
            $message = \Lang::get('agrivet.deleted');
        } else {
            $credit->sale->delete();
        }

        return \Redirect::route('admin_credits.index')->with('success', $message);
	}

	/**
	 * Restore deleted resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function restore($id) {
		$credit = \Credit::withTrashed()->where('credit_id', $id)->first();
		if (!$credit->restore()) {
			return \Redirect::back()->withErrors($credit->errors());			
		}

		return \Redirect::back()->with('success', \Lang::get('agrivet.restored'));
	}

	public function review($reviewId = false) {
		$input = \Input::all();


		$rules = \Sale::$rules;

		if (!\Confide::user()->isAdmin()) {
			$input['branch_id'] = \Confide::user()->branch_id;
		}
		$input['status'] = 0;
		$input['encoded_by'] = \Confide::user()->id;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {

				$review = [];

				if (\Session::has('creditsReview')) {
					$review = \Session::get('creditsReview');	
				}
				
				if (!$reviewId) {
					$reviewId = time();
				}

				$review[$reviewId] = array_add($input, 'branch_id', $input['branch_id']);


				\Session::put('creditsReview', $review);
				

				return \Redirect::route('admin_credits.create')->with('success', \Lang::get('agrivet.add_to_review'));

			} catch(\Exception $e) {
				return \Redirect::back()->withErrors((array)$e->getMessage())->withInput($input);
			}
		}
	}

	public function deleteReview($reviewId) {
		\Session::forget("creditsReview.$reviewId");

		return \Redirect::route('admin_credits.create')->with('success', \Lang::get('agrivet.deleted'));
	}

	public function saveReview() {
		try {
			$reviews = \Session::get('creditsReview');
			$errors = [];

			foreach ($reviews as $key => $input) {
				$input['encoded_by'] = \Confide::user()->id;
				$input['is_paid'] = 0;

				$errors = [];

				\DB::transaction(function() use(&$input, &$errors) {
					

					// Get user branch
					$branch_id = array_get($input, 'branch_id');
					$uom = array_get($input, 'uom');
					$product = array_get($input, 'product_id');
					$quantity = array_get($input, 'quantity');

					// Convert sack to kg
					if (strpos($uom,'sack') !== false) {
						$oldMeasure = $input['uom'];
						$input['quantity'] = $quantity * \Config::get('agrivet.equivalent_measure.sacks.per');
						$input['uom'] = $uom = 'kg';
					}


					$stock = \StockOnHand::where('product_id', $product)
									->where('branch_id', $branch_id)
									->where('uom', $uom)
									->first();

		
					if ($stock && $stock->total_stocks > 0) {

						if ($stock->total_stocks >= array_get($input, 'quantity', 0)) {
							$branch = \ProductPricing::whereRaw("branch_id = {$branch_id} AND product_id = {$product}  AND per_unit = '{$uom}'")->first();

							$input['sale_type'] = 	"CREDIT";
							$input['supplier_price'] = 	$branch->supplier_price;
							$input['selling_price'] = 	$branch->selling_price;
							$input['total_amount'] = 	$branch->selling_price * $input['quantity'];


							$sale = new \Sale;

							if (!$sale->doSave($sale, $input)) {			
								$errors = $sale->errors();
							} else {
								$stock->total_stocks = $stock->total_stocks - array_get($input, 'quantity');
								$stock->save();

								$credit = new \Credit;
								$input['sale_id'] = $sale->sale_id;
								$credit->doSave($credit, $input);

							}
						} else {
							if (strpos($oldMeasure, 'sack') !== false) $input['uom'] = $oldMeasure;
							$errors = [\Lang::get('agrivet.errors.insufficient_stocks', ['stocks' => $stock->total_stocks .' '.$uom])];
						}

					} else {
						if (strpos($oldMeasure,'sack') !== false) $input['uom'] = $oldMeasure;
						$errors = [\Lang::get('agrivet.errors.out_of_stocks')];

					}
				});
				


				if (count($errors) == 0) {
					\Session::forget("creditsReview.$key");
				} else {
					$errors[] = $credit->errors();
				}

			}
				

			if (count($errors) == 0) {
				return \Redirect::route('admin_credits.create')->with('success', \Lang::get('agrivet.created'));
			} else {

				return \Redirect::route('admin_credits.create')->withErrors($errors);
			}


		} catch (\Exception $e) {
			return \Redirect::route('admin_credits.create')->withErrors((array)$e->getMessage());
		}
	}


}
