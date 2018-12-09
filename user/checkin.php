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
	'lastcheckin_datetime' => $http->formatdatemon('now', true),
	'lastcheckin_ipaddress' => $http->get_ip(),
	'lastcheckin_useragent' => $http->get_agent(),
	'flag_login' => FLAG_IS_CHECKIN,
));

list($flag_check, $data_check) = $http->filter_used($master, array('username', 'lastcheckin_datetime','lastcheckin_ipaddress','lastcheckin_useragent', 'lastcheckin_location', 'flag_login'));

if($data_check)
{
	list($flag_user, $data_user) = $http->filter_used($master, array('username', 'flag_login'));

	$data_user['flag_login'] = FLAG_IS_LOGIN; // adding manual filter for already login

	list($flag_login, $data_login) = $model->selectUser($data_user);

	// little hack because return from db was string
	$is_login = ((int) $data_login['flag_login'] === FLAG_IS_LOGIN);

	if($is_login) // checkin only once time and should re-login for re-checkin
	{
		$final = $model->upLastCheckIn($data_check);

		$http->getResponse($final, 'label_api_success');
	} else
		$http->getResponse($is_login, 'label_api_missing_login');
} else
	// must has parameter flag_login and lastcheckin_location id
	$http->getResponse($data_check, 'label_api_missing_parameter');