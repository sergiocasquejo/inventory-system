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
	public function store()
	{
		$input = \Input::all();

		$input['encoded_by'] = \Confide::user()->id;

		$rules = \StockOnHand::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$stock = new \StockOnHand;
				if ($stock->doSave($stock, $input)) {
					return \Redirect::route('admin_products.edit', $stock->id)->with('success', \Lang::get('agrivate.created'));
				}

				return \Redirect::back()->withErrors($stock->errors())->withInput();
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

		$rules['branch_id'] = 'required|exists:branches,id|unique:stocks_on_hand,branch_id,product_id,'.$product_id;

		$validator = \StockOnHand::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$stock = \StockOnHand::findOrFail($id);
				

				if ($stock->doSave($stock, $input)) {
					return \Redirect::route('admin_products.index')->with('success', \Lang::get('agrivate.updated'));
				}

				return \Redirect::back()->withErrors($stock->errors())->withInput();
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
