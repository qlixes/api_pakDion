<?php

define("MIN_YEAR", 1900);

class Utils
{
	function get_ip()
	{
		return $_SERVER['REMOTE_ADDR'];
	}

	function get_agent()
	{
		return $_SERVER['HTTP_USER_AGENT'];
	}

	// Thank you for Mr.Pandu
	function formatdatemon( $strdate = null, $time = false, $dateformat = 'Y-m-d')
	{
	    if(empty($strdate))
	        $strdate = 'now';
	    else if($strdate == 'first')
	        $strdate = 'first day of this month';
	    else if($strdate == 'last')
	        $strdate = 'last day of this month';

	    if($time === true)
	        $dateformat .= " H:i:s";

	    $strdate = date($dateformat, strtotime($strdate));
	    if(date('Y', strtotime($strdate)) < MIN_YEAR)
	        return null;
	    else
	        return $strdate;
	}
}