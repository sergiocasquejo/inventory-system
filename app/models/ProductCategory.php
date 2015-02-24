<?php

class ProductCategory extends Eloquent {
	protected $table = 'products_categories';
	protected $primaryKey = 'category_id';
	public $timestamps = false;

	public static $rules = [
		'name'	=> 'required'
    	'slug' 	=> 'required',
    	'status' => 'required|in:0,1'
    ];
}