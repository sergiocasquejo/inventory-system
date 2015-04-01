<?php namespace Admin;

class CreditsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{


		try {

            $input = \Input::all();

            $lists =  \Credit::withTrashed()
                ->join('sales', 'credits.sale_id', '=', 'sales.sale_id')
                ->join('customers', 'customers.customer_id', '=', 'credits.customer_id')
                //->unPaid()
                ->owned()
                ->filter($input)->orderBy('credit_id', 'desc');

            $totalRows = $lists->count();

            $offset = intval(array_get($input, 'records_per_page', 10));
            if ( $offset == -1 ) {
                $offset = $totalRows;

            }

            $credits = $lists->search($input)->paginate($offset);



            $appends = ['records_per_page' => \Input::get('records_per_page', 10)];






            $branches = \DB::table('expenses')->join('branches', 'expenses.branch_id', '=', 'branches.id')
                ->select(\DB::raw('CONCAT(SUBSTRING(' . \DB::getTablePrefix() . 'branches.name, 1, 20),"...") AS name, ' . \DB::getTablePrefix() . 'branches.id'));


            // Filter branch if user is not owner
            if (!\Confide::user()->isAdmin()) {
                $branches = $branches->where('branches.id', \Confide::user()->branch_id);
            }


            $all = [
                'totals' => array_add(\Sale::filterBranch()->join('credits', 'credits.sale_id', '=', 'sales.sale_id')->lists('total_amount', 'total_amount'), '', 'Amount'),
                'days' => array_add(\Sale::filterBranch()->join('credits', 'credits.sale_id', '=', 'sales.sale_id')->select(\DB::raw('DAY(date_of_sale) as day'))->lists('day', 'day'), '', 'Day'),
                'months' => array_add(\Sale::filterBranch()->join('credits', 'credits.sale_id', '=', 'sales.sale_id')->select(\DB::raw('DATE_FORMAT(date_of_sale, "%b") as month, MONTH(date_of_sale) as month_no'))->lists('month', 'month_no'), '', 'Month'),
                'years' => array_add(\Sale::filterBranch()->join('credits', 'credits.sale_id', '=', 'sales.sale_id')->select(\DB::raw('YEAR(date_of_sale) as year'))->lists('year', 'year'), '', 'Year'),
            ];


            return \View::make('admin.credit.index', $all)
                ->with('credits', $credits)
                ->with('customers', array_add( $credits = \Customer::hasCredits()->belongToBranch()->lists('customer_name', 'customer_id'), '', 'Select Customer'))
                ->with('branches', array_add(\Branch::filterBranch()->select(\DB::raw('CONCAT(address, " ", city) as name'), 'id')->lists('name', 'id'), '', 'Select Branch'))
                ->with('appends', $appends)
                ->with('totalRows', $totalRows);
        } catch (\Exception $e) {
            return \Redirect::back()->withErrors([$e->getMessage()]);
        }

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return \View::make('admin.credit.create')
		->with('reviews', \Session::get('creditsReview'))
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

        $rules = \Customer::$rules;

        if (array_get($input, 'customer_id') == 0) {
            $rules['customer_name']	= 'required|unique:customers,customer_name,NULL,customer_id,address,'.array_get($input, 'address', '');
        } else {
            $rules['customer_name']	= 'required|unique:customers,customer_name,'. array_get($input, 'customer_id')  .',customer_id,address,'.array_get($input, 'address', '');
        }


        $validator = $this->validateCustomer($input, $rules);
        if ( $validator->fails()) {
            return \Redirect::back()->withErrors($validator->errors())->withInput();
        }

		if (array_get($input, 'action') == 'review') {
			$reviewId = false;
			if (array_get($input, 'review_id')) {
				$reviewId = array_get($input, 'review_id');
			}
			return $this->review($reviewId);
		}


		$input['encoded_by'] = \Confide::user()->id;

		if (!\Confide::user()->isAdmin()) {
			$input['branch_id'] = \Confide::user()->branch_id;
		}
		$input['is_paid'] = 0;

		$rules = \Credit::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {

				$errors = [];

				\DB::transaction(function() use(&$input, &$errors) {
					

					// Get user branch
					$branch_id = array_get($input, 'branch_id');
                    $oldMeasure = $uom = array_get($input, 'uom');
					$product = array_get($input, 'product_id');
					$quantity = array_get($input, 'quantity');

					// Convert sack to kg
					if (strpos($uom,'sack') !== false) {
						$oldMeasure = $input['uom'];
						$input['quantity'] = $quantity * \Config::get('agrivet.equivalent_measure.sacks.per');
						$input['uom'] = $uom = 'kg';
					}


					$stock = \StockOnHand::where('product_id', $product)
									->where('branch_id', $branch_id)
									->where('uom', $uom)
									->first();


                    if (array_get($input, 'customer_id') == 0) {
                        $customer = new \Customer;
                    } else {
                        $customer = \Customer::findOrFail(array_get($input, 'customer_id'));
                    }
                    $input['total_credits'] = $customer->total_credits + array_get($input, 'total_amount');
                    $customer->doSave($customer, $input);
                    $input['customer_id'] = $customer->customer_id;

					if ($stock && $stock->total_stocks > 0) {

						if ($stock->total_stocks >= array_get($input, 'quantity', 0)) {
							$branch = \ProductPricing::whereRaw("branch_id = {$branch_id} AND product_id = {$product}  AND per_unit = '{$uom}'")->first();

							$input['supplier_price'] = 	$branch->supplier_price;
							$input['selling_price'] = 	$branch->selling_price;
							$input['total_amount'] = 	$branch->selling_price * $input['quantity'];
							$input['sale_type'] = 	"CREDIT";
							$input['encoded_by'] 	= 	\Confide::user()->id;

							$sale = new \Sale;

							if (!$sale->doSave($sale, $input)) {			
								$errors = $sale->errors();
							} else {
								$stock->total_stocks = $stock->total_stocks - array_get($input, 'quantity');
								$stock->save();

								$credit = new \Credit;
								$input['sale_id'] = $sale->sale_id;
								$credit->doSave($credit, $input);

							}
						} else {
							if (strpos($oldMeasure, 'sack') !== false) $input['uom'] = $oldMeasure;
							$errors = [\Lang::get('agrivet.errors.insufficient_stocks', ['stocks' => $stock->total_stocks .' '.$uom])];
						}

					} else {
						if (strpos($oldMeasure,'sack') !== false) $input['uom'] = $oldMeasure;
						$errors = [\Lang::get('agrivet.errors.out_of_stocks')];

					}
				});
				

				if (count($errors) == 0) {
					return \Redirect::route('admin_credits.index')->with('success', \Lang::get('agrivet.created'));
				} else {
					return \Redirect::back()->withErrors($errors)->withInput($input);
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

        $rules = \Customer::$rules;

        if (array_get($input, 'customer_id') == 0) {
            $rules['customer_name']	= 'required|unique:customers,customer_name,NULL,customer_id,address,'.array_get($input, 'address', '');
        } else {
            $rules['customer_name']	= 'required|unique:customers,customer_name,'. array_get($input, 'customer_id')  .',customer_id,address,'.array_get($input, 'address', '');
        }

        $rules['branch_id'] = '';

        $validator = $this->validateCustomer($input, $rules);
        if ( $validator->fails()) {
            return \Redirect::back()->withErrors($validator->errors())->withInput();
        }


		if (!\Confide::user()->isAdmin()) {
			$input['branch_id'] = \Confide::user()->branch_id;
		}
		
		$rules = array_except(\Credit::$rules, 'encoded_by');

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {


				$errors = [];

				\DB::transaction(function() use(&$input,$id, &$errors) {
					

					// Get user branch
					$branch_id = array_get($input, 'branch_id');
                    $oldMeasure = $uom = array_get($input, 'uom');
					$product = array_get($input, 'product_id');
					$quantity = array_get($input, 'quantity', 0);

					// Convert sack to kg
					if (strpos($uom,'sack') !== false) {
						$oldMeasure = $input['uom'];
						$input['quantity'] = $quantity * \Config::get('agrivet.equivalent_measure.sacks.per');
						$input['uom'] = $uom = 'kg';
					}

					$stock = \StockOnHand::where('product_id', $product)
									->where('branch_id', $branch_id)
									->where('uom', $uom)
									->first();


					$input['sale_type'] 	= 	"CREDIT";


					$credit = \Credit::findOrFail($id);

					$sale = \Sale::findOrFail($credit->sale_id);

                    if (array_get($input, 'customer_id') == 0) {
                        $customer = new \Customer;
                    } else {
                        $customer = \Customer::findOrFail($credit->customer_id);
                    }

                    $customer->doSave($customer, $input);

                    $input['customer_id'] = $customer->customer_id;

                    if (!$sale->doSave($sale, $input)) {
                        $errors = $sale->errors();
                    }


				});



				if (count($errors) == 0) {
					return \Redirect::route('admin_credits.index')->with('success', \Lang::get('agrivet.updated'));
				} else {
					return \Redirect::back()->withErrors($credit->errors())->withInput();
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
		$message = \Lang::get('agrivet.trashed');
        try {
            if (!$credit->sale)  return \Redirect::back();

            if ($credit->trashed() || \Input::get('remove') == 1) {
                $credit->sale->forceDelete();
                $message = \Lang::get('agrivet.deleted');
            } else {
                $credit->sale->delete();
            }
        } catch(\FatalErrorException $e) {
            return \Redirect::back()->withErrors([$e->getMessage()]);
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
		if (!$credit->restore($credit->credit_id)) {
			return \Redirect::back()->withErrors($credit->errors());			
		}

		return \Redirect::back()->with('success', \Lang::get('agrivet.restored'));
	}

    private function validateCustomer($input, $rules) {




        $validator = \Validator::make($input, $rules);

        return $validator;

    }



	public function review($reviewId = false) {
        $input = \Input::all();

        $rules = \Customer::$rules;

        if (array_get($input, 'customer_id') == 0) {
            $rules['customer_name']	= 'required|unique:customers,customer_name,NULL,customer_id,address,'.array_get($input, 'address', '');
        } else {
            $rules['customer_name']	= 'required|unique:customers,customer_name,'. array_get($input, 'customer_id')  .',customer_id,address,'.array_get($input, 'address', '');
        }

        $validator = $this->validateCustomer($input, $rules);
        if ( $validator->fails()) {
            return \Redirect::back()->withErrors($validator->errors())->withInput();
        }


		$rules = \Sale::$rules;

		if (!\Confide::user()->isAdmin()) {
			$input['branch_id'] = \Confide::user()->branch_id;
		}
		$input['status'] = 0;
		$input['encoded_by'] = \Confide::user()->id;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {

				$review = [];

				if (\Session::has('creditsReview')) {
					$review = \Session::get('creditsReview');	
				}
				
				if (!$reviewId) {
					$reviewId = time();
				}






				$review[$reviewId] = array_add($input, 'branch_id', $input['branch_id']);


				\Session::put('creditsReview', $review);
				

				return \Redirect::route('admin_credits.create')->with('success', \Lang::get('agrivet.add_to_review'));

			} catch(\Exception $e) {
				return \Redirect::back()->withErrors((array)$e->getMessage())->withInput($input);
			}
		}
	}

	public function deleteReview($reviewId) {
		\Session::forget("creditsReview.$reviewId");

		return \Redirect::route('admin_credits.create')->with('success', \Lang::get('agrivet.deleted'));
	}

	public function saveReview() {
		try {
			$reviews = \Session::get('creditsReview');
			$errors = [];

			foreach ($reviews as $key => $input) {
				$input['encoded_by'] = \Confide::user()->id;
				$input['is_paid'] = 0;

				$errors = [];

				\DB::transaction(function() use(&$input, &$errors) {
					

					// Get user branch
					$branch_id = array_get($input, 'branch_id');
                    $oldMeasure = $uom = array_get($input, 'uom');
					$product = array_get($input, 'product_id');
					$quantity = array_get($input, 'quantity');

					// Convert sack to kg
					if (strpos($uom,'sack') !== false) {
						$oldMeasure = $input['uom'];
						$input['quantity'] = $quantity * \Config::get('agrivet.equivalent_measure.sacks.per');
						$input['uom'] = $uom = 'kg';
					}


					$stock = \StockOnHand::where('product_id', $product)
									->where('branch_id', $branch_id)
									->where('uom', $uom)
									->first();

		
					if ($stock && $stock->total_stocks > 0) {

						if ($stock->total_stocks >= array_get($input, 'quantity', 0)) {
							$branch = \ProductPricing::whereRaw("branch_id = {$branch_id} AND product_id = {$product}  AND per_unit = '{$uom}'")->first();

							$input['sale_type'] = 	"CREDIT";
							$input['supplier_price'] = 	$branch->supplier_price;
							$input['selling_price'] = 	$branch->selling_price;
							$input['total_amount'] = 	$branch->selling_price * $input['quantity'];


							$sale = new \Sale;

							if (!$sale->doSave($sale, $input)) {			
								$errors = $sale->errors();
							} else {
								$stock->total_stocks = $stock->total_stocks - array_get($input, 'quantity');
								$stock->save();

                                if (array_get($input, 'customer_id') == 0) {
                                    $customer = new \Customer;
                                } else {
                                    $customer = \Customer::findOrFail(array_get($input, 'customer_id'));
                                }

                                $input['total_credits'] = $customer->total_credits + array_get($input, 'total_amount');

                                $customer->doSave($customer, $input);
                                $input['customer_id'] = $customer->customer_id;



								$credit = new \Credit;
								$input['sale_id'] = $sale->sale_id;
								$credit->doSave($credit, $input);

							}
						} else {
							if (strpos($oldMeasure, 'sack') !== false) $input['uom'] = $oldMeasure;
							$errors = [\Product::find($product)->name.' '.\Lang::get('agrivet.errors.insufficient_stocks', ['stocks' => $stock->total_stocks .' '.$uom])];
						}

					} else {
						if (strpos($oldMeasure,'sack') !== false) $input['uom'] = $oldMeasure;
						$errors = [\Product::find($product)->name.' '.\Lang::get('agrivet.errors.out_of_stocks')];

					}
				});
				


				if (count($errors) == 0) {
					\Session::forget("creditsReview.$key");
				} else {
					$errors[] = $errors;
				}

			}
				

			if (count($errors) == 0) {
				return \Redirect::route('admin_credits.create')->with('success', \Lang::get('agrivet.created'));
			} else {

				return \Redirect::route('admin_credits.create')->withErrors($errors);
			}


		} catch (\Exception $e) {
			return \Redirect::route('admin_credits.create')->withErrors((array)$e->getMessage());
		}
	}


    public function payables() {
        $input = \Input::all();


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


        return \View::make('admin.credit.payable')
            ->with('branches', array_add(\Branch::lists('address', 'id'), '', 'Branch'))
            ->with('brands', array_add(\Brand::all()->lists('name', 'brand_id'), '', 'Select Brand'))
            ->with('suppliers', array_add(\Supplier::hasPayables()->lists('supplier_name', 'supplier_id'), '', 'Select Supplier'))
            ->with('expenses', $expenses)
            ->with('appends', $appends)
            ->with('totalRows', $totalRows);
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
                $supplier = \Supplier::findOrFail($supplierId);

                $supplier->total_payables = $supplier->total_payables - array_get($input, 'amount');
                if (! $supplier->save()) {
                    return \Response::json(['errors' => $supplier->errors()]);
                } else {
                    return \Response::json(['success' => 'Successfully saved.']);
                }
            }

        } catch(\Exception $e) {
            return \Response::json(['error' =>  $e->getMessage()]);
        }

    }



    public function partialPayment() {

        $input = \Input::all();

        try {

            $rules = [
                'amount' => 'required|numeric|min:1',
                'customer' => 'required|exists:customers,customer_id'
            ];


            $validator = \Validator::make($input, $rules);

            if ($validator->fails()) {
                return \Response::json(['errors' => $validator->errors()]);
            } else {

                $cusId = array_get($input, 'customer');
                $customer = \Customer::find($cusId);

                $input['branch_id'] = $customer->branch_id;
                $input['sale_type'] = 'SALE';
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

                \DB::transaction(function() use (&$input, &$customer, &$errors) {
                    $sale = new \Sale;

                    if (!$sale->doSave($sale, $input)) {
                        $errors[] = $sale->errors();
                    } else {
                        $customer->total_credits = $customer->total_credits - array_get($input, 'amount');
                        $customer->save();
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
