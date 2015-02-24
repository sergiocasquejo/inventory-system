<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class Branch extends Eloquent {
	use SoftDeletingTrait;


	protected $table = 'branches';
	protected $primaryKey = 'id';
	protected $dates = ['deleted_at'];
	
	public static $rules = [
    	'name' 		=> 'required|min:5|unique:expenses',
    	'address'	=> 'required',
    	'city' 		=> 'required',
    	'state'		=> 'required',
    	'post_code'	=> 'required',
    	'country' 	=> 'required',
    	'status'	=> 'required|in:0,1'
    ];



	public function users() {
		return $this->hasMany('User');
	}

	public function expense() {
		return $this->hasMany('Expense');
	}


	public function productPricing() {
        return $this->hasMany('ProductPricing');
    }

    public function sales() {
        return $this->hasMany('Sale');
    }


    public function stockOnHand() {
        return $this->hasMany('StockOnHand');
    }

	public function doSave(Branch $instance, $input) {
		$instance->name = array_get($input, 'name');
		$instance->address = array_get($input, 'address');
		$instance->city = array_get($input, 'city');
		$instance->state = array_get($input, 'state');
		$instance->post_code = array_get($input, 'post_code');
		$instance->country = array_get($input, 'country');
		$instance->status = array_get($input, 'status');
		
		$this->save($instance);
		return $instance;
	}

	 /**
     * Simply saves the given instance
     *
     * @param  Branch $instance
     *
     * @return  boolean Success
     */
    public function save(Branch $instance)
    {
        return $instance->save();
    }

}