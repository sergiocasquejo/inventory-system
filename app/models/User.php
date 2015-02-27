<?php

use Zizaco\Confide\ConfideUser;
use Zizaco\Confide\ConfideUserInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;



class User extends Eloquent implements ConfideUserInterface {

	 use ConfideUser;
	 use SoftDeletingTrait;

	public static $rules = [
		'username' => 'required|min:5',
		'email' => 'required|email|unique:users,email',
		'password' => 'required_if:password,NULL',
		'confirm_password' => 'same:password',
		'first_name' => 'required',
		'last_name' => 'required',
		'is_admin' => 'required|in:1,0',
		'confirmed' => 'required|in:1,0',
		'status' => 'required|in:1,0',
	];
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');


	public function expenses() {
		return $this->hasMany('Expense');
	}

	public function branch() {
		return $this->belongsTo('Branch', 'branch_id');
	}

	public function productPricing() {
        return $this->hasMany('ProductPricing');
    }

    public function products() {
        return $this->hasMany('Product');
    }

    public function scopeSearch($query, $input) {
    	
    	if (isset($input['s'])) {
    		$query->whereRaw('username LIKE "%'. array_get($input, 's', '') .'%" 
    			OR first_name LIKE "%'. array_get($input, 's', '') .'%"
    			OR last_name LIKE "%'. array_get($input, 's', '') .'%"
    			OR display_name LIKE "%'. array_get($input, 's', '') .'%"');
    	}

    	return $query;
    }



    public function doSave(User $instance, $input) {


    	$instance->username = array_get($input, 'username');
    	$instance->email = array_get($input, 'email');
    	
    	$instance->password = array_get($input, 'password');

        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $instance->password_confirmation = array_get($input, 'confirm_password');

    	// if (array_get($input, 'password_confirmed') != '') {
    	// 	$instance->password = \Hash::make(array_get($input, 'password_confirmed'));
    	// }

    	$instance->display_name = array_get($input, 'display_name');
    	$instance->first_name = array_get($input, 'first_name');
    	$instance->last_name = array_get($input, 'last_name');
    	$instance->is_admin = array_get($input, 'is_admin');
    	$instance->confirmed = array_get($input, 'confirmed');
    	$instance->status = array_get($input, 'status');
    	$instance->branch_id = array_get($input, 'branch_id');

    	$instance->save();

    	return $instance;
    }

}

