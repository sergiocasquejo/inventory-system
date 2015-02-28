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


		$sales = \Sale::withTrashed()
				->filter($input)
				->orderBy('sale_id', 'desc')
				->paginate(intval(array_get($input, 'records_per_page', 10)));
		
		$totalRows = \Sale::withTrashed()->count();

		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		$countries = \Config::get('agrivate.countries');



		$yearly = \Sale::whereRaw('YEAR(date_of_sale) = YEAR(CURDATE())')->sum('total_amount');
		$monthly = \Sale::whereRaw('MONTH(date_of_sale) = MONTH(CURDATE())')->sum('total_amount');
		$weekly = \Sale::whereRaw('WEEK(date_of_sale) = WEEK(CURDATE())')->sum('total_amount');
		$daily = \Sale::whereRaw('DAY(date_of_sale) = DAY(CURDATE())')->sum('total_amount');


		$all = [
			'daily' => $daily,
			'weekly' => $weekly,
			'monthly' => $monthly,
			'yearly' => $yearly,
			'branches' =>array_add(\DB::table('sales')->join('branches', 'sales.branch_id', '=', 'branches.id')
					->select(\DB::raw('CONCAT(SUBSTRING(name, 1, 20),"...") AS name, id'))
					 ->lists('name', 'id'), '', 'Branch'),
			'products' => array_add(\DB::table('sales')
					->join('products', 'sales.product_id', '=', 'products.id')
					->select(\DB::raw('CONCAT(SUBSTRING(name, 1, 20),"...") AS name, id'))
					->lists('name', 'id'), '', 'Product'),
			'totals' => array_add(\Sale::all()->lists('total_amount', 'total_amount'), '', 'Amount'),
			'days' => array_add(\Sale::select(\DB::raw('DAY(date_of_sale) as day'))->lists('day', 'day'), '', 'Day'),
			'months' => array_add(\Sale::select(\DB::raw('DATE_FORMAT(date_of_sale, "%b") as month, MONTH(date_of_sale) as month_no'))->lists('month', 'month_no'), '', 'Month'),
			'years' => array_add(\Sale::select(\DB::raw('YEAR(date_of_sale) as year'))->lists('year', 'year'), '', 'Year'),
			'statuses' => array_add(\Sale::select(\DB::raw('status, IF (status = 1, \'Active\', \'Inactive\') as name'))->lists('name', 'status'), '', 'Status'),
		];

		

		return \View::make('admin.sale.index', $all)
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

		return \View::make('admin.sale.edit')
		->with('sale', $sale)
		->with('branches', \Branch::all()->lists('name', 'id'))
		->with('products', \Product::all()->lists('name', 'id'));
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
		$input['encoded_by'] = \Confide::user()->id;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$sale = \Sale::findOrFail($id);
				
				if ($sale->doSave($sale, $input)) {
					return \Redirect::route('admin_sales.index')->with('success', \Lang::get('agrivate.updated'));
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
