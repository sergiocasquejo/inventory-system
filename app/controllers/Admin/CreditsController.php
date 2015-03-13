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


		$credits = \Credit::withTrashed()
			->filterBranch()
			->filter($input)
			->search($input)
			->orderBy('credit_id', 'desc')
			->paginate(intval(array_get($input, 'records_per_page', 10)));
		
		$totalRows = \Credit::withTrashed()->filterBranch()->count();

		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		// $countries = \Config::get('agrivate.countries');



		$yearly = \Credit::filterBranch()->whereRaw('YEAR(date_of_credit) = YEAR(CURDATE())')->sum('total_amount');
		$monthly = \Credit::filterBranch()->whereRaw('MONTH(date_of_credit) = MONTH(CURDATE())')->sum('total_amount');
		$weekly = \Credit::filterBranch()->whereRaw('WEEK(date_of_credit) = WEEK(CURDATE())')->sum('total_amount');
		$daily = \Credit::filterBranch()->whereRaw('DAY(date_of_credit) = DAY(CURDATE())')->sum('total_amount');

		$branches = \DB::table('expenses')->join('branches', 'expenses.branch_id', '=', 'branches.id')
					->select(\DB::raw('CONCAT(SUBSTRING('.\DB::getTablePrefix().'branches.name, 1, 20),"...") AS name, '.\DB::getTablePrefix().'branches.id'));


					
		// Filter branch if user is not owner
		if (!\Confide::user()->isAdmin()) {
			$branches = $branches->where('branches.id', \Confide::user()->branch_id);
		}


		$all = [
			'daily' => $daily,
			'weekly' => $weekly,
			'monthly' => $monthly,
			'yearly' => $yearly,
			'branches' =>array_add($branches->lists('name', 'id'), '', 'Branch'),
			'totals' => array_add(\Credit::filterBranch()->lists('total_amount', 'total_amount'), '', 'Amount'),
			'days' => array_add(\Credit::filterBranch()->select(\DB::raw('DAY(date_of_credit) as day'))->lists('day', 'day'), '', 'Day'),
			'months' => array_add(\Credit::filterBranch()->select(\DB::raw('DATE_FORMAT(date_of_credit, "%b") as month, MONTH(date_of_credit) as month_no'))->lists('month', 'month_no'), '', 'Month'),
			'years' => array_add(\Credit::filterBranch()->select(\DB::raw('YEAR(date_of_credit) as year'))->lists('year', 'year'), '', 'Year'),
			'statuses' => array_add(\Credit::filterBranch()->select(\DB::raw('is_paid, IF (is_paid = 1, \'Paid\', \'Not Paid\') as name'))->lists('name', 'is_paid'), '', 'Is Paid?'),
		];



		return \View::make('admin.credit.index', $all)
			->with('credits', $credits)
			->with('branches', \Branch::filterBranch()->select(\DB::raw('CONCAT(address, " ", city) as name'), 'id')->lists('name', 'id'))
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
		return \View::make('admin.credit.create')
		->with('branches', \Branch::filterBranch()->dropdown()->lists('name', 'id'))
		->with('products', array_add(\Product::all()->lists('name', 'id'), '0', 'Select Product'))
		->with('measures', array_add(\UnitOfMeasure::all()->lists('label', 'name'), '', 'Select Measure'));
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

		if (!\Confide::user()->isAdmin()) {
			$input['branch_id'] = \Confide::user()->branch_id;
		}

		$rules = \Credit::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$credit = new \Credit;


				if ($credit->doSave($credit, $input)) {
					return \Redirect::route('admin_credits.edit', $credit->credit_id)->with('success', \Lang::get('agrivate.created'));
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
		
		return \View::make('admin.credit.edit')->with('credit', $credit)
		->with('branches', \Branch::filterBranch()->dropdown()->lists('name', 'id'))
		->with('products', array_add(\Product::all()->lists('name', 'id'), '0', 'Select Product'))
		->with('measures', array_add(\UnitOfMeasure::all()->lists('label', 'name'), '', 'Select Measure'));
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

		if (!\Confide::user()->isAdmin()) {
			$input['branch_id'] = \Confide::user()->branch_id;
		}
		
		$rules = array_except(\Credit::$rules, 'encoded_by');

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
		$credit = \Credit::withTrashed()->where('credit_id', $id)->first();
		$message = \Lang::get('agrivate.trashed');
		if ($credit->trashed() || \Input::get('remove') == 1) {
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
		$credit = \Credit::withTrashed()->where('credit_id', $id)->first();
		if (!$credit->restore()) {
			return \Redirect::back()->withErrors($credit->errors());			
		}

		return \Redirect::back()->with('success', \Lang::get('agrivate.restored'));
	}


}
