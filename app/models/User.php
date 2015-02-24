<?php

use Zizaco\Confide\ConfideUser;
use Zizaco\Confide\ConfideUserInterface;

class User extends Eloquent implements ConfideUserInterface {

	 use ConfideUser;

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

}
