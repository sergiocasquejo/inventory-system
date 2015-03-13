<?php

class Helper {

	private static $str = '';

	/**
	 * Format date
	 *@param $date - The date, $format - The expected date format
	 *@return $formatted_date - formatted date
	 */
	public static function fd($date, $format = 'F j, Y', $echo = true) {
		$formatted = date($format, strtotime($date));

		if (!$echo) {
			return $formatted;
		}

		echo $formatted;
	}



	/**
	 * Get time elapsed of the given time
	 *
	 * @param INT $ptime - the time to evaluate
	 * @return STRING - elapsed time formatted to string
	 */
	public static function timeElapsedString($ptime)
	{
	    $etime = time() - $ptime;

	    if ($etime < 1)
	    {
	        return '0 seconds';
	    }

	    $a = array( 365 * 24 * 60 * 60  =>  'year',
	                 30 * 24 * 60 * 60  =>  'month',
	                      24 * 60 * 60  =>  'day',
	                           60 * 60  =>  'hour',
	                                60  =>  'minute',
	                                 1  =>  'second'
	                );
	    $a_plural = array( 'year'   => 'years',
	                       'month'  => 'months',
	                       'day'    => 'days',
	                       'hour'   => 'hours',
	                       'minute' => 'minutes',
	                       'second' => 'seconds'
	                );

	    foreach ($a as $secs => $str)
	    {
	        $d = $etime / $secs;
	        if ($d >= 1)
	        {
	            $r = round($d);
	            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
	        }
	    }
	}

	/**
	 * Format price
	 *
	 * @param FLOAT $price - Price
	 * STRING $dp - decimal separator
	 * STRING $ts - Thousand separator
	 * @return STRING  - Formatted price
	 */

	public static function nf($price, $dp = 2, $ps = '.', $ts = ', ', $echo = true) {
		$formatted =  Config::get('agrivate.default_currency').number_format($price, $dp, $ps, $ts);

		if (!$echo) {
			return $formatted;		
		}

		echo $formatted;
	  
	}


	/**
	 * Bread crumbs
	 *
	 * @param FLOAT $price - Price
	 * STRING $dp - decimal separator
	 * STRING $ts - Thousand separator
	 * @return STRING  - Formatted price
	 */
	public static function breadCrumbs() {
		$current = \Request::path();
	    $segments = explode ('/', $current);

	    $arr = [];

	    $url = '';
	    foreach ($segments as $key => $value) {

	    	$url .= $value. '/';

	    	if (is_numeric($value)) continue;

	    	switch($value) {
	    		case 'admin':
	    			$arr[] = sprintf('<li><a href="%s"><i class="icon-home"></i> %s</a></li>', URL::to($url), 'Home');
	    			break;
	    		default:
	    			$arr[] = sprintf('<li><a href="%s"> %s</a></li>', URL::to($url), \Str::title($value));

	    	}
	    }
	    

	    foreach ($arr as $segment) {
	    	echo $segment;
	    }
	}

	/**
	 * DONT REPEAT YOUR SELF
	 *
	 * @param FLOAT $price - Price
	 * STRING $dp - decimal separator
	 * STRING $ts - Thousand separator
	 * @return STRING  - Formatted price
	 */

	public static function drus($name) {
		if ($name == static::$str) {
			return '....';
		} else {
			static::$str = $name;
			return static::$str;
		}
	}

	public static function is_decimal( $val )
	{
	    return is_numeric( $val ) && floor( $val ) != $val;
	}
}