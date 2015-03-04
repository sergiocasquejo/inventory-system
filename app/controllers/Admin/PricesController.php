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

		$per_unit = array_get($input, 'per_unit');

		$rules = \ProductPricing::$rules;
	
		$rules['branch_id'] = 'required|exists:branches,id|unique:product_pricing,branch_id,NULL,price_id,product_id,'.$product_id.',per_unit,'.$per_unit;
		

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Response::json(['errors' => $validator->errors()]);
		} else {
			try {
				$errors = [];

				\DB::transaction(function() use($input, $errors) {
					$price = new \ProductPricing;
					
					if ($price->doSave($price, $input)) {
						$priceHistory = new \PriceHistory;
						if (!$priceHistory->doSave($priceHistory, $input)) {
							$errors = $priceHistory->errors();
						}
					} else {
						$errors = $price->errors();
					}
				});
				if (count($errors) == 0) {
					return \Response::json(['success' => \Lang::get('agrivate.created')]);
				} else {
					return \Response::json(['errors' => $price->errors()]);
				}
			} catch(\Exception $e) {
				return \Response::json(['errors' => (array)$e->getMessage()]);
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

		$per_unit = array_get($input, 'per_unit');
		
		$rules = \ProductPricing::$rules;

		$rules['branch_id'] = 'required|exists:branches,id|unique:product_pricing,branch_id,'.$price_id.',price_id,product_id,'.$product_id.',per_unit,'.$per_unit;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Response::json(['errors' => $validator->errors()]);
		} else {
			try {
				$errors = [];

				\DB::transaction(function() use($price_id, $input, $errors) {
					$price = \ProductPricing::findOrFail($price_id);
					
					if ($price->doSave($price, $input)) {
						$priceHistory = new \PriceHistory;
						if (!$priceHistory->doSave($priceHistory, $input)) {
							$errors = $priceHistory->errors();
						}
					} else {
						$errors = $price->errors();
					}
				});
				if (count($errors) == 0) {
					return \Response::json(['success' => \Lang::get('agrivate.updated')]);
				} else {
					return \Response::json(['errors' => $price->errors()]);
				}

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
