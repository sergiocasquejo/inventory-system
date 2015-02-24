<?php

class StockOnHand extends Eloquent {
	protected $table = 'stocks_on_hand';
	protected $primaryKey = 'stock_on_hand_id';

	public static $rules = [
		'branch_id'		=> 'required|exists:branches,id',
    	'product_id' => 'required|exists:products,id',
    	'total_stocks'	=> 'required|numeric',
    	'uom'	           => 'required',
    ];


    public function product() {
    	return $this->belongsTo('Products', 'product_id');
    }

    public function branch() {
        return $this->belongsTo('Branch', 'branch_id');
    }


    public function doSave(Expense $instance, $input) {
		$instance->branch_id = array_get($input, 'branch_id');
		$instance->product_id = array_get($input, 'product_id');
		$instance->total_stocks = array_get($input, 'total_stocks');
		$instance->uom = array_get($input, 'uom');
		
		$instance->save();
		return $instance;
	}



}