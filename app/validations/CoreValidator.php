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


    public function validateWholeNumber($attribute, $value, $parameters) {

    	$number = array_get(Input::all(), $parameters[0]);


		if (!\UnitOfMeasure::byName($value)->first()->is_decimal) {
			if (is_numeric( $number ) && floor( $number ) != $number) {
				return false;
			}
			return true;
		}
		return true;
    }
}