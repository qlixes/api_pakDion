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
	'lastlogin_datetime'	=>	$http->formatdatemon('now', true),
	'lastlogin_ipaddress'	=>	$http->get_ip(),
	'lastlogin_useragent'	=>	$http->get_agent(),
	'flag_login'			=>	FLAG_IS_LOGIN, //
));

list($flag_login, $data_login) = $http->filter_used($master, array('username','password'));
if($flag_login)
{
	$data_login = array_merge($data_login, array('password' => $http->hash_password($data_login['password'])));
	list($is_login, $get_login) = $model->selectUser($data_login);
	if($is_login)
	{
		list($filter_update, $datafilter_update) = $http->filter_used($master, array('username', 'lastlogin_datetime', 'lastlogin_ipaddress', 'lastlogin_useragent', 'flag_login'));

		list($flag_update) = $model->upLastLogin($datafilter_update);
		if($flag_update)
			$http->getResponse($is_login, 'label_api_success', $get_login);
		else
			$http->getResponse($flag_update, 'label_api_failed_update');
	} else
		$http->getResponse($is_login, 'label_api_failed_login');
} else
	$http->getResponse($flag_login, 'label_api_missing_login');

	unset($master);