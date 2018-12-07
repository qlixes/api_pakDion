<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require __DIR__ . '/../helper/Httper.php';
require __DIR__ . '/../modules/Users_model.php';

$param2 = array();
$http = new Httper();
$params = $http->getRequestParams();

$model = new Users_model();

$master = $http->filter_default($params, array(
	'lastlogin_datetime' => $http->formatdatemon('now', true),
	'lastlogin_ipaddress' => $http->get_ip(),
	'lastlogin_useragent' => $http->get_agent(),
	'flag_login' => FLAG_IS_LOGIN,
));

list($flag_user, $data_user) = $http->filter_used($master, array('username', 'password'));
if($flag_user)
{
	$data_user = array_merge($data_user, array(
		'password' => $http->hash_password($data_user['password'])
	));
	// munculkan request field
	list($flag_login, $data_login) = $model->alias(array('username', 'password'))->selectUser($data_user);
	if($data_login)
	{
		// param update
		list($flag_update, $data_update) = $http->filter_used($master, array(
			'lastlogin_datetime', 'lastlogin_ipaddress', 'lastlogin_useragent', 'username', 'flag_login'
		));
		// param location
		list($flag_loc, $data_loc) = $http->filter_used($master, array(
			'latitude', 'longitude'
		));
		if($flag_update && $flag_loc)
		{
			// update lastlogin_*
			$status_update = $model->upLastLogin($data_update);

			// search location
			list($flag_location, $data_location) = $model->selectArea($data_loc);
			if($data_location)
				$location = array(
					'id' => $data_location['id'],
					'name' => $data_location['name'],
					'address' => $data_location['address']
				);
			else
				$location = array();
			$final = array_merge($data_login, array('location' => $location));

			$http->getResponse(($flag_update && $flag_loc), 'label_api_success', $final);
		} else
			$http->getResponse(($flag_update && $flag_loc), 'label_api_missing_parameter');
	} else
		$http->getResponse($data_login, 'label_api_failed_login');
} else
	$http->getResponse($flag_user, 'label_api_missing_login');