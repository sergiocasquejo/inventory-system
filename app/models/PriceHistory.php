<?php

class PriceHistory extends Eloquent {
	protected $table = 'product_price_history';
	protected $primaryKey = 'price_history_id';
    public $timestamps = false;
	public static $rules = [
		'branch_id'		=> 'required|exists:branches,id',
    	'product_id' => 'required|exists:products,id',
        'supplier_price' => 'required|numeric',
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

    public function credit() {
        return $this->hasMany('Credit');
    }



    public function doSave(PriceHistory $instance, $input) {
		$instance->branch_id = array_get($input, 'branch_id');
		$instance->product_id = array_get($input, 'product_id');
        $instance->supplier_id = array_get($input, 'supplier_id');
		$instance->selling_price = array_get($input, 'price');
        $instance->supplier_price = array_get($input, 'supplier_price');
		$instance->per_unit = array_get($input, 'per_unit');
		
		$instance->save();
		return $instance;
	}

}