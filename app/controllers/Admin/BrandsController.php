<?php namespace Admin;

class BrandsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$input = \Input::all();


        $totalRows = \Brand::All()->count();

        $offset = intval(array_get($input, 'records_per_page', 10));
        if ( $offset == -1 ) {
            $offset = $totalRows;

        }


		$brands = \Brand::search($input)->orderBy('brand_id', 'desc')->paginate($offset);
		


		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		$countries = \Config::get('agrivet.countries');
		return \View::make('admin.brand.index')
			->with('brands', $brands)
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
		return \View::make('admin.brand.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = \Input::all();


		$rules = \Brand::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$brand = new \Brand;


				if ($brand->doSave($brand, $input)) {
					return \Redirect::route('admin_brands.edit', $brand->brand_id)->with('success', \Lang::get('agrivet.created'));
				}

				return \Redirect::back()->withErrors($brand->errors())->withInput();
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

		$brand = \Brand::find($id);
		
		return \View::make('admin.brand.edit')
			->with('brand', $brand)
			->with('brands', array_add(\Brand::all()->lists('name', 'id'), '', 'Select Brand'));
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
		$rules = \Brand::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$brand = \Brand::findOrFail($id);
				
				if ($brand->doSave($brand, $input)) {
					return \Redirect::route('admin_brands.index')->with('success', \Lang::get('agrivet.updated'));
				}

				return \Redirect::back()->withErrors($brand->errors())->withInput();
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
		try {
			$brand = \Brand::findOrFail($id);
			$message = \Lang::get('agrivet.trashed');
			if (!$brand->delete() || \Input::get('remove') == 1) {
				return \Redirect::back()->withErrors($brand->errors());			
	        }

	        return \Redirect::route('admin_brands.index')->with('success', $message);

        } catch (\FatalErrorException $e) {
    		return \Redirect::back()->withErrors((array)$e->getMessage());
    	} catch (\Exception $e) {
    		return \Redirect::back()->withErrors((array)$e->getMessage());
    	}
	}


	public function getCategories($id) {

		if ($id == 0) return;

		try {
			$brand = \Brand::findOrFail($id);

			if ($brand) {
				return \Response::json($brand->categories->lists('name', 'category_id'));
			}
		} catch (\Exception $e) {
			return \Response::json(['error' => $e->getMessage()]);
		}
		return [];
	}


}
