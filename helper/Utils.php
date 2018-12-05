<?php

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
}