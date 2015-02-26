<?php

class ProductPricing extends Eloquent {
	protected $table = 'product_pricing';
	protected $primaryKey = 'price_id';
    public $timestamps = false;
	public static $rules = [
		'branch_id'		=> 'required|exists:branches,id',
    	'product_id' => 'required|exists:products,id',
    	'price'	=> 'required|numeric',
    	'per_unit'	=> 'required',
    ];

    /**================================================
     * QUERY RELATIONSHIPS
     *==================================================*/

    public function product() {
    	return $this->belongsTo('Product', 'product_id');
    }

    public function branch() {
        return $this->belongsTo('Branch', 'branch_id');
    }



    public function doSave(ProductPricing $instance, $input) {
		$instance->branch_id = array_get($input, 'branch_id');
		$instance->product_id = array_get($input, 'product_id');
		$instance->price = array_get($input, 'price');
		$instance->per_unit = array_get($input, 'per_unit');
		
		$instance->save();
		return $instance;
	}

}