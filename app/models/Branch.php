<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class Branch extends Eloquent {
	use SoftDeletingTrait;


	protected $table = 'branches';
	protected $primaryKey = 'id';
	public $timestamps = false;
    
	public static $rules = [
    	'name' 		=> 'required',
    	'address'	=> 'required|min:5|unique:branches,name,NULL,id',
    	'city' 		=> 'required',
    	'post_code'	=> 'required',
    	'status'	=> 'required|in:0,1'
    ];


    /**=================================================
     * QUERY RELATIONSHIPS
     *==================================================*/
	public function users() {
		return $this->hasMany('User');
	}

	public function expense() {
		return $this->hasMany('Expense');
	}


    public function credit() {
        return $this->hasMany('Credit');
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

    public function scopeNameWithAddress($query) {
        return $query->select(\DB::raw('CONCAT(name," (",address,")") AS name'), 'id' );
    }

    public function scopeFilterBranch($query) {
        /* Check if current user is not admin
        * filter only his branch
        */
        if (!\Confide::user()->isAdmin()) {
           $query->whereRaw('id ='. (int) \Confide::user()->branch_id); 
        } 

        return $query;
    }
    

	public function doSave(Branch $instance, $input) {
		$instance->name = array_get($input, 'name');
		$instance->address = array_get($input, 'address');
		$instance->city = array_get($input, 'city');
		$instance->post_code = array_get($input, 'post_code');
		$instance->status = array_get($input, 'status');
		
		$instance->save();
		return $instance;
	}


}