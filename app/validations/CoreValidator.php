<?php 

class CoreValidator extends Illuminate\Validation\Validator {
	public function validateUniqueStockBranch($attribute, $value, $parameters)
    {
    	echo '<pre />';
    	print_r($attribute);
    	print_r($value);
    	print_r($parameters);
    	die;
    	return false;
        //return $value == 'foo';
    }
}