<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class Product extends Eloquent {
	
	use SoftDeletingTrait;

	protected $table = 'products';
	protected $primaryKey = 'id';
	protected $dates = ['deleted_at'];

	public static $rules = [
    	'name'	           => 'required|min:5|unique:products,name',
    	// 'description'	   => 'required',
    	'encoded_by' 	   => 'required|exists:users,id',
        'uom'               => 'required|exists:unit_of_measures,name',
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

    public function expenses() {
        return $this->hasMany('Expense');
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

    public function scopeFilter($query, $input) {
        
        $px = \DB::getTablePrefix();

        if (isset($input['s'])) {
            $s = array_get($input, 's', '');
            
            $query->whereRaw("{$px}products.name LIKE  '%".$s."%'");
        }


        if (isset($input['branch']) && $input['branch'] != '') {
            $branch = array_get($input, 'branch');
            $query->whereRaw("{$px}product_pricing.branch_id = $branch");   
        }

        if (isset($input['category']) && $input['category'] != '') {
            $category = array_get($input, 'category');
            $query->whereRaw("{$px}products.category_id = $category");   
        }

        return $query;
    }



    public function scopeSearch($query, $input) {
        
        if (isset($input['s'])) {
            $s = array_get($input, 's', '');
            $query->whereRaw('name LIKE  "%'.$s.'%"');
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
        $instance->uom = json_encode(array_get($input, 'uom'));
        
		
		$instance->save();
		return $instance;
	}

}