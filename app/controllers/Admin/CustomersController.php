<?php
/**
 * Created by PhpStorm.
 * User: Serg
 * Date: 3/23/2015
 * Time: 11:23 PM
 */

namespace Admin;


class CustomersController extends \BaseController {


    public function index() {

        $input = \Input::all();
        $totalRows = \Customer::count();

        $offset = intval(array_get($input, 'records_per_page', 10));
        if ( $offset == -1 ) {
            $offset = $totalRows;

        }
        $customers = \Customer::orderBy('customer_id', 'desc')->belongToBranch()->paginate($offset);



        $appends = ['records_per_page' => \Input::get('records_per_page', 10)];


        return \View::make('admin.customer.index')
            ->with('customers', $customers)
            ->with('appends', $appends)
            ->with('totalRows', $totalRows);

    }


    public function create() {
        return \View::make('admin.customer.create')
            ->with('branches', \Branch::dropdown()->lists('name', 'id'));
    }


    public function store() {
        $input = \Input::all();

        $rules = \Customer::$rules;
        $rules['customer_name']	= 'required|unique:customers,customer_name,NULL,customer_id';

        $validator = \Validator::make($input, $rules);

        if ($validator->fails()) {
            return \Redirect::back()->withErrors([$validator->getMessage()])->withInput();
        }

        try {

            $customer = new \Customer;

            if ($customer->doSave($customer, $input)) {
                return \Redirect::route('admin_customers.index')->with('success', \Lang::get('agrivet.created'));
            }

            return \Redirect::back()->withErrors([$customer->getMessage()])->withInput();
        } catch (\Exception $e) {
            return \Redirect::back()->withErrors([$e->getMessage()])->withInput();
        }
    }

    public function show($id) {
        $customer = \Customer::findOrFail($id);
        return \View::make('admin.customer.show')
            ->with('customer', $customer)
            ->with('customers', array_add( $credits = \Customer::hasCredits()->belongToBranch()->lists('customer_name', 'customer_id'), '', 'Select Customer'));

    }

    public function edit($id) {

        $customer = \Customer::findOrFail($id);
        return \View::make('admin.customer.edit')
            ->with('customer', $customer)
            ->with('branches', \Branch::dropdown()->lists('name', 'id'));
    }


    public function update($id) {
        $input = \Input::all();

        $rules = \Customer::$rules;
        $rules['customer_name']	= 'required|unique:customers,customer_name,'. $id .',customer_id';

        $validator = \Validator::make($input, $rules);



        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator->errors())->withInput();
        }

        try {

            $customer = \Customer::findOrFail($id);

            if ($customer->doSave($customer, $input)) {
                return \Redirect::back()->with('success', \Lang::get('agrivet.updated'));
            }

            return \Redirect::back()->withErrors($customer->errors())->withInput();
        } catch (\Exception $e) {
            return \Redirect::back()->withErrors([$e->getMessage()])->withInput();
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
            $customer = \Customer::findOrFail($id);
            $message = \Lang::get('agrivet.trashed');
            if (!$customer->delete()) {
                return \Redirect::back()->withErrors($customer->errors());
            }

            return \Redirect::route('admin_customers.index')->with('success', $message);

        } catch (\FatalErrorException $e) {
            return \Redirect::back()->withErrors((array)$e->getMessage());
        } catch (\Exception $e) {
            return \Redirect::back()->withErrors((array)$e->getMessage());
        }
    }

    public function lists() {
        $customers = \Customer::all();
        if (\Request::ajax()) {
            return \Response::json(['customers' => $customers]);
        }

    }
}