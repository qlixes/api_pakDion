<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require __DIR__ . '/../helper/Httper.php';
require __DIR__ . '/../modules/Users_model.php';

$param2 = array();
$http = new Httper();
$params = $http->getRequestParams();

$model = new Users_model();

if(!empty($params['username']))
{
	list($flag, $data) = $model->selectUser($param);
	if($flag)
	{
		if(empty($param['lastlogin_datetime']))
			$param2['lastlogin_datetime'] = $http->formatdatemon('now',true);
		if(empty($param['lastlogin_ipaddress']))
			$param2['lastlogin_ipaddress'] = $http->get_ip();
		if(empty($param['lastlogin_useragent']))
			$param2['lastlogin_useragent'] = $http->get_agent();
		$param = array_merge($param, $param2);
		
		$model->upLastLogin($param);
	}
}
else
	list($flag, $data) = array(false, array());

return $http->getResponse($flag, $data);