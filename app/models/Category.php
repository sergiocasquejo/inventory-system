<?php

class Category extends Eloquent {
	protected $table = 'categories';
	protected $primaryKey = 'category_id';
	public $timestamps = false;

	public static $rules = [
		'name'	=> 'required'
    ];

     /**=================================================
     * QUERY RELATIONSHIPS
     *==================================================*/

    public function brands() {
    	return $this->belongsToMany('Brand', 'categories_to_brands', 'category_id', 'brand_id');
    }


    public function products() {
        return $this->hasMany('Products');
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

    public function doSave(Category $instance, $input) {
        $instance->name = array_get($input, 'name');
        $instance->description = array_get($input, 'description');
        
        $instance->save();
        return $instance;
    }
}