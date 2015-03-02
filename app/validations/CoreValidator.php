<?php 

class CoreValidator extends Illuminate\Validation\Validator {
	public function validateGreaterThanEqual($attribute, $value, $parameters)
    {


    	if (isset($parameters[0])) {
	       	$other = array_get(Input::all(), $parameters[0]);

	       	return intval($value) >= intval($other);
       	} else {
       		return false;
       	}
    
    }
}