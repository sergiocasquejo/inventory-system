<?php
class Supplier extends Eloquent {

    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';
    public $timestamps = false;

    public static $rules = [
        'supplier_name' => 'required|max:120|unique:suppliers,supplier_name,NULL,supplier_id',
        'location'	=> 'required|exists:branches,id',
        'contact_no' 	=> 'required|max:30',
        'total_payables' => 'numeric',
    ];


    public function payables() {
        return $this->hasMany('Expense');
    }

    public function products() {
        return $this->hasMany('Product');
    }

    public function branch() {
        return $this->belongsTo('Branch', 'location');
    }

    public function scopeHasPayables($query) {
        return $query->where('total_payables', '>', 0);
    }

    public function scopeByBranch($query, $branch) {
        return $query->where('location', $branch);
    }

    public function scopeSearch($query, $input) {

        if (isset($input['s'])) {
            $query->whereRaw('supplier_name LIKE "%'. array_get($input, 's', '') .'%"
                OR location LIKE "%'. array_get($input, 's', '') .'%"');
        }

        return $query;
    }



    public function doSave(Supplier $instance, $input) {
        $instance->supplier_name = array_get($input, 'supplier_name');
        $instance->location = array_get($input, 'location');
        $instance->contact_no = array_get($input, 'contact_no');
        if (array_get($input, 'total_payables') != '') {
            $instance->total_payables = array_get($input, 'total_payables');
        }

        $instance->save();
        return $instance;
    }


}