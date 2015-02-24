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
    	'uom'	           => 'required',
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