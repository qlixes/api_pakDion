<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require __DIR__ . '/../helper/Httper.php';
require __DIR__ . '/../modules/Users_model.php';

$param2 = array();
$http = new Httper();
$params = $http->getRequestParams();

$model = new Users_model();

// preparing default data while null
$master = $http->filter_default($params, array(
	//master_user
	'lastcheckin_datetime'	=>	$http->formatdatemon('now', true),
	'lastcheckin_ipaddress'	=>	$http->get_ip(),
	'lastcheckin_useragent'	=>	$http->get_agent(),
	// datacheckin
	'datetime'				=>	$http->formatdatemon('now', true),
	'ipaddress'				=>	$http->get_ip(),
	'useragent'				=>	$http->get_agent(),
	'flag_login'			=>	FLAG_IS_CHECKIN,
));

//checking area
list($flag_area, $data_area) = $http->filter_used($master, array('latitude','longitude'));
if($flag_area)
{
	//get id_location from known latitude & longitude
	list($flag_location, $data_location) = $model->selectArea($data_area);
	if($flag_location)
	{
		$master = array_merge($master, array(
			'lastcheckin_location' => $data_location['id'], 
			'location' => $data_location['id']
		));
		//checking data update
		list($flag_update, $data_update) = $http->filter_used($master, array('username', 'lastcheckin_datetime', 'lastcheckin_ipaddress', 'lastcheckin_useragent', 'lastcheckin_location'));
		//checking data update
		list($flag_log, $data_log) = $http->filter_used($master, array('username', 'datetime', 'location', 'ipaddress', 'useragent','note', 'flag'));
		if($flag_update && $flag_log) //all need was completed
		{
			//update master_user
			list($update_user) = $model->upLastCheckIn($data_update);

			list($flag_logs, $data_logs) = $model->addHistory($data_log);

			if($update_user && $flag_logs) //update & add history worked
				$http->getResponse(($update_user && $flag_logs), 'label_api_success');
			else
				$http->getResponse(($update_user && $flag_logs), 'label_api_failed_update');
		} else
			$http->getResponse(($flag_update && $flag_log), 'label_api_missing_parameter');
	} else
		$http->getResponse($flag_location, 'label_api_unknown_services');
} else
	$http->getResponse($flag_area, 'label_api_need_location');

unset($master);