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


		$sales = \Sale::withTrashed()
				->filter($input)
				->orderBy('sale_id', 'desc')
				->paginate(intval(array_get($input, 'records_per_page', 10)));
		
		$totalRows = \Sale::withTrashed()->filterBranch()->count();

		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		$countries = \Config::get('agrivate.countries');



		$yearly = \Sale::filterBranch()->whereRaw('YEAR(date_of_sale) = YEAR(CURDATE())')->sum('total_amount');
		$monthly = \Sale::filterBranch()->whereRaw('MONTH(date_of_sale) = MONTH(CURDATE())')->sum('total_amount');
		$weekly = \Sale::filterBranch()->whereRaw('WEEK(date_of_sale) = WEEK(CURDATE())')->sum('total_amount');
		$daily = \Sale::filterBranch()->whereRaw('DAY(date_of_sale) = DAY(CURDATE())')->sum('total_amount');


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
			'years' => array_add(\Sale::filterBranch()->select(\DB::raw('YEAR(date_of_sale) as year'))->lists('year', 'year'), '', 'Year'),
			'statuses' => array_add(\Sale::filterBranch()->select(\DB::raw('status, IF (status = 1, \'Active\', \'Inactive\') as name'))->lists('name', 'status'), '', 'Status'),
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
		->with('branches', \Branch::filterBranch()->dropdown())
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

		$rules = \Sale::$rules;

		$input['encoded_by'] = \Confide::user()->id;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {

				$errors = [];

				\DB::transaction(function() use($input, &$errors) {
					

					// Get user branch
					$branch_id = array_get($input, 'branch_id');
					$uom = array_get($input, 'uom');
					$product = array_get($input, 'product_id');


					$stock = \StockOnHand::where('product_id', $product)
									->where('branch_id', $branch_id)
									->where('uom', $uom)
									->first();

		
					if ($stock->total_stocks > 0) {

						if ($stock->total_stocks >= array_get($input, 'quantity', 0)) {
							$branch = \ProductPricing::whereRaw("branch_id = {$branch_id} AND product_id = {$product}  AND per_unit = '{$uom}'")->first();

							$input['supplier_price'] = 	$branch->supplier_price;
							$input['selling_price'] = 	$branch->selling_price;


							$sale = new \Sale;

							if (!$sale->doSave($sale, $input)) {			
								$errors = $sale->errors();
							} else {
								$stock->total_stocks = $stock->total_stocks - array_get($input, 'quantity');
								$stock->save();
							}
						} else {
							$errors = [\Lang::get('agrivate.errors.insufficient_stocks', ['stocks' => $stock->total_stocks .' '.$uom])];
						}

					} else {
						$errors = [\Lang::get('agrivate.errors.out_of_stocks')];

					}
				});
				

				if (count($errors) == 0) {
					return \Redirect::route('admin_sales.index')->with('success', \Lang::get('agrivate.created'));
				} else {
					return \Redirect::back()->withErrors($errors)->withInput();
				}


				
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
			$sale = \Sale::findOrFail($id);
		} catch(\Exception $e) {
			return \Redirect::back()->with('info', \Lang::get('agrivate.errors.restore'));
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

		$rules = \Sale::$rules;
		$input['encoded_by'] = \Confide::user()->id;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {



				$errors = [];

				\DB::transaction(function() use($input,$id, &$errors) {
					

					// Get user branch
					$branch_id = array_get($input, 'branch_id');
					$uom = array_get($input, 'uom');
					$product = array_get($input, 'product_id');

					$quantity = array_get($input, 'quantity', 0);

					$stock = \StockOnHand::where('product_id', $product)
									->where('branch_id', $branch_id)
									->where('uom', $uom)
									->first();

					$branch = \ProductPricing::whereRaw("branch_id = {$branch_id} AND product_id = {$product}  AND per_unit = '{$uom}'")->first();

					$input['supplier_price'] = 	$branch->supplier_price;
					$input['selling_price'] = 	$branch->selling_price;


					$sale = \Sale::findOrFail($id);

					if ($sale->quantity > $quantity) {
							$stock->total_stocks = $stock->total_stocks +  ($sale->quantity - array_get($input, 'quantity'));
							$stock->save();
					} else if ($sale->quantity < $quantity) {
						
						$total = $stock->total_stocks -  ($quantity - $sale->quantity);


						// Check if stock is insufficient
						if ($total < 0) {
							$errors = [\Lang::get('agrivate.errors.insufficient_stocks', ['stocks' => $stock->total_stocks .' '.$uom])];
							return;
						}

						$stock->total_stocks = $total;

						$stock->save();

					}


					if (!$sale->doSave($sale, $input)) {			
						$errors = $sale->errors();
					} else {
						$stock->total_stocks = $stock->total_stocks - $quantity;
						$stock->save();
					}

				});
				

				if (count($errors) == 0) {
					return \Redirect::route('admin_sales.index')->with('success', \Lang::get('agrivate.created'));
				} else {
					return \Redirect::back()->withErrors($errors)->withInput();
				}

				return \Redirect::back()->withErrors($sale->errors())->withInput();
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
		$sale = \Sale::withTrashed()->where('sale_id', $id)->first();
		$message = \Lang::get('agrivate.trashed');
		if ($sale->trashed()) {
            $sale->forceDelete();
            $message = \Lang::get('agrivate.deleted');
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

		return \Redirect::back()->with('success', \Lang::get('agrivate.restored'));
	}


}
