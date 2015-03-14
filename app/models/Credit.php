<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Credit extends Eloquent {
    use SoftDeletingTrait;

	protected $table = 'credits';
	protected $primaryKey = 'credit_id';

	public static $rules = [
		'customer_name'	=> 'required'
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
        return $this->belongsTo('Product', 'product');
    }

    public function sale() {
        return $this->belongsTo('Sale', 'sale_id');
    }

    /**=================================================
     * SCOPE QUERY
     *==================================================*/
    public function scopeSearch($query, $input) {
        
        if (isset($input['s'])) {
            $query->whereRaw('customer_name LIKE "%'. array_get($input, 's', '') .'%"');
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
        $status = array_get($input, 'status');
        $total = array_get($input, 'total');
        $year = $year = array_get($input, 'year');
        $month = $month = array_get($input, 'month');
        $day = $day = array_get($input, 'day');
        /* Check if current user is not admin
        * filter only his branch
        */
        if (!\Confide::user()->isAdmin()) {
           $query->whereRaw('branch_id ='. (int) \Confide::user()->branch_id); 
        } elseif ($branch != '') {
            $query->whereRaw('branch_id ='. (int) $branch);
        }

        if ($total != '') {
            $query->whereRaw('total_amount = '. (float)$total);
        }
        if ($year != '') {
            $query->whereRaw('YEAR(date_of_credit) = '.(int)$year);
        }
        if ($month != '') {
            $query->whereRaw('MONTH(date_of_credit) = '.(int)$month);
        }
        if ($day != '') {
            $query->whereRaw('DAY(date_of_credit) = '. (int)$day);
        }
        if ($status != '') {
            $query->whereRaw('is_paid = '. (int)$status);
        }


        return $query;
    }


    public function doSave(Credit $instance, $input) {
        $instance->sale_id = array_get($input, 'sale_id');
        $instance->customer_name = array_get($input, 'customer_name');
        $instance->address = array_get($input, 'address');
        $instance->contact_number = array_get($input, 'contact_number');
        $instance->is_paid = array_get($input, 'is_paid');
        
        $instance->save();
        return $instance;
    }
}
