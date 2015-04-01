<?php

class Brand extends Eloquent {
	protected $table = 'brands';
	protected $primaryKey = 'brand_id';
	public $timestamps = false;

	public static $rules = [
		'name'	=> 'required',
    	// 'description' 	=> 'required',
    ];

     /**=================================================
     * QUERY RELATIONSHIPS
     *==================================================*/
    public function categories() {
        return $this->belongsToMany('Category', 'categories_to_brands', 'brand_id', 'category_id');
    }

    public function products() {
        return $this->hasMany('Products');
    }

    public function payables() {
        return $this->hasMany('Expense');
    }

     /**=================================================
     * SCOPE QUERY
     *==================================================*/

    public function scopeSearch($query, $input) {
        
        if (isset($input['s'])) {
            $query->whereRaw('name LIKE "%'. array_get($input, 's', '') .'%"');
        }

        return $query;
    }




    public function doSave(Brand $instance, $input) {
        $instance->name = array_get($input, 'name');
        $instance->description = array_get($input, 'description');
        
        $instance->save();
        return $instance;
    }
}