<?php namespace Admin;

class SalesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$sales = \Sale::withTrashed();


		return \View::make('admin.sale.index')->with('sales', $sales);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return \View::make('admin.sale.create');
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

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors());
		} else {
			try {
				$errors = [];

				\DB::transaction(function() use($errors) {


					$sale = new \Sale;

					if (!$sale->doSave($sale, $input)) {
						$errors = $sale->errors();
					}

				});

				if (count($errors))
					return \Redirect::back()->withErrors($errors);
				else
					return \Redirect::route('admin_sales.index')->with('success', \Lang::get('agrivate.created'));

				
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

		$sale = \Sale::find($id);
		
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

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors());
		} else {
			try {
				$errors = [];

				\DB::transaction(function() use($errors, $id) {


					$sale = \Sale::find($id);

					if (!$sale->doSave($sale, $input)) {
						$errors = $sale->errors();
					}

				});

				if (count($errors))
					return \Redirect::back()->withErrors($errors);
				else
					return \Redirect::route('admin_sales.index')->with('success', \Lang::get('agrivate.updated'));
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
		$sale = \Sale::withTrashed()->where('id', $id)->first();
		$message = \Lang::get('agrivate.trashed');
		if ($sale->trashed()) {
            $sale->forceDelete();
            $message = \Lang::get('agrivate.deleted');
        } else {
            $sale->delete();
        }

        return \Redirect::route('admin_sales.index')->with('success', $message);
	}


}
