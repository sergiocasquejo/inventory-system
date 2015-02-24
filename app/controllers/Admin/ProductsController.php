<?php namespace Admin;

class ProductsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$input = \Input::all();


		$products = \Product::withTrashed()->search($input)->orderBy('id', 'desc')->paginate(intval(array_get($input, 'records_per_page', 10)));
		
		$totalRows = \Product::withTrashed()->count();

		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		$countries = \Config::get('agrivate.countries');
		return \View::make('admin.product.index')
			->with('products', $products)
			->with('branches', \Branch::all()->lists('name', 'id'))
			->with('appends', $appends)
			->with('totalRows', $totalRows);


		$products = \Product::withTrashed();


		;
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

		$input['encoded_by'] = \Confide::user()->id;

		$rules = \Product::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$product = new \Product;


				if ($product->doSave($product, $input)) {
					return \Redirect::route('admin_products.index')->with('success', \Lang::get('agrivate.created'));
				}

				return \Redirect::back()->withErrors($product->errors())->withInput();
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

		$product = \Product::find($id);
		
		return \View::make('admin.product.edit')->with('product', $product)->with('branches', array_add(\Branch::all()->lists('name', 'id'), '', 'Select Branch'));
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


		if (array_get($input, 'action') == 'add_stock') {
			$product = \Product::find($id);

			$rules = \StockOnHand::$rules;

			$repo = new \StockOnHand;
			$validator = \Validator::make($input, $rules);
			if ($validator->fails()) {
				return \Response::json($validator->errors());
			} else {
				if ($repo->doSave($repo, $input)) {
					return \Response::json(['message' => \Lang::get('agrivate.created')]);
				}

				return \Response::json($repo->errors());
			}

		} else {


			$rules = array_except(\Product::$rules, 'encoded_by');

			$rules['name'] = $rules['name'].','.$id.',id';

			$validator = \Validator::make($input, $rules);

			if ($validator->fails()) {
				return \Redirect::back()->withErrors($validator->errors())->withInput();
			} else {
				try {
					$product = \Product::findOrFail($id);
					
					$input['encoded_by'] = $product->encoded_by;
					if ($product->doSave($product, $input)) {
						return \Redirect::route('admin_products.index')->with('success', \Lang::get('agrivate.updated'));
					}

					return \Redirect::back()->withErrors($product->errors())->withInput();
				} catch(\Exception $e) {
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

        return \Redirect::route('admin_products.index')->with('success', $message);
	}


}
