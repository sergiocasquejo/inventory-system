<?php

class Payable extends Eloquent {
    protected $table = 'payables';
    protected $primaryKey = 'payable_id';
    protected $dates = ['deleted_at'];
    public static $rules = [
        'branch_id'        => 'required|exists:branches,id',
        'name'	           => 'required:min:5',
        'quantity'          => 'required|numeric|min:0.25',
        'total_amount' 	   => 'required|numeric|min:1',
        'uom'	           => 'required|whole_number:quantity',
        'encoded_by' 	   => 'required|exists:users,id',
    ];

    /**=================================================
     * QUERY RELATIONSHIPS
     *==================================================*/

    public function user() {
        return $this->belongsTo('User', 'encoded_by');
    }


    public function branch() {
        return $this->belongsTo('Branch', 'branch_id');
    }

    public function product() {
        return $this->belongsTo('Product', 'name');
    }

    public function stockOnHand() {
        return $this->hasOne('StockOnHand');
    }

    public function brand() {
        return $this->belongsTo('Brand');
    }

    public function supplier() {
        return $this->belongsTo('Supplier', 'supplier_id');
    }

    /**=================================================
     * SCOPE QUERY
     *==================================================*/

    public function scopeSearch($query, $input) {

        if (isset($input['s'])) {
            $query->whereRaw('name LIKE "%'. array_get($input, 's', '') .'%"');
        }

        return $query;
    }


    public function scopeOwned($query) {
        if (!\Confide::user()->isAdmin()) {
            $query->where('encoded_by', \Confide::user()->id);
        }
        return $query;
    }


    public function scopeFilterBranch($query) {
        if (!\Confide::user()->isAdmin()) {
            $query->where('branch_id', \Confide::user()->branch_id);
        }
        return $query;
    }


    public function scopeFilter($query, $input) {

        $branch = array_get($input, 'branch');
        $total = array_get($input, 'total');


        $brand = array_get($input, 'brand');

        $supplier = array_get($input, 'supplier');

        if (!\Confide::user()->isAdmin()) {
            $query->whereRaw('branch_id ='. (int) \Confide::user()->branch_id);
        } elseif ($branch != '') {
            $query->whereRaw('branch_id ='. (int) $branch);
        }

        if ($total != '') {
            $query->whereRaw('total_amount = '. (float)$total);
        }


        if ($brand != '') {
            $query->where('brand_id', $brand);
        }

        if ($supplier != '') {
            $query->where('supplier_id', $supplier);
        }

        return $query;
    }



    public function doSave(Payable $instance, $input) {
        if (array_get($input, 'branch_id') != 0) {
            $instance->branch_id = array_get($input, 'branch_id');
        }


        if (array_get($input, 'brand') != 0) {
            $instance->brand_id = array_get($input, 'brand');
        }

        if (array_get($input, 'stock_on_hand_id') != 0) {
            $instance->stock_on_hand_id = array_get($input, 'stock_on_hand_id');
        }

        if (array_get($input, 'supplier') != 0) {
            $instance->supplier_id = array_get($input, 'supplier');
        }


        if (array_get($input, 'name')) {
            $instance->name = array_get($input, 'name');
        }
        if (array_get($input, 'total_amount')) {
            $instance->total_amount = array_get($input, 'total_amount');
        }
        if (array_get($input, 'quantity')) {
            $instance->quantity = array_get($input, 'quantity');
        }
        if (array_get($input, 'uom')) {
            $instance->uom = array_get($input, 'uom');
        }
        if (array_get($input, 'encoded_by')) {
            $instance->encoded_by = array_get($input, 'encoded_by');
        }


        $instance->comments = array_get($input, 'comments');

        $instance->save();
        return $instance;
    }

}