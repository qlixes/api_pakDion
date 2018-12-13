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
	// must from api
	'datetime' => $http->formatdatemon('now', true),
	'ipaddress' => $http->get_ip(),
	'useragent' => $http->get_agent(),
	'note' => '',
	'flag' => FLAG_DEFAULT,
	// possible from apps
	'lastcheckin_datetime' => $http->formatdatemon('now', true),
	'lastcheckin_ipaddress' => $http->get_ip(),
	'lastcheckin_useragent' => $http->get_agent(),
	'flag_login' => FLAG_IS_CHECKIN,
));

list($flag_check, $data_check) = $http->filter_used($master, array('username', 'lastcheckin_datetime','lastcheckin_ipaddress','lastcheckin_useragent', 'lastcheckin_location', 'flag_login'));

list($flag_loc, $data_loc) = $http->filter_used($master, array('latitude','longitude'));

if($data_check &&	 $flag_loc)
{
	list($flag_user, $data_user) = $http->filter_used($master, array('username', 'flag_login'));

	$data_user['flag_login'] = FLAG_IS_LOGIN; // adding manual filter for already login

	list($flag_login, $data_login) = $model->selectUser($data_user);

	// little hack because return from db was string
	$is_login = ((int) $data_login['flag_login'] === FLAG_IS_LOGIN);

	if($is_login) // checkin only once time and should re-login for re-checkin
	{
		list($flag_location, $data_location) = $model->alias(array('id','name','address', 'city'))->selectArea($data_loc);
		if($data_location)
		{
			$data_check['lastcheckin_location'] = $data_location[$http->output('id')]; // setup id location from api

			list($flag_hist, $data_hist) = $http->filter_used($master, array('username','datetime','location','ipaddress','useragent','note','flag'));

			$data_hist['location'] = $data_location[$http->output('id')];

			$final = $model->upLastCheckIn($data_check);

			list($flag_history, $data_history) = $model->addHistory($data_hist); // inject data_checkin

			$http->getResponse($final, 'label_api_success');
		} else
			$http->getResponse($data_location, 'label_api_missing_location');
	} else
		$http->getResponse($is_login, 'label_api_need_login');
} else
	// must has parameter flag_login and lastcheckin_location id
	$http->getResponse($data_check, 'label_api_missing_parameter');
