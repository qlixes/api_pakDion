<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require __DIR__ . '/../helper/Httper.php';
require __DIR__ . '/../modules/Users_model.php';

$http = new Httper();
$params = $http->getRequestParams();

$model = new Users_model();


if(!empty($params['username']))
{
}
else
	list($flag, $data) = array(false, array());

return $http->getResponse($flag, $data);