<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class UnitOfMeasure extends Eloquent {

	use SoftDeletingTrait;

	
	protected $table = 'unit_of_measures';
	protected $primaryKey = 'uom_id';

	public $timestamps = false;
	
	public static $rules = [
		'name'		=> 'required|unique:unit_of_measures,name',
    	'label' => 'required|unique:unit_of_measures,label',
        'total_amount' => 'numeric'
    ];



    /**================================================
     * QUERY RELATIONSHIPS
     *==================================================*/

    public function sales() {
    	return $this->hasMany('Sale');
    }

    public function doSave(UnitOfMeasure $instance, $input) {
		$instance->name = array_get($input, 'name');
		$instance->label = array_get($input, 'label');
		$instance->is_decimal = array_get($input, 'is_decimal');
		$instance->save();
		return $instance;
	}


	 public function scopeSearch($query, $input) {
    	
    	if (isset($input['s'])) {
    		$query->whereRaw('name LIKE "%'. array_get($input, 's', '') .'%" OR label LIKE "%'. array_get($input, 's', '') .'%"');
    	}

    	return $query;
    }

    public function scopeByName($query, $uom_name) {
    	$query->where('name', '=', $uom_name);
    	return $query;
    }


}
