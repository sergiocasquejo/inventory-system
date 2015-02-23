<?php

class Sale extends Eloquent {
	protected $table = 'sales';
	protected $primaryKey = 'sale_id';


	public function branch() {
		return $this->belongsTo('Branch', 'branch_id');
	}


	public function product() {
		return $this->belongsTo('Product', 'product_id');
	}
}