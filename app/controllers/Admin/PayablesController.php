<?php
/**
 * Created by PhpStorm.
 * User: Serg
 * Date: 3/31/2015
 * Time: 9:33 PM
 */

namespace Admin;


class PayablesController extends \BaseController {


    public function index() {
        $input = \Input::all();

        $totalRows = \Supplier::count();

        $offset = intval(array_get($input, 'records_per_page', 10));
        if ( $offset == -1 ) {
            $offset = $totalRows;

        }

        $payables = \Supplier::search($input)->orderBy('supplier_id', 'desc')->paginate($offset);



        $appends = ['records_per_page' => \Input::get('records_per_page', 10)];

        return \View::make('admin.credit.payables')
            ->with('payables', $payables)
            ->with('appends', $appends)
            ->with('branches', array_add(\Branch::lists('address', 'id'), '', 'Branch'))
            ->with('brands', array_add(\Brand::all()->lists('name', 'brand_id'), '', 'Select Brand'))
            ->with('suppliers', array_add(\Supplier::hasPayables()->lists('supplier_name', 'supplier_id'), '', 'Select Supplier'))
            ->with('totalRows', $totalRows);

    }


    public function details() {
        $input = \Input::all();


        if (array_get($input, 'branch') == '' || array_get($input, 'supplier') == '') {
            return \Redirect::route('admin_credits.payables')->with('warning', 'Please select or filter');
        }

        $appends = '';
        $totalRows = 0;
        $payables = array();

        if ($input) {
            $lists = \Payable::filter($input)->owned()->orderBy('payable_id', 'desc');


            $totalRows = $lists->count();

            $offset = intval(array_get($input, 'records_per_page', 10));
            if ($offset == -1) {
                $offset = $totalRows;

            }

            $payables = $lists->paginate($offset);


            $appends = ['records_per_page' => \Input::get('records_per_page', 10)];

        }
        $branch = \Branch::findOrFail(array_get($input, 'branch'));
        $supplier = \Supplier::findOrFail(array_get($input, 'supplier'));

        return \View::make('admin.credit.payable_details')
            ->with('branch', $branch->address)
            ->with('supplier', $supplier->supplier_name)
            ->with('suppliers', array_add(\Supplier::hasPayables()->lists('supplier_name', 'supplier_id'), '', 'Select Supplier'))
            ->with('payables', $payables)
            ->with('appends', $appends)
            ->with('totalRows', $totalRows);
    }


    public function edit($id) {
        $supplier = \Supplier::findOrFail($id);
        return \View::make('admin.credit.edit_payable')->with('supplier', $supplier);

    }

    public function update($id) {

        $input = \Input::only('total_payables');
        $rules['total_payables'] = 'required|numeric';

        $validator = \Validator::make($input, $rules);

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator->errors())->withInput();
        }

        $amount = array_get($input, 'total_payables', 0);
        $supplier = \Supplier::findOrFail($id);
        $supplier->total_payables = $amount;
        if ($supplier->save()) {
            return \Redirect::back()->with('success', \Lang::get('agrivet.updated'));
        }
        return \Redirect::back()->withError($supplier->errors());
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $payable = \Payable::findOrFail($id);
        $message = \Lang::get('agrivet.deleted');

        if (!$payable->delete()) {
            return \Redirect::back()->withErrors($payable->errors());
        }


        return \Redirect::back()->with('success', $message);

    }




    public  function infoBySupplierId($supplierId) {

        $payables = \Supplier::findOrFail($supplierId);

        return \Response::json(['data' => $payables]);
    }


    public  function infoByCusId($cusId) {

        $credits = \Customer::findOrFail($cusId);

        return \Response::json(['data' => $credits]);
    }





    public function partialPayablePayment() {

        $input = \Input::all();

        try {

            $rules = [
                'amount' => 'required|numeric|min:1',
                'supplier' => 'required|exists:suppliers,supplier_id'
            ];


            $validator = \Validator::make($input, $rules);

            if ($validator->fails()) {
                return \Response::json(['errors' => $validator->errors()]);
            } else {



                $errors = [];

                \DB::transaction(function() use (&$input, &$supplier, &$errors) {

                    $expense = new \Expense;

                    $supplierId = array_get($input, 'supplier');
                    $supplier = \Supplier::findOrFail($supplierId);

                    $expense->is_payable = 1;
                    $expense->branch_id = $supplier->location;
                    $expense->name = 'PARTIAL PAYMENT';
                    $expense->expense_type = 'STORE EXPENSES';
                    $expense->total_amount = array_get($input, 'amount');
                    $expense->comments = array_get($input, 'comments');
                    $expense->date_of_expense = date('Y-m-d H:i:s');
                    $expense->encoded_by = \Confide::user()->id;


                    if (!$expense->save()) {
                        $errors[] = $expense->errors();
                    } else {

                        $supplier->total_payables = $supplier->total_payables - array_get($input, 'amount');
                        if (! $supplier->save()) {
                            $errors[] = $supplier->errors();
                        }
                    }
                });


                if (count($errors) != 0) {
                    return \Response::json(['errors' => $errors]);
                } else {
                    return \Response::json(['success' => 'Successfully saved.']);
                }
            }

        } catch(\Exception $e) {
            return \Response::json(['error' =>  $e->getMessage()]);
        }




    }
}