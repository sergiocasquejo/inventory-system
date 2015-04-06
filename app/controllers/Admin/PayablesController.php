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
        $expenses = array();

        if ($input) {
            $lists = \Expense::filter($input)->owned()->payable()->orderBy('expense_id', 'desc');


            $totalRows = $lists->count();

            $offset = intval(array_get($input, 'records_per_page', 10));
            if ($offset == -1) {
                $offset = $totalRows;

            }

            $expenses = $lists->paginate($offset);


            $appends = ['records_per_page' => \Input::get('records_per_page', 10)];

        }
        $branch = \Branch::findOrFail(array_get($input, 'branch'));
        $supplier = \Supplier::findOrFail(array_get($input, 'supplier'));

        return \View::make('admin.credit.payable_details')
            ->with('branch', $branch->address)
            ->with('supplier', $supplier->supplier_name)
            ->with('suppliers', array_add(\Supplier::hasPayables()->lists('supplier_name', 'supplier_id'), '', 'Select Supplier'))
            ->with('expenses', $expenses)
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

                $supplierId = array_get($input, 'supplier');
                $supplier = \Supplier::find($supplierId);

                $input['branch_id'] = $supplier->branch_id;
                $input['expense_type'] = 'STORE EXPENSES';
                $input['product_id'] = 0;
                $input['supplier_price'] = 0;
                $input['selling_price'] = 0;
                $input['quantity'] = 0;
                $input['uom'] = '';
                $input['total_amount'] = array_get($input, 'amount');
                $input['comments'] = array_get($input, 'comments');
                $input['date_of_sale'] = date('Y-m-d');
                $input['encoded_by'] = \Confide::user()->id;

                $errors = [];

                \DB::transaction(function() use (&$input, &$supplier, &$errors) {
                    $expense = new \Expense;

                    if (!$expense->doSave($expense, $input)) {
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