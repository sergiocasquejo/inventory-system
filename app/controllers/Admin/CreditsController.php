<?php namespace Admin;

class CreditsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$input = \Input::all();


		$credits = \Credit::withTrashed()->search($input)->orderBy('id', 'desc')->paginate(intval(array_get($input, 'records_per_page', 10)));
		
		$totalRows = \Credit::withTrashed()->count();

		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		$countries = \Config::get('agrivate.countries');
		return \View::make('admin.credit.index')
			->with('credits', $credits)
			->with('branches', \Branch::all()->lists('name', 'id'))
			->with('appends', $appends)
			->with('totalRows', $totalRows);


		$credits = \Credit::withTrashed();


		;
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return \View::make('admin.credit.create');
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

		$rules = \Credit::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$credit = new \Credit;


				if ($credit->doSave($credit, $input)) {
					return \Redirect::route('admin_credits.edit', $credit->id)->with('success', \Lang::get('agrivate.created'));
				}

				return \Redirect::back()->withErrors($credit->errors())->withInput();
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

		$credit = \Credit::find($id);
		
		return \View::make('admin.credit.edit')->with('credit', $credit)->with('branches', array_add(\Branch::all()->lists('name', 'id'), '', 'Select Branch'));
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

		$rules = array_except(\Credit::$rules, 'encoded_by');

		$rules['name'] = $rules['name'].','.$id.',credit_id';

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$credit = \Credit::findOrFail($id);
				
				$input['encoded_by'] = $credit->encoded_by;
				if ($credit->doSave($credit, $input)) {
					return \Redirect::route('admin_credits.index')->with('success', \Lang::get('agrivate.updated'));
				}

				return \Redirect::back()->withErrors($credit->errors())->withInput();
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
		$credit = \Credit::withTrashed()->where('id', $id)->first();
		$message = \Lang::get('agrivate.trashed');
		if ($credit->trashed()) {
            $credit->forceDelete();
            $message = \Lang::get('agrivate.deleted');
        } else {
            $credit->delete();
        }

        return \Redirect::route('admin_credits.index')->with('success', $message);
	}

	/**
	 * Restore deleted resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function restore($id) {
		$credit = \Credit::withTrashed()->where('id', $id)->first();
		if (!$credit->restore()) {
			return \Redirect::back()->withErrors($credit->errors());			
		}

		return \Redirect::back()->with('success', \Lang::get('agrivate.restored'));
	}


}
