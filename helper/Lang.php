<?php

function lang($label)
{
	require __DIR__ . '/../conf/lang.php';

	return $lang[$label];
}