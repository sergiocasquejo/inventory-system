<?php
/**
 * Created by PhpStorm.
 * User: Serg
 * Date: 3/26/2015
 * Time: 9:28 PM
 */

namespace Admin;


class SuppliersController extends \BaseController {
    public function index() {
        $input = \Input::all();

        $totalRows = \Supplier::count();

        $offset = intval(array_get($input, 'records_per_page', 10));
        if ( $offset == -1 ) {
            $offset = $totalRows;

        }

        $suppliers = \Supplier::search($input)->orderBy('supplier_id', 'desc')->paginate($offset);



        $appends = ['records_per_page' => \Input::get('records_per_page', 10)];

        return \View::make('admin.supplier.index')
            ->with('suppliers', $suppliers)
            ->with('appends', $appends)
            ->with('totalRows', $totalRows);
    }

    public function create() {
        return \View::make('admin.supplier.create')
            ->with('branches', array_add(\Branch::filterBranch()->dropdown()->lists('name', 'id'), '', 'Select Branch'));
    }

    public function store() {
        $input = \Input::all();

        $rules = \Supplier::$rules;

        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator->errors())->withInput();
        } else {
            try {

                $supplier = new \Supplier;

                if ( !$supplier->doSave($supplier, $input) ) {
                    return \Redirect::back()->withErrors($supplier->errors())->withInput();
                }

                return \Redirect::route('admin_suppliers.index')->with('success', \Lang::get('agrivet.created'));
            } catch (\Exception $e) {
                return \Redirect::back()->withErrors([$e->getMessage()])->withInput();
            }

        }

    }

    public function show($id) {
        $supplier = \Supplier::findOrFail($id);
        return \View::make('admin.supplier.show')
            ->with('supplier', $supplier)
            ->with('suppliers', array_add(\Supplier::hasPayables()->lists('supplier_name', 'supplier_id'), '', 'Select Supplier'));

    }


    public function edit($id) {
        $supplier = \Supplier::findOrFail($id);
        return \View::make('admin.supplier.edit')->with('supplier', $supplier)
            ->with('branches', array_add(\Branch::filterBranch()->dropdown()->lists('name', 'id'), '', 'Select Branch'));

    }

    public function update($id) {
        $input = \Input::all();



        $rules = \Supplier::$rules;
        $rules['supplier_name'] = 'required|max:120|unique:suppliers,supplier_name,'. $id .',supplier_id';

        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator->errors())->withInput();
        } else {
            try {

                $supplier = \Supplier::findOrFail($id);

                if ( !$supplier->doSave($supplier, $input) ) {
                    return \Redirect::back()->withErrors($supplier->errors())->withInput();
                }

                return \Redirect::route('admin_suppliers.index')->with('success', \Lang::get('agrivet.created'));
            } catch (\Exception $e) {
                return \Redirect::back()->withErrors([$e->getMessage()])->withInput();
            }

        }
    }

    public function destroy($id) {
        try {
            $supplier = \Supplier::findOrFail($id);
            if (!$supplier->delete()) {
                return \Redirect::back()->withErrors($supplier->errors());
            }

            return \Redirect::route('admin_suppliers.index')->with('success', \Lang::get('agrivet.trashed'));

        } catch (\FatalErrorException $e) {
            return \Redirect::back()->withErrors((array)$e->getMessage());
        } catch (\Exception $e) {
            return \Redirect::back()->withErrors((array)$e->getMessage());
        }
    }


    public function getByBranch() {
        $branch = \Input::get('branch');
        $suppliers = [];

        if (is_numeric($branch)) {
            $suppliers = \Supplier::byBranch($branch)->select('supplier_name', 'supplier_id')->get();
        }
        return \Response::json($suppliers);

    }
}