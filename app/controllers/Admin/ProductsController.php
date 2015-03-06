<?php namespace Admin;

class ProductsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$input = \Input::all();

		$px = \DB::getTablePrefix();

		$products = \Product::withTrashed()->select(
					"products.id", "products.status", "branches.name as branch_name", "products.name",
					\DB::raw("GROUP_CONCAT('P', selling_price, '/', per_unit) as selling_price")
				)
			/*\DB::raw(
				"CONCAT(TRUNCATE(SUM(IF({$px}product_pricing.per_unit ='sacks', {$px}product_pricing.selling_price, NULL  )), 0), ' ', 'sacks') as sacks"
			),
			\DB::raw(
				"CONCAT(IF({$px}product_pricing.per_unit = 'sacks', SUM({$px}product_pricing.selling_price) - SUM(IF({$px}product_pricing.per_unit ='sacks', {$px}product_pricing.selling_price, NULL  )), SUM({$px}product_pricing.selling_price)  ), ' ', IF ({$px}product_pricing.per_unit = 'sacks', 'kg', {$px}product_pricing.per_unit )) as other")
			)*/
			->filter($input)
			->join('product_pricing', 'products.id', '=', 'product_pricing.product_id')
			->join('branches', 'product_pricing.branch_id', '=', 'branches.id')
			->groupBy('products.id')
			->paginate(intval(array_get($input, 'records_per_page', 10)));



			

		/*$products = \Product::withTrashed()
		->leftJoin('product_pricing', 'products.id', '=', 'product_pricing.product_id')
		->leftJoin('branches', 'branches.id', '=', 'product_pricing.branch_id')
		->select('products.id', 'products.status', \DB::raw('CONCAT(sales_branches.name, "(", sales_branches.address, ")") as branch_name'), 'products.name', '{$px}product_pricing.selling_price', '{$px}product_pricing.per_unit')
		->filter($input)
		->orderBy('product_id', 'desc')
		->paginate(intval(array_get($input, 'records_per_page', 10)));*/

		$totalRows = \Product::withTrashed()->count();

		$appends = ['records_per_page' => \Input::get('records_per_page', 10)];

		$countries = \Config::get('agrivate.countries');
		return \View::make('admin.product.index')
			->with('products', $products)
			->with('categories', array_add(\Category::all()->lists('name', 'category_id'), '', 'Select Category'))
			->with('branches', array_add(\Branch::dropdown(), '', 'Select Branch'))
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
		return \View::make('admin.product.create')
		->with('brands', array_add(\Branch::dropdown(), '', 'Select Branch'))
		->with('categories', array_add(\Category::all()->lists('name', 'category_id'), 0, 'Select Category'))
		->with('measures', \UnitOfMeasure::all()->lists('label', 'name'));
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

		$rules = \Product::$rules;

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$product = new \Product;


				if ($product->doSave($product, $input)) {
					return \Redirect::route('admin_products.edit', $product->id)->with('success', \Lang::get('agrivate.created'));
				}

				return \Redirect::back()->withErrors($product->errors())->withInput();
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

		$product = \Product::find($id);

		$uoms = implode("','", json_decode($product->uom));

		$measures = \UnitOfMeasure::whereRaw("name IN ('$uoms')")->lists('label', 'name');



		return \View::make('admin.product.edit')
		->with('product', $product)
		->with('branches', array_add(\Branch::dropdown(), '', 'Select Branch'))
		->with('brands', array_add(\Brand::all()->lists('name', 'brand_id'), 0, 'Select Brand'))
		->with('categories', array_add(\Category::all()->lists('name', 'category_id'), 0, 'Select Category'))
		->with('dd_measures', array_add($measures, '', 'Select Measure'))
		->with('measures', \UnitOfMeasure::all()->lists('label', 'name'));
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

		$rules = array_except(\Product::$rules, 'encoded_by');

		$rules['name'] = $rules['name'].','.$id.',id';

		$validator = \Validator::make($input, $rules);

		if ($validator->fails()) {
			return \Redirect::back()->withErrors($validator->errors())->withInput();
		} else {
			try {
				$product = \Product::findOrFail($id);
				
				$input['encoded_by'] = $product->encoded_by;
				if ($product->doSave($product, $input)) {
					return \Redirect::route('admin_products.index')->with('success', \Lang::get('agrivate.updated'));
				}

				return \Redirect::back()->withErrors($product->errors())->withInput();
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
		$product = \Product::withTrashed()->where('id', $id)->first();
		$message = \Lang::get('agrivate.trashed');
		if ($product->trashed()) {
            $product->forceDelete();
            $message = \Lang::get('agrivate.deleted');
        } else {
            $product->delete();
        }

        return \Redirect::route('admin_products.index')->with('success', $message);
	}

	/**
	 * Restore deleted resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function restore($id) {
		$product = \Product::withTrashed()->where('id', $id)->first();
		if (!$product->restore()) {
			return \Redirect::back()->withErrors($product->errors());			
		}

		return \Redirect::back()->with('success', \Lang::get('agrivate.restored'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function get($id)
	{	
		$branch_id = \Input::get('branch_id');
		$unit = \Input::get('uom');

		$product = \ProductPricing::whereRaw(\DB::raw('product_id = '.$id.' AND per_unit = "'.$unit.'"'));

		if ($branch_id) {
			$product = $product->where('branch_id', $branch_id);
		}
		$product = $product->first();

		return \Response::json($product);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function uom($id)
	{	
		$branch = \Input::get('branch_id');

		$uoms = \ProductPricing::whereRaw(\DB::raw('product_id = '.$id))
			->join('unit_of_measures', 'product_pricing.per_unit', '=', 'unit_of_measures.name')
			->groupBy('unit_of_measures.uom_id')
			->select('name', 'label');

		if ($branch) {
			$uoms = $uoms->where('branch_id', $branch);
		}
		


		return \Response::json($uoms->get());
	}
}	
