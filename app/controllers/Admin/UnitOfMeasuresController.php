<?php namespace Admin;

class UnitOfMeasuresController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$input = \Input::all();


		$uoms = \UnitOfMeasure::search($input)->orderBy('uom_id', 'desc')->paginate(intval(array_get($input, 'records_per_page', 10)));
		
		$totalRows = \UnitOfMeasure::All()->count();

		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		$countries = \Config::get('agrivate.countries');
		return \View::make('admin.uom.index')
			->with('uoms', $uoms)
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
		return \View::make('admin.uom.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = \Input::all();


		$rules = \UnitOfMeasure::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$uom = new \UnitOfMeasure;


				if ($uom->doSave($uom, $input)) {
					return \Redirect::route('admin_uoms.edit', $uom->uom_id)->with('success', \Lang::get('agrivate.created'));
				}

				return \Redirect::back()->withErrors($uom->errors())->withInput();
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

		$uom = \UnitOfMeasure::find($id);
		
		return \View::make('admin.uom.edit')
			->with('uom', $uom);
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
		$rules = \UnitOfMeasure::$rules;
		$rules['name'] = 'required|unique:unit_of_measures,name,'.$id.',uom_id';
		$rules['label'] = 'required|unique:unit_of_measures,label,'.$id.',uom_id';

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$uom = \UnitOfMeasure::findOrFail($id);
				
				if ($uom->doSave($uom, $input)) {
					return \Redirect::route('admin_uoms.index')->with('success', \Lang::get('agrivate.updated'));
				}

				return \Redirect::back()->withErrors($uom->errors())->withInput();
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
			$uom = \UnitOfMeasure::findOrFail($id);
			$message = \Lang::get('agrivate.trashed');
			if (!$uom->delete()) {
				return \Redirect::back()->withErrors($uom->errors());			
	        }

	        return \Redirect::route('admin_uoms.index')->with('success', $message);

        } catch (\FatalErrorException $e) {
    		return \Redirect::back()->withErrors((array)$e->getMessage());
    	} catch (\Exception $e) {
    		return \Redirect::back()->withErrors((array)$e->getMessage());
    	}
	}



}
