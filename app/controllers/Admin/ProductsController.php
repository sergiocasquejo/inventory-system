<?php namespace Admin;

class ProductsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$products = \Product::withTrashed();


		return \View::make('admin.product.index')->with('products', $products);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return \View::make('admin.product.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = \Input::all();

		$rules = \Product::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors());
		} else {
			try {
				$product = new \Product;

				if ($product->doSave($product, $input)) {
					return \Redirect::route('admin_branches.index')->with('success', \Lang::get('agrivate.created'));
				}

				return \Redirect::back()->withErrors($product->errors());
			} catch(\Exception $e) {
				return \Redirect::back()->withErrors((array)$e->getMessage());
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

		$product = \Product::find($id);
		
		return \View::make('admin.product.edit')->with('product', $product);
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

		$rules = \Product::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors());
		} else {
			try {
				$product = \Product::findOrFail($id);
				
				if ($product->doSave($product, $input)) {
					return \Redirect::route('admin_branches.index')->with('success', \Lang::get('agrivate.updated'));
				}

				return \Redirect::back()->withErrors($product->errors());
			} catch(\Exception $e) {
				return \Redirect::back()->withErrors((array)$e->getMessage());
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
		$product = \Product::withTrashed()->where('id', $id)->first();
		$message = \Lang::get('agrivate.trashed');
		if ($product->trashed()) {
            $product->forceDelete();
            $message = \Lang::get('agrivate.deleted');
        } else {
            $product->delete();
        }

        return \Redirect::route('admin_branches.index')->with('success', $message);
	}


}
