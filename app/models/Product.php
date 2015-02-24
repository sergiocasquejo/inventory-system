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


	public function sales() {
		return $this->hasMany('Sale');
	}

	public function prices() {
    	return $this->hasMany('ProductPricing');
    }

    public function stocks() {
    	return $this->hasMany('StockOnHand');
    }


	public function doSave(Expense $instance, $input) {
		$instance->name = array_get($input, 'name');
		$instance->description = array_get($input, 'description');
		$instance->comments = array_get($input, 'comments');
		$instance->status = array_get($input, 'status');
		$instance->encoded_by = array_get($input, 'encoded_by');
		
		$this->save($instance);
		return $instance;
	}

	 /**
     * Simply saves the given instance
     *
     * @param  Expense $instance
     *
     * @return  boolean Success
     */
    public function save(Expense $instance)
    {
        return $instance->save();
    }

}