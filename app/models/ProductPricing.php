<?php

class ProductPricing extends Eloquent {
	protected $table = 'stocks_on_hand';
	protected $primaryKey = 'stock_on_hand_id';

	public static $rules = [
		'branch_id'		=> 'required|exists:branches,id'
    	'product_id' => 'required|exists:products,id',
    	'price'	=> 'required|numeric',
    	'per_unit'	=> 'required',
    	'uom'	           => 'required',
    	'encoded_by' 	   => 'required|exists:users,id',
    	'status'	       => 'required|in:0,1'
    ];


    public function product() {
    	return $this->belongsTo('Product', 'product_id');
    }

    public function branch() {
        return $this->belongsTo('Branch', 'branch_id');
    }


    public function user() {
        return $this->belongsTo('User', 'encoded_by');
    }

    public function doSave(Expense $instance, $input) {
		$instance->branch_id = array_get($input, 'branch_id');
		$instance->product_id = array_get($input, 'product_id');
		$instance->price = array_get($input, 'price');
		$instance->per_unit = array_get($input, 'per_unit');
		$instance->uom = array_get($input, 'uom');
		$instance->status = array_get($input, 'status');
		$instance->encoded_by = array_get($input, 'encoded_by');
		
		$this->save($instance);
		return $instance;
	}

	 /**
     * Simply saves the given instance
     *
     * @param  Expense $instance
     *
     * @return  boolean Success
     */
    public function save(Expense $instance)
    {
        return $instance->save();
    }

}