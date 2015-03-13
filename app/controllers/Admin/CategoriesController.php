<?php namespace Admin;

class CategoriesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$input = \Input::all();


		$categories = \Category::search($input)->orderBy('category_id', 'desc')->paginate(intval(array_get($input, 'records_per_page', 10)));
		
		$totalRows = \Category::All()->count();

		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		$countries = \Config::get('agrivate.countries');
		return \View::make('admin.category.index')
			->with('categories', $categories)
			->with('appends', $appends)
			->with('totalRows', $totalRows);


		$categories = \Category::All();


		;
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return \View::make('admin.category.create')
		->with('brands', \Brand::all());
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

		$rules = \Category::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$category = new \Category;


				if ($category->doSave($category, $input)) {
					if (count(array_get($input, 'brand')) != 0) {
						$category->brands()->sync(array_get($input, 'brand'));
					}

					return \Redirect::route('admin_categories.edit', $category->category_id)->with('success', \Lang::get('agrivate.created'));
				}

				return \Redirect::back()->withErrors($category->errors())->withInput();
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

		$category = \Category::find($id);
		
		return \View::make('admin.category.edit')
			->with('category', $category)
			->with('category_brands', $category->brands->lists('brand_id'))
			->with('brands', \Brand::all());
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

		$rules['name'] = 'required|unique:categories,name,'.$id.',category_id';

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$category = \Category::findOrFail($id);
				
			
				if ($category->doSave($category, $input)) {
					$category->brands()->sync(array_get($input, 'brand'));
					return \Redirect::route('admin_categories.index')->with('success', \Lang::get('agrivate.updated'));
				}

				return \Redirect::back()->withErrors($category->errors())->withInput();
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
			$category = \Category::findOrFail($id);
			$message = \Lang::get('agrivate.trashed');
			if (!$category->delete() || \Input::get('remove') == 1) {
				return \Redirect::back()->withErrors($category->errors());			
	        }

	        return \Redirect::route('admin_categories.index')->with('success', $message);
    	} catch (\FatalErrorException $e) {
    		return \Redirect::back()->withErrors((array)$e->getMessage());
    	} catch (\Exception $e) {
    		return \Redirect::back()->withErrors((array)$e->getMessage());
    	}
	}



}
