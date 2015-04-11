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
    	'quantity'	=> 'required|numeric|min:0.25',
    	'total_amount'	=> 'required|numeric|min:1',
    	'uom'	           => 'required',
    	'encoded_by' 	   => 'required|exists:users,id',
    ];

    /**=================================================
     * QUERY RELATIONSHIPS
     *==================================================*/
	public function branch() {
		return $this->belongsTo('Branch', 'branch_id');
	}


	public function credit() {
        return $this->hasOne('Credit');
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


	public function scopeFilterBranch($query) {
		if (!\Confide::user()->isAdmin()) {
			$query->where('branch_id', \Confide::user()->branch_id);
		}	
		return $query;
	}

	public function scopeOwned($query) {
        if (!\Confide::user()->isAdmin()) {
            $query->where('encoded_by', \Confide::user()->id);
        }   
        return $query;
    }

    public  function scopeSale($query) {
        $query->where('sale_type', '=', 'SALE');
        return $query;
    }

    public  function scopeIsSale($query) {
        $query->where('sale_type', '=', 'SALE');
        return $query;
    }

    public function scopeIsCredit($query) {
        $query->where('sale_type', '=', 'CREDIT');
    }

    public function scopeIsCashOut($query) {
        $query->where('is_cash_out', '=', 1);
    }

    public function scopeByYear($query, $year = null) {
        if ($year == null) {
            return $query->whereRaw('YEAR(CURDATE()) = YEAR(date_of_sale)');
        } else {
            return $query->whereRaw($year.' = YEAR(date_of_sale)');
        }
    }
    

	public function scopeFilter($query, $input) {

		$branch = array_get($input, 'branch');
		//$status = array_get($input, 'status');
		$product = array_get($input, 'product');
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
//		if ($status != '') {
//			$query->whereRaw('status = '. (int)$status);
//		}


		return $query;
	}

	/**
     * Simply saves the given instance
     *
     * @param  Sale $instance
     * @return Object $instance
     */


	public function doSave(Sale $instance, $input) {

        if (array_get($input, 'branch_id') != '') {
            $instance->branch_id = array_get($input, 'branch_id');
        }

        if (array_get($input, 'product_id') != '') {
            $instance->product_id = array_get($input, 'product_id');
        }
        if (array_get($input, 'supplier_price') != '') {
            $instance->supplier_price = array_get($input, 'supplier_price');
        }
        if (array_get($input, 'selling_price') != '') {
            $instance->selling_price = array_get($input, 'selling_price');
        }
        if (array_get($input, 'quantity')!= '') {
            $instance->quantity = array_get($input, 'quantity');
        }
        if (array_get($input, 'uom')!= '') {
            $instance->uom = array_get($input, 'uom');
        }

        if (array_get($input, 'total_amount')!= '') {
            $instance->total_amount = array_get($input, 'total_amount');
        }

        if (array_get($input, 'encoded_by') != '') {
            $instance->encoded_by = array_get($input, 'encoded_by');
        }


        if (array_get($input, 'sale_type') != '') {

            $instance->sale_type = array_get($input, 'sale_type', 'SALE');
        }

        if (array_get($input, 'is_cash_out') != '') {

            $instance->is_cash_out = array_get($input, 'is_cash_out', 0);
        }




		$instance->comments = array_get($input, 'comments');
		$instance->date_of_sale = date('Y-m-d', strtotime(array_get($input, 'date_of_sale')));

		//$instance->status = array_get($input, 'status');
		
		$instance->save();
		return $instance;
	}

}