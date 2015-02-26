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


		$sales = \Sale::withTrashed()->orderBy('sale_id', 'desc')
				->paginate(intval(array_get($input, 'records_per_page', 10)));
		
		$totalRows = \Sale::withTrashed()->count();

		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		$countries = \Config::get('agrivate.countries');
		return \View::make('admin.sale.index')
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
		->with('branches', \Branch::all()->lists('name', 'id'))
		->with('products', \Product::all()->lists('name', 'id'));
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
				$sale = new \Sale;

				if ($sale->doSave($sale, $input)) {
					return \Redirect::route('admin_sales.index')->with('success', \Lang::get('agrivate.created'));
				}

				return \Redirect::back()->withErrors($sale->errors())->withInput();
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

		return \View::make('admin.sale.edit')->with('sale', $sale);
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

		$rules['name'] = $rules['name'].','.$id.',id';

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$sale = \Sale::findOrFail($id);
				
				if ($sale->doSave($sale, $input)) {
					return \Redirect::route('admin_sale.index')->with('success', \Lang::get('agrivate.updated'));
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
