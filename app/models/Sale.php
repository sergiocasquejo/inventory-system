<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Sale extends Eloquent {
	
	use SoftDeletingTrait;

	protected $table = 'sales';
	protected $primaryKey = 'sale_id';

	protected $dates = ['deleted_at'];

	public static $rules = [
		'branch_id'		=> 'required|exists:branches,id',
    	'product_id' => 'required|exists:products,id',
    	'quantity'	=> 'required|numeric',
    	'total_amount'	=> 'required|numeric',
    	'uom'	           => 'required',
    	'encoded_by' 	   => 'required|exists:users,id',
    	'status'	       => 'required|in:0,1'
    ];

    /**=================================================
     * QUERY RELATIONSHIPS
     *==================================================*/
	public function branch() {
		return $this->belongsTo('Branch', 'branch_id');
	}


	public function product() {
		return $this->belongsTo('Product', 'product_id');
	}


	public function user() {
		return $this->belongsTo('user', 'encoded_by');
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


	public function scopeFilter($query, $input) {

		$branch = array_get($input, 'branch');
		$status = array_get($input, 'status');
		$product = array_get($input, 'product');
		$total = array_get($input, 'total');
		$year = $year = array_get($input, 'year');
		$month = $month = array_get($input, 'month');
		$day = $day = array_get($input, 'day');


	 	if ($branch != '') {
			$query->whereRaw('branch_id ='. (int) $branch);
		}
		if ($product != '') {
			$query->whereRaw('product_id = '. (int) $product);
		}
		if ($total != '') {
			$query->whereRaw('total_amount = '. (float)$total);
		}
		if ($year != '') {
			$query->whereRaw('YEAR(date_of_sale) = '.(int)$year);
		}
		if ($month != '') {
			$query->whereRaw('MONTH(date_of_sale) = '.(int)$month);
		}
		if ($day != '') {
			$query->whereRaw('DAY(date_of_sale) = '. (int)$day);
		}
		if ($status != '') {
			$query->whereRaw('status = '. (int)$status);
		}


		return $query;
	}

	/**
     * Simply saves the given instance
     *
     * @param  Sale $instance
     * @return Object $instance
     */


	public function doSave(Sale $instance, $input) {
		$instance->branch_id = array_get($input, 'branch_id');
		$instance->product_id = array_get($input, 'product_id');
		$instance->quantity = array_get($input, 'quantity');
		$instance->uom = array_get($input, 'uom');
		$instance->total_amount = array_get($input, 'total_amount');
		$instance->comments = array_get($input, 'comments');
		$instance->date_of_sale = array_get($input, 'date_of_sale');
		$instance->encoded_by = array_get($input, 'encoded_by');
		$instance->status = array_get($input, 'status');
		
		$instance->save();
		return $instance;
	}

}