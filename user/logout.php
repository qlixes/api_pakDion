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
	'flag_login'			=>	FLAG_DEFAULT, //
));

list($flag_logout, $data_logout) = $http->filter_used($master, array('username','flag_login'));
list($flag_out) = $model->upLastLogout($data_logout);

if($flag_out)
	$http->getResponse($flag_out, 'label_api_success');