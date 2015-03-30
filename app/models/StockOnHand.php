<?php

class StockOnHand extends Eloquent {
	protected $table = 'stocks_on_hand';
	protected $primaryKey = 'stock_on_hand_id';

	public $timestamps = false;
	
	public static $rules = [
		'branch_id'		=> 'required|exists:branches,id',
    	'product_id' => 'required|exists:products,id',
    	'total_stocks'	=> 'required|numeric',
    	'uom'	           => 'required|whole_number:total_stocks',
    ];


    public function product() {
    	return $this->belongsTo('Product', 'product_id');
    }

    public function branch() {
        return $this->belongsTo('Branch', 'branch_id');
    }


    public function scopeFilter($query, $input) {

		$branch = array_get($input, 'branch');
		$keyword = array_get($input, 's');


	 	/* Check if current user is not admin
        * filter only his branch
        */
        if ($branch != '') {
            $query->whereRaw('branch_id ='. (int) $branch);
        }

        if ($keyword != '') {
        	$query->where('products.name', 'LIKE', "%$keyword%");	
        }

        $query->whereNull('branches.deleted_at');

        $query->where('branches.status', '=', 1);
		return $query;
	}


    public function doSave(StockOnHand $instance, $input) {
		$instance->branch_id = array_get($input, 'branch_id');
		$instance->product_id = array_get($input, 'product_id');
		$instance->total_stocks = array_get($input, 'total_stocks');
		$instance->uom = array_get($input, 'uom');
		
		$instance->save();
		return $instance;
	}



}