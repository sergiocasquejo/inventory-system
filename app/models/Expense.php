<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Expense extends Eloquent {
	use SoftDeletingTrait;
	protected $table = 'expenses';
	protected $primaryKey = 'expense_id';
    protected $dates = ['deleted_at'];
    public static $rules = [
    	'branch_id'        => 'required|exists:branches,id',
    	'name'	           => 'required:min:5',
    	'total_amount' 	   => 'required|numeric',
    	'quantity'		   => 'required|numeric',
    	//'uom'	           => 'required',
        'date_of_expense'  => 'required|date',
    	'encoded_by' 	   => 'required|exists:users,id',
    	'status'	       => 'required|in:0,1'
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

    public function scopeFilter($query, $input) {

        $branch = array_get($input, 'branch');
        $status = array_get($input, 'status');
        $total = array_get($input, 'total');
        $year = $year = array_get($input, 'year');
        $month = $month = array_get($input, 'month');
        $day = $day = array_get($input, 'day');


        if ($branch != '') {
            $query->whereRaw('branch_id ='. (int) $branch);
        }

        if ($total != '') {
            $query->whereRaw('total_amount = '. (float)$total);
        }
        if ($year != '') {
            $query->whereRaw('YEAR(date_of_expense) = '.(int)$year);
        }
        if ($month != '') {
            $query->whereRaw('MONTH(date_of_expense) = '.(int)$month);
        }
        if ($day != '') {
            $query->whereRaw('DAY(date_of_expense) = '. (int)$day);
        }
        if ($status != '') {
            $query->whereRaw('status = '. (int)$status);
        }


        return $query;
    }



	public function doSave(Expense $instance, $input) {
		$instance->branch_id = array_get($input, 'branch_id');
		$instance->name = array_get($input, 'name');
		$instance->total_amount = array_get($input, 'total_amount');
		$instance->quantity = array_get($input, 'quantity');
		$instance->uom = array_get($input, 'uom');
		$instance->comments = array_get($input, 'comments');
		$instance->status = array_get($input, 'status');
        $instance->encoded_by = array_get($input, 'encoded_by');
        $instance->date_of_expense = array_get($input, 'date_of_expense');
		
		$instance->save();
		return $instance;
	}

}