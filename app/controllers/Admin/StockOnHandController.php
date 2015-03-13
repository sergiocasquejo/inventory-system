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

		$stocks = \StockOnHand::filter($input)
			->select('stocks_on_hand.stock_on_hand_id', 'stocks_on_hand.uom', 
				'stocks_on_hand.total_stocks',
				'products.name as product_name', 
				'branches.name as branch_name', 'branches.address')
			->join('products', 'stocks_on_hand.product_id', '=', 'products.id')
			->join('branches', 'branches.id', '=', 'stocks_on_hand.branch_id')
			->orderBy('branch_id', 'asc')->get();

		$newStocks = [];
		foreach ($stocks as $stock) {
			$prod_name = $stock->product_name;
			$branch_name = $stock->branch_name.'('.$stock->address.')';

			


			$total_stocks = $stockStr = $stock->total_stocks.' '. $stock->uom;
			$sackStr = 'N/A';

			if ($stock->uom == 'kg') {
				//1 Sack equivalent
				$sackEqui = \Config::get('agrivate.equivalent_measure.sacks.per');

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
			->with('stocks', $newStocks);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		$measures = \UnitOfMeasure::all()->lists('label', 'name');


		$products = array_add(\Product::all()->lists('name', 'id'), '', 'Select Product');
		return \View::make('admin.stock.create')
		->with('products', $products)
		->with('branches', array_add(\Branch::dropdown()->lists('name', 'id'), '', 'Select Branch'))
		->with('dd_measures', array_add($measures, '', 'Select Measure'))
		->with('measures', \UnitOfMeasure::all()->lists('label', 'name'));
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


				$stockObj = \StockOnHand::whereRaw("branch_id = {$branch_id} AND product_id = {$product_id}  AND uom = '{$uom}'")->first();

				if ($stockObj) {
					$stock = $stockObj;
					$input['total_stocks'] = $stock->total_stocks + array_get($input, 'total_stocks', 0);
				}


				// Do conversion sacks to kilogram
				$uomInput = array_get($input, 'uom');
				if (strpos($uomInput, 'sack') !== false) {

					$equi_config = \Config::get('agrivate.equivalent_measure.sacks');
					$input['uom'] = $uom = $equi_config['to'];
					
					$total_stocks = array_get($input, 'total_stocks', 0) * $equi_config['per'];

					$stockObj = \StockOnHand::whereRaw("branch_id = {$branch_id} AND product_id = {$product_id}  AND uom = '{$uom}'")->first();

					if ($stockObj) {
						$stock = $stockObj;
						$total_stocks = $stock->total_stocks + $total_stocks;
					}

					$input['total_stocks'] = $total_stocks;

				}

				

				$uom = array_get($input, 'uom');


				if ($stock->doSave($stock, $input)) {
					if (\Request::ajax()) {
						return \Response::json(['success' => \Lang::get('agrivate.created')]);
					} else {
						return \Redirect::route('admin_stocks.index')->with('success', \Lang::get('agrivate.created'));
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
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($stock_id = false)
	{	

		$input = \Input::all();
		$product_id = array_get($input, 'product_id');

		if (\Request::ajax()) {
			$stock = \StockOnHand::findOrFail($stock_id);
			
			return \Response::json($stock->toArray());
		} else {


			$measures = \UnitOfMeasure::all()->lists('label', 'name');


			$products = array_add(\Product::all()->lists('name', 'id'), '', 'Select Product');
			$stock = \StockOnHand::findOrFail($stock_id);

			return \View::make('admin.stock.edit')
			->with('products', $products)
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

		$input = \Input::all();




		$rules = \StockOnHand::$rules; 

		$uom = array_get($input, 'uom');

		$product_id = array_get($input, 'product_id');

		
		$rules['branch_id'] = 'required|exists:branches,id|unique:stocks_on_hand,branch_id,'.$stock_id.',stock_on_hand_id,product_id,'.$product_id.',uom,'.$uom;


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
				
				// Do conversion sacks to kilogram
				$uomInput = array_get($input, 'uom');
				if (strpos($uomInput, 'sack')  !== false) {


					$equi_config = \Config::get('agrivate.equivalent_measure.sacks');
					$input['uom'] = $uom = $equi_config['to'];
					
					$input['total_stocks'] = array_get($input, 'total_stocks', 0) * $equi_config['per'];

				}



				if ($stock->doSave($stock, $input)) {
					if (\Request::ajax()) {
						return \Response::json(['success' => \Lang::get('agrivate.updated')]);
					} else {
						return \Redirect::route('admin_stocks.index')->with('success', \Lang::get('agrivate.created'));
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
			return \Redirect::back()->with('success', \Lang::get('agrivate.deleted'));
			// return \Response::json(['success' => \Lang::get('agrivate.deleted')]);
		} 
		return \Redirect::back()->withErrors($stock->errors());
		// return \Response::json($stock->errors());
	}



	public function uom() {
		$product_id = \Input::get('product_id');
		$product = \Product::find($product_id);

		$uoms = implode("','", json_decode($product->uom));

		$measures = \UnitOfMeasure::whereRaw("name IN ('$uoms')")->lists('label', 'name');

		return \Response::json($measures);

	}
}
