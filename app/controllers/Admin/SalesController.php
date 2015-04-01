<?php namespace Admin;

class SalesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$input = \Input::all();

        $totalRows = \Sale::withTrashed()
            ->filter($input)
            ->owned()
            ->orderBy('sale_id', 'desc')->count();


        $offset = intval(array_get($input, 'records_per_page', 10));
        if ( $offset == -1 ) {
            $offset = $totalRows;

        }


		$sales = \Sale::withTrashed()
				->filter($input)
                ->sale()
				->owned()
				->orderBy('sale_id', 'desc')
				->paginate($offset);
		


		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		$countries = \Config::get('agrivet.countries');


		$yearly = \Sale::owned()->sale()->owned()->whereRaw('sale_type="SALE" AND YEAR(date_of_sale) = YEAR(CURDATE())')->sum('total_amount');
		$monthly = \Sale::owned()->sale()->owned()->whereRaw('sale_type="SALE" AND MONTH(date_of_sale) = MONTH(CURDATE())')->sum('total_amount');
		$weekly = \Sale::owned()->sale()->owned()->whereRaw('sale_type="SALE" AND WEEK(date_of_sale) = WEEK(CURDATE())')->sum('total_amount');
		$daily = \Sale::owned()->sale()->owned()->whereRaw('sale_type="SALE" AND DAY(date_of_sale) = DAY(CURDATE())')->sum('total_amount');


		$branches = \DB::table('sales')->join('branches', 'sales.branch_id', '=', 'branches.id')
					->select(\DB::raw('CONCAT(name," (",address,")") AS name, id'));

		$products = \DB::table('sales')
					->join('products', 'sales.product_id', '=', 'products.id')
					->select(\DB::raw('CONCAT(SUBSTRING(name, 1, 20),"...") AS name, id'));
					
		// Filter branch if user is not owner
		if (!\Confide::user()->isAdmin()) {
			$branches = $branches->where('branches.id', \Confide::user()->branch_id);
			$products = $products->where('sales.branch_id', \Confide::user()->branch_id);
		}

		$all = [
			'daily' => $daily,
			'weekly' => $weekly,
			'monthly' => $monthly,
			'yearly' => $yearly,
			'branches' => array_add($branches->lists('name', 'id'), '', 'Branch'),
			'products' => array_add($products->lists('name', 'id'), '', 'Product'),
			'totals' => array_add(\Sale::filterBranch()->lists('total_amount', 'total_amount'), '', 'Amount'),
			'days' => array_add(\Sale::filterBranch()->select(\DB::raw('DAY(date_of_sale) as day'))->lists('day', 'day'), '', 'Day'),
			'months' => array_add(\Sale::filterBranch()->select(\DB::raw('DATE_FORMAT(date_of_sale, "%b") as month, MONTH(date_of_sale) as month_no'))->lists('month', 'month_no'), '', 'Month'),
			'years' => array_add(\Sale::filterBranch()->select(\DB::raw('YEAR(date_of_sale) as year'))->lists('year', 'year'), '', 'Year')
		];

		

		return \View::make('admin.sale.index', $all)
			->with('sales', $sales)
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
		return \View::make('admin.sale.create')
		->with('reviews', \Session::get('salesReview'))
		->with('branches', \Branch::filterBranch()->dropdown()->lists('name', 'id'))
		->with('products', \Product::active()->lists('name', 'id'))
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


							$sale = new \Sale;

							if (!$sale->doSave($sale, $input)) {			
								$errors = $sale->errors();
							} else {
								$stock->total_stocks = $stock->total_stocks - array_get($input, 'quantity');
								$stock->save();
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
					return \Redirect::route('admin_sales.index')->with('success', \Lang::get('agrivet.created'));
				} else {
					return \Redirect::back()->withErrors($errors)->withInput($input);
				}


				
			} catch(\Exception $e) {
				return \Redirect::back()->withErrors((array)$e->getMessage())->withInput($input);
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
			$sale = \Sale::findOrFail($id);
		} catch(\Exception $e) {
			return \Redirect::back()->with('info', \Lang::get('agrivet.errors.restore'));
		}

		return \View::make('admin.sale.edit')
		->with('sale', $sale)
		->with('branches', \Branch::filterBranch()->active()->lists('name', 'id'))
		->with('products', \Product::active()->lists('name', 'id'))
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
		
		$rules = \Sale::$rules;
        $rules['branch_id'] = "";
        $rules['product_id'] = "";
        $rules['quantity'] = "";
        $rules['total_amount'] = "";
        $rules['uom'] = "";
        $rules['encoded_by'] = "";
        $rules['encoded_by'] = "";

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {



				$errors = [];

				\DB::transaction(function() use(&$input,$id, &$errors) {
                    $sale = \Sale::findOrFail($id);
                    if (!$sale->doSave($sale, $input)) {
                        $errors = $sale->errors();
                    }


                    /*
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


					$sale = \Sale::findOrFail($id);
                    $input['encoded_by'] = $sale->encoded_by;

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
					}*/

				});
				

				if (count($errors) == 0) {
					return \Redirect::route('admin_sales.index')->with('success', \Lang::get('agrivet.created'));
				} else {
					return \Redirect::back()->withErrors($errors)->withInput($input);
				}

				return \Redirect::back()->withErrors($sale->errors())->withInput();
			} catch(\Exception $e) {
				return \Redirect::back()->withErrors((array)$e->getMessage())->withInput($input);
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
		$sale = \Sale::withTrashed()->where('sale_id', $id)->first();
		$message = \Lang::get('agrivet.trashed');
		if ($sale->trashed() || \Input::get('remove') == 1 ) {
            $sale->forceDelete();
            $message = \Lang::get('agrivet.deleted');
        } else {
            $sale->delete();
        }

        // Session::set('success', 'Successfully deleted');
        return \Redirect::route('admin_sales.index')->with('success', $message);
        
	}


	/**
	 * Restore deleted resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function restore($id) {
		$sale = \Sale::withTrashed()->where('sale_id', $id)->first();
		if (!$sale->restore()) {
			return \Redirect::back()->withErrors($sale->errors());			
		}

		return \Redirect::back()->with('success', \Lang::get('agrivet.restored'));
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
						'date_of_sale'), 'branch_id', $input['branch_id']);


				\Session::put('salesReview', $review);
				

				return \Redirect::route('admin_sales.create')->with('success', \Lang::get('agrivet.add_to_review'));

			} catch(\Exception $e) {
				return \Redirect::back()->withErrors((array)$e->getMessage())->withInput($input);
			}
		}
	}

	public function deleteReview($reviewId) {
		\Session::forget("salesReview.$reviewId");

		return \Redirect::route('admin_sales.create')->with('success', \Lang::get('agrivet.deleted'));
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


							$sale = new \Sale;

							if (!$sale->doSave($sale, $input)) {			
								$errors[] = $sale->errors();
							} else {
								$stock->total_stocks = $stock->total_stocks - array_get($input, 'quantity');
								$stock->save();
							}
						} else {
							if (strpos($oldMeasure, 'sack') !== false) $input['uom'] = $oldMeasure;
							$errors[] = [$p->name.' '.\Lang::get('agrivet.errors.insufficient_stocks', ['stocks' => $stock->total_stocks .' '.$uom])];
						}

					} else {
						if (strpos($oldMeasure,'sack') !== false) $input['uom'] = $oldMeasure;
						$errors[] = [$p->name.' '.\Lang::get('agrivet.errors.out_of_stocks')];

					}
				});
				
				if (count($errors) == 0) {
					\Session::forget("salesReview.$key");
				}

			}
				

			if (count($errors) == 0) {
				return \Redirect::route('admin_sales.create')->with('success', \Lang::get('agrivet.created'));
			} else {

				return \Redirect::back()->withErrors($errors);
			}


		} catch (\Exception $e) {
			return \Redirect::back()->withErrors((array)$e->getMessage());
		}
	}

}
