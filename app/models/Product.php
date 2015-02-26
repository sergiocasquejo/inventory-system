<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class Product extends Eloquent {
	
	use SoftDeletingTrait;

	protected $table = 'products';
	protected $primaryKey = 'id';
	protected $dates = ['deleted_at'];

	public static $rules = [
    	'name'	           => 'required|min:5|unique:products,name',
    	'description'	   => 'required',
    	'encoded_by' 	   => 'required|exists:users,id',
    	'status'	       => 'required|in:0,1'
    ];

    /**=================================================
     * QUERY RELATIONSHIPS
     *==================================================*/
	public function sales() {
		return $this->hasMany('Sale');
	}

	public function prices() {
    	return $this->hasMany('ProductPricing');
    }
    
    public function user() {
    	return $this->belongsTo('User', 'encoded_by');
    }

    public function stocks() {
    	return $this->hasMany('StockOnHand');
    }

    public function credits() {
        return $this->hasMany('Credit');
    }

    public function brand() {
        return $this->belongsTo('Brand', 'brand_id');
    }

    public function category() {
        return $this->belongsTo('Category', 'category_id');
    }

    /**=================================================
     * SCOPE QUERY
     *==================================================*/

    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    public function scopeInActive($query) {
        return $query->where('status', 0);
    }

    public function scopeSearch($query, $input) {
    	
    	if (isset($input['s'])) {
    		$query->whereRaw('name LIKE "%'. array_get($input, 's', '') .'%"');
    	}

    	return $query;
    }


	public function doSave(Product $instance, $input) {
		$instance->name = array_get($input, 'name');
		$instance->description = array_get($input, 'description');
		$instance->comments = array_get($input, 'comments');
		$instance->status = array_get($input, 'status');
		$instance->encoded_by = array_get($input, 'encoded_by');
        $instance->brand_id = array_get($input, 'brand_id');
        $instance->category_id = array_get($input, 'category_id');
		
		$instance->save();
		return $instance;
	}

}