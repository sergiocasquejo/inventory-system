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


    /**=================================================
     * SCOPE QUERY
     *==================================================*/
    public function scopeSearch($query, $input) {
        
        if (isset($input['s'])) {
            $query->whereRaw('customer_name LIKE "%'. array_get($input, 's', '') .'%"');
        }

        return $query;
    }


    public function doSave(Category $instance, $input) {
        $instance->customer_name = array_get($input, 'customer_name');
        $instance->address = array_get($input, 'address');
        $instance->contact_number = array_get($input, 'contact_number');
        $instance->product = array_get($input, 'product');
        $instance->quantity = array_get($input, 'quantity');
        $instance->uom = array_get($input, 'uom');
        $instance->total_amount = array_get($input, 'total_amount');
        $instance->comments = array_get($input, 'comments');
        $instance->date_of_credit = array_get($input, 'date_of_credit');
        
        $instance->save();
        return $instance;
    }
}
