<?php namespace Admin;

class StockOnHandController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$input = \Input::all();

        $stockSQL = \StockOnHand::select('stocks_on_hand.stock_on_hand_id', 'stocks_on_hand.uom',
                'stocks_on_hand.total_stocks',
                'products.name as product_name', 'products.sack_to_kg',
                'branches.name as branch_name', 'branches.address')
            ->join('products', 'stocks_on_hand.product_id', '=', 'products.id')
            ->join('branches', 'branches.id', '=', 'stocks_on_hand.branch_id');

        $totalRows = $stockSQL->count();

        $offset = intval(array_get($input, 'records_per_page', 10));
        if ( $offset == -1 ) {
            $offset = $totalRows;

        }



        $stocks = $stockSQL->filter($input)
			->orderBy('branch_id', 'asc')
            ->paginate($offset);





        $appends = ['records_per_page' => \Input::get('records_per_page', 10)];


		$newStocks = [];
		foreach ($stocks as $stock) {
			$prod_name = $stock->product_name;
			$branch_name = $stock->branch_name.'('.$stock->address.')';

			


			$total_stocks = $stockStr = $stock->total_stocks.' '. $stock->uom;
			$sackStr = 'N/A';

			if ($stock->uom == 'kg') {
				//1 Sack equivalent
				$sackEqui = $stock->sack_to_kg != 0 ? $stock->sack_to_kg : \Config::get('agrivet.equivalent_measure.sacks.per');

				$sack = 0;
				$quantity = (float)$stock->total_stocks / (float)$sackEqui;

				$total_stocks = '';

				if ($stock->total_stocks  >= $sackEqui) {
					$sack = floor( $quantity );
					$total_stocks = $sackStr = $sack .' sack(s)';
				}

				
				$kg = ($quantity - $sack) * $sackEqui;


				if ($kg != 0) {
					$stockStr = $kg .' '. $stock->uom;
					$total_stocks .= ($sack != 0) ? ' and '. $stockStr :$stockStr;
				} else {
					$stockStr = $kg .' '. $stock->uom;
				}

				 
			}

			$newStocks[] = [
				'stock_id' => $stock->stock_on_hand_id,
				'branch' => $branch_name,
				'product_name' => $prod_name,
				'other_stock' => $stockStr,
				'sack_stock' => $sackStr,
				'total_stocks' => $total_stocks,
			];
		}


		return \View::make('admin.stock.index')
			->with('branches', array_add(\Branch::dropdown()->lists('name', 'id'), '', 'Select Branch'))
            ->with('brands', array_add(\Brand::all()->lists('name', 'brand_id'), '', 'Select Brand'))
			->with('stocks', $newStocks)
            ->with('pagination', $stocks)
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

		$measures = \UnitOfMeasure::all()->lists('label', 'name');


		//$products = array_add(\Product::all()->lists('name', 'id'), '', 'Select Product');

        $suppliers = \Supplier::lists('supplier_name', 'supplier_id');
        $brands = \Brand::lists('name', 'brand_id');


		return \View::make('admin.stock.create')
		//->with('products', $products)
		->with('branches', array_add(\Branch::dropdown()->lists('name', 'id'), '', 'Select Branch'))
		->with('dd_measures', array_add($measures, '', 'Select Measure'))
		->with('measures', \UnitOfMeasure::all()->lists('label', 'name'))
            ->with('suppliers', array_add($suppliers, '', 'Select Supplier'))
            ->with('brands', array_add($brands, '', 'Select Brands'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = \Input::all();



		$rules = \StockOnHand::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			if (\Request::ajax()) {
				return \Response::json(['errors' => $validator->errors()]);
			} else {
				return \Redirect::back()->withErrors($validator->errors())->withInput();
			}
		} else {
			try {
				

				$stock = new \StockOnHand;
		

				// Get user branch
				$branch_id = array_get($input, 'branch_id');
				$uom = array_get($input, 'uom');
				$product_id = array_get($input, 'product_id');
                $input['quantity'] = array_get($input, 'total_stocks');

				$stockObj = \StockOnHand::whereRaw("branch_id = {$branch_id} AND product_id = {$product_id}  AND uom = '{$uom}'")->first();

				if ($stockObj) {
					$stock = $stockObj;
					$input['total_stocks'] = $stock->total_stocks + array_get($input, 'total_stocks', 0);
				}


				// Do conversion sacks to kilogram
				$uomInput = array_get($input, 'uom');
				if (strpos($uomInput, 'sack') !== false) {

					$equi_config = \Config::get('agrivet.equivalent_measure.sacks');
                    $sackEqui = $stock->sack_to_kg != 0 ? $stock->sack_to_kg : $equi_config['per'];

					$input['uom'] = $uom = $equi_config['to'];
					
					$total_stocks = array_get($input, 'total_stocks', 0) * $sackEqui;

					$stockObj = \StockOnHand::whereRaw("branch_id = {$branch_id} AND product_id = {$product_id}  AND uom = '{$uom}'")->first();

					if ($stockObj) {
						$stock = $stockObj;
						$total_stocks = $stock->total_stocks + $total_stocks;
					}

					$input['total_stocks'] = $total_stocks;

				}

                $errors = [];

                \DB::transaction(function() use (&$stock, &$input, &$errors) {
                    if ($stock->doSave($stock, $input)) {
                        if (array_get($input, 'is_payable') == 1 ) {

                            $payable = new \Payable;
                            $input['name'] = array_get($input, 'product_id');
                            $input['brand'] = array_get($input, 'brand');
                            $input['supplier'] = array_get($input, 'supplier');
                            $input['quantity'] = array_get($input, 'quantity');
                            $input['encoded_by'] = \Confide::user()->id;
                            $input['stock_on_hand_id'] = $stock->stock_on_hand_id;
                            $input['comments'] = array_get($input, 'comments');

                            if ($payable->doSave($payable, $input)) {

                                $supplier = \Supplier::findOrFail($input['supplier']);
                                $supplier->total_payables = $supplier->total_payables + array_get($input, 'total_amount', 0);
                                $supplier->save();

                            } else {
                                $errors[] = $payable->errors();
                            }
                        }
                    } else {
                        $errors[] = $stock->errors();
                    }
                });


				if (count($errors) == 0) {
                    return \Redirect::route('admin_stocks.index')->with('success', \Lang::get('agrivet.created'));
				} else {
					return \Redirect::back()->withErrors($stock->errors())->withInput();	
				}

			} catch(\Exception $e) {

				if (\Request::ajax()) {
					return \Response::json(['errors' => (array)$e->getMessage()]);
				} else {
					return \Redirect::back()->withErrors((array)$e->getMessage())->withInput();
				}

			}
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($stock_id = false)
	{	

		//$input = \Input::all();

        $suppliers = \Supplier::lists('supplier_name', 'supplier_id');
		//$product_id = array_get($input, 'product_id');

		if (\Request::ajax()) {
			$stock = \StockOnHand::findOrFail($stock_id);
			
			return \Response::json($stock->toArray());
		} else {


			$measures = \UnitOfMeasure::all()->lists('label', 'name');


			//$products = array_add(\Product::all()->lists('name', 'id'), '', 'Select Product');
			$stock = \StockOnHand::findOrFail($stock_id);

			return \View::make('admin.stock.edit')
			//->with('products', $products)
            ->with('suppliers', array_add($suppliers, '', 'Select Supplier'))
			->with('stock', $stock)
			->with('branches', array_add(\Branch::dropdown()->lists('name', 'id'), '', 'Select Branch'))
			->with('dd_measures', array_add($measures, '', 'Select Measure'))
			->with('measures', \UnitOfMeasure::all()->lists('label', 'name'));
		}
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($stock_id = false)
	{

		$input = \Input::only('total_stocks');




		$rules['total_stocks'] = 'required|numeric';



		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {

			if (\Request::ajax()) {
				return \Response::json(['errors' => $validator->errors()]);
			} else {
				return \Redirect::back()->withErrors($validator->errors())->withInput();
			}
		} else {
			try {


				$stock = \StockOnHand::findOrFail($stock_id);
				
                $stock->total_stocks = array_get($input, 'total_stocks', 0);



				if ($stock->save()) {
					if (\Request::ajax()) {
						return \Response::json(['success' => \Lang::get('agrivet.updated')]);
					} else {
						return \Redirect::route('admin_stocks.index')->with('success', \Lang::get('agrivet.created'));
					}
				}

				if (\Request::ajax()) {
					return \Response::json(['errors' => $stock->errors()]);
				} else {
					return \Redirect::back()->withErrors($stock->errors())->withInput();
				}

				
			} catch(\Exception $e) {
				if (\Request::ajax()) {
					return \Response::json(['errors' => (array)$e->getMessage()]);
				} else {
					return \Redirect::back()->withErrors((array)$e->getMessage())->withInput();
				}
			}
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($stock_id = false)
	{

		$stock = \StockOnHand::find($stock_id)->delete();
		if ($stock) {
			return \Redirect::back()->with('success', \Lang::get('agrivet.deleted'));
		} 
		return \Redirect::back()->withErrors($stock->errors());
	}



	public function uom() {
		$product_id = \Input::get('product_id');
		$product = \Product::find($product_id);

		$uoms = implode("','", json_decode($product->uom));

		$measures = \UnitOfMeasure::whereRaw("name IN ('$uoms')")->lists('label', 'name');

		return \Response::json($measures);

	}
}
