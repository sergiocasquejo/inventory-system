<?php namespace Admin;

class StockOnHandController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($product_id)
	{
		$input = \Input::all();

		$stock = new \StockOnHand;

		$input['product_id'] = $product_id;
		$branch_id = array_get($input, 'branch_id');

		// Do conversion sacks to kilogram
		$uomInput = array_get($input, 'uom');
		if ($uomInput == 'sacks') {
			
			$equi_config = \Config::get('agrivate.equivalent_measure.sacks');
			$input['uom'] = $equi_config['to'];
			$total_stocks = array_get($input, 'total_stocks', 0) * $equi_config['per'];


			// Get user branch
			$branch_id = array_get($input, 'branch_id');
			$uom = array_get($input, 'uom');
			$product = array_get($input, 'product_id');
			$stock = \StockOnHand::whereRaw("branch_id = {$branch_id} AND product_id = {$product_id}  AND per_unit = 'kg'")->first();

			$input['total_stocks'] = $stock->total_stocks + $total_stocks;

		}



		

		$uom = array_get($input, 'uom');
		
		$rules = \StockOnHand::$rules;

		



		$rules['branch_id'] = 'required|exists:branches,id|unique:stocks_on_hand,branch_id,NULL,stock_on_hand_id,product_id,'.$product_id.',uom,'.$uom;

		// echo $rules['branch_id'];
		// die;
		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Response::json(['errors' => $validator->errors()]);
		} else {
			try {
				
				if ($stock->doSave($stock, $input)) {

					return \Response::json(['success' => \Lang::get('agrivate.created')]);
					//return \Redirect::route('admin_products.edit', $stock->id)->with('success', \Lang::get('agrivate.created'));
				}
				return \Response::json(['errors' => $stock->errors()]);
				//return \Redirect::back()->withErrors($stock->errors())->withInput();
			} catch(\Exception $e) {
				return \Response::json(['errors' => (array)$e->getMessage()]);
				//return \Redirect::back()->withErrors((array)$e->getMessage())->withInput();
			}
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($product_id, $stock_id)
	{	
		$stock = \StockOnHand::findOrFail($stock_id);
		
		return \Response::json($stock->toArray());
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($product_id, $stock_id)
	{

		$input = \Input::all();

		$rules = \StockOnHand::$rules; 

		$uom = array_get($input, 'uom');
		
		$rules['branch_id'] = 'required|exists:branches,id|unique:stocks_on_hand,branch_id,'.$stock_id.',stock_on_hand_id,product_id,'.$product_id.',uom,'.$uom;

		$input['product_id'] = $product_id;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Response::json(['errors' => $validator->errors()]);
		} else {
			try {
				$stock = \StockOnHand::findOrFail($stock_id);
				

				if ($stock->doSave($stock, $input)) {
					return \Response::json(['success' => \Lang::get('agrivate.updated')]);
				}

				return \Response::json(['errors' => $stock->errors()]);
			} catch(\Exception $e) {
				return \Response::json(['errors' => (array)$e->getMessage()]);
			}
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($product_id, $stock_id)
	{
		$stock = \StockOnHand::find($stock_id)->delete();
		if ($stock) {
			return \Redirect::back()->with('success', \Lang::get('agrivate.deleted'));
			// return \Response::json(['success' => \Lang::get('agrivate.deleted')]);
		} 
		return \Redirect::back()->withErrors($stock->errors());
		// return \Response::json($stock->errors());
	}


}
