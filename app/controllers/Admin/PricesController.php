<?php namespace Admin;

class PricesController extends \BaseController {

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

		$input['product_id'] = $product_id;

		$rules = \ProductPricing::$rules;


		$rules['branch_id'] = 'required|exists:branches,id|unique:product_pricing,price_id,NULL,price_id,product_id,'.$product_id;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Response::json(['errors' => $validator->errors()]);
		} else {
			try {
				$price = new \ProductPricing;
				if ($price->doSave($price, $input)) {
				return \Response::json(['success' => \Lang::get('agrivate.created')]);
					//return \Redirect::route('admin_products.edit', $stock->id)->with('success', \Lang::get('agrivate.created'));
				}
				return \Response::json(['errors' => $price->errors()]);
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
	public function edit($product_id, $price_id)
	{	
		$stock = \ProductPricing::findOrFail($price_id);
		
		return \Response::json($stock->toArray());
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($product_id, $price_id)
	{
		$input = \Input::all();

		$input['product_id'] = $product_id;

		$rules = \ProductPricing::$rules;

		$rules['branch_id'] = 'required|exists:branches,id|unique:product_pricing,price_id,'.$price_id.',price_id,product_id,'.$product_id;


		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Response::json(['errors' => $validator->errors()]);
		} else {
			try {
				$price = \ProductPricing::findOrFail($price_id);
				
				if ($price->doSave($price, $input)) {
					return \Response::json(['success' => \Lang::get('agrivate.created')]);
					//return \Redirect::route('admin_products.edit', $stock->id)->with('success', \Lang::get('agrivate.created'));
				}
				return \Response::json(['errors' => $price->errors()]);
				//return \Redirect::back()->withErrors($stock->errors())->withInput();
			} catch(\Exception $e) {
				return \Response::json(['errors' => (array)$e->getMessage()]);
				//return \Redirect::back()->withErrors((array)$e->getMessage())->withInput();
			}
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($product_id, $price_id)
	{
		try {
			$stock = \ProductPricing::findOrFail($price_id)->delete();
			if ($stock) {
				return \Redirect::back()->with('success', \Lang::get('agrivate.deleted'));
				// return \Response::json(['success' => \Lang::get('agrivate.deleted')]);
			} 
			return \Redirect::back()->withErrors($stock->errors());
			// return \Response::json($stock->errors());
		} catch(\Exception $e) {
			return \Redirect::back()->withErrors((array)$e->getMessage());
		}
	}


}
