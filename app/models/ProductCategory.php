<?php

class ProductCategory extends Eloquent {
	protected $table = 'products_categories';
	protected $primaryKey = 'category_id';
	public $timestamps = false;

}