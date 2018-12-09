<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require __DIR__ . '/../helper/Httper.php';
require __DIR__ . '/../modules/Users_model.php';

$param2 = array();
$http = new Httper();
$params = $http->getRequestParams();

$model = new Users_model();

list($flag_log, $data_log) = $http->filter_used($params, array('username'));

//cari user apakah exist
if($data_log)
	{
		$data_log['flag_login'] = FLAG_DEFAULT;
		$status = $model->upLastLogout($data_log);
		$http->getResponse($data_log, 'label_api_success');
	} else
		$http->getResponse($data_log, 'label_api_missing_login');