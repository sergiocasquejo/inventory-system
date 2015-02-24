<?php namespace Admin;

class ExpensesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$expenses = \Expense::withTrashed();


		return \View::make('admin.expense.index')->with('expenses', $expenses);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return \View::make('admin.expense.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = \Input::all();

		$rules = \Expense::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$expense = new \Expense;

				if ($expense->doSave($expense, $input)) {
					return \Redirect::route('admin_branches.index')->with('success', \Lang::get('agrivate.created'));
				}

				return \Redirect::back()->withErrors($expense->errors())->withInput();
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

		$expense = \Expense::find($id);
		
		return \View::make('admin.expense.edit')->with('expense', $expense);
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

		$rules = \Expense::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$expense = \Expense::findOrFail($id);
				
				if ($expense->doSave($expense, $input)) {
					return \Redirect::route('admin_branches.index')->with('success', \Lang::get('agrivate.updated'));
				}

				return \Redirect::back()->withErrors($expense->errors())->withInput();
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
		$expense = \Expense::withTrashed()->where('id', $id)->first();
		$message = \Lang::get('agrivate.trashed');
		if ($expense->trashed()) {
            $expense->forceDelete();
            $message = \Lang::get('agrivate.deleted');
        } else {
            $expense->delete();
        }

        return \Redirect::route('admin_branches.index')->with('success', $message);
	}


}
