<?php

class Product extends Eloquent {
	protected $table = 'products';
	protected $primaryKey = 'id';


	public function sales() {
		return $this->hasMany('Sale');
	}

}