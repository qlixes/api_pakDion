<?php

class Config
{
	function items($key)
	{
		require __DIR__ . '/../conf/config.php';
		
		return $config[$key];
	}
}
