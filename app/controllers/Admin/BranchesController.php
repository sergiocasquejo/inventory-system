<?php namespace Admin;

class BranchesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$input = \Input::all();


        $totalRows = \Branch::withTrashed()->count();

        $offset = intval(array_get($input, 'records_per_page', 10));
        if ( $offset == -1 ) {
            $offset = $totalRows;

        }


		$branches = \Branch::withTrashed()->search($input)->orderBy('id', 'desc')->paginate($offset);
		


		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		$countries = \Config::get('agrivet.countries');
		return \View::make('admin.branch.index')
			->with('branches', $branches)
			->with('countries', $countries)
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

		$countries = \Config::get('agrivet.countries');
		$default_country_code = \Config::get('agrivet.default_country_code');

		return \View::make('admin.branch.create')->with('countries', $countries)->with('default_country_code', $default_country_code);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = \Input::all();

		$rules = \Branch::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$branch = new \Branch;

				if ($branch->doSave($branch, $input)) {
					return \Redirect::route('admin_branches.index')->with('success', \Lang::get('agrivet.created'));
				}

				return \Redirect::back()->withErrors($branch->errors())->withInput();
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
			$branch = \Branch::findOrFail($id);
			$countries = \Config::get('agrivet.countries');
		} catch(\Exception $e) {
			return \Redirect::back()->with('info', \Lang::get('agrivet.errors.restore'));
		}

		return \View::make('admin.branch.edit')->with('branch', $branch)->with('countries', $countries);
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

		$rules = \Branch::$rules;

		$rules['address'] = 'required|unique:branches,address,'.$id.',id';

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$branch = \Branch::findOrFail($id);
				
				if ($branch->doSave($branch, $input)) {
					return \Redirect::route('admin_branches.index')->with('success', \Lang::get('agrivet.updated'));
				}

				return \Redirect::back()->withErrors($branch->errors())->withInput();
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
		$branch = \Branch::withTrashed()->where('id', $id)->first();
		$message = \Lang::get('agrivet.trashed');
		if ($branch->trashed() || \Input::get('remove') == 1) {
            $branch->forceDelete();
            $message = \Lang::get('agrivet.deleted');
        } else {
            $branch->delete();
        }

        // Session::set('success', 'Successfully deleted');
        return \Redirect::route('admin_branches.index')->with('success', $message);
        
	}


	/**
	 * Restore deleted resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function restore($id) {
		$branch = \Branch::withTrashed()->where('id', $id)->first();
		if (!$branch->restore()) {
			return \Redirect::back()->withErrors($branch->errors());			
		}

		return \Redirect::back()->with('success', \Lang::get('agrivet.restored'));
	}


}
