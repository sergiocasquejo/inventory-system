<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class Product extends Eloquent {
	
	use SoftDeletingTrait;

	protected $table = 'products';
	protected $primaryKey = 'id';
	protected $dates = ['deleted_at'];

	public static $rules = [
    	'name'	           => 'required:min:5',
    	'uom'	           => 'required',
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

    public function stocks() {
    	return $this->hasMany('StockOnHand');
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


	public function doSave(Expense $instance, $input) {
		$instance->name = array_get($input, 'name');
		$instance->description = array_get($input, 'description');
		$instance->comments = array_get($input, 'comments');
		$instance->status = array_get($input, 'status');
		$instance->encoded_by = array_get($input, 'encoded_by');
		
		$instance->save();
		return $instance;
	}

}