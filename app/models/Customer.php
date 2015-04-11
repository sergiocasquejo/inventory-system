<?php
/**
 * Created by PhpStorm.
 * User: Serg
 * Date: 3/23/2015
 * Time: 11:16 PM
 */

class Customer extends Eloquent {

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    public static $rules = [
        'customer_name'	=> 'required|unique:customers,customer_name',
        'address'	=> 'required',
        'branch_id' => 'required',
        'total_credits' => 'numeric'
    ];

    public function branch() {
        return $this->belongsTo('Branch', 'branch_id');
    }

    public function credits() {
        return $this->hasMany('Credit');
    }

    public function scopeCashOut($query) {
        return $query->where('is_cash_out', 1);
    }

    public function scopeHasCredits($query) {
        return $query->where('total_credits', '>', 0);
    }

    public function scopeBelongToBranch($query) {

        if (!\Confide::user()->isAdmin()) {
            $query->where('branch_id', '=', \Confide::user()->branch_id);
        }

        return $query;
    }


    public function scopeSearch($query, $input) {

        if (isset($input['s'])) {
            $query->whereRaw('customer_name LIKE "%'. array_get($input, 's', '') .'%"
    			OR address LIKE "%'. array_get($input, 's', '') .'%"');
        }

        return $query;
    }

    public function doSave(Customer $instance, $input) {
        $instance->customer_name = array_get($input, 'customer_name', '');
        $instance->address = array_get($input, 'address', '');
        $instance->contact_no = array_get($input, 'contact_number', '');

        if (array_get($input, 'branch_id') != '') {
            $instance->branch_id = array_get($input, 'branch_id');
        }
        if (array_get($input, 'total_credits') != '') {
            $instance->total_credits = array_get($input, 'total_credits', 0);
        }

        $instance->save();
        return $instance;
    }


}
