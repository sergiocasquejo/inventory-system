<?php

class Helper {

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
	public function timeElapsedString($ptime)
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

	public function nf($price, $dp = 2, $ps = '.', $ts = ', ', $echo = true) {
		$formatted =  Config::get('agrivate.default_currency').number_format($price, $dp, $ps, $ts);

		if (!$echo) {
			return $formatted;		
		}

		echo $formatted;
	  
	}
}