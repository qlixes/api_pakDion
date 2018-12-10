<?php

/**
 * @api {post} /user/:id Request User information
 * @apiName GetUser
 * @apiGroup User
 *
 * @apiParam {Number} id Users unique ID.
 *
 * @apiSuccess {String} firstname Firstname of the User.
 * @apiSuccess {String} lastname  Lastname of the User.
 */

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
	'flag_login' => FLAG_DEFAULT,
));

list($flag_user, $data_user) = $http->filter_used($master, array('username', 'password'));

if($flag_user)
{
	$data_user = array_merge($data_user, array(
		'password' => $http->hash_password($data_user['password']),
	));
	// munculkan request field
	list($flag_login, $data_login) = $model->alias(array('username', 'name','position'))->selectUser($data_user);

	// param update with flag_login = 0
	list($flag_update, $data_update) = $http->filter_used($master, array(
		'lastlogin_datetime', 'lastlogin_ipaddress', 'lastlogin_useragent', 'username', 'flag_login'
	));

	$set_default = $model->upLastLogin($data_update); //change flag_login to default

	if($data_login) // if params for login completed
	{
		$data_update['flag_login'] = FLAG_IS_LOGIN;

		// param location
		list($flag_loc, $data_loc) = $http->filter_used($master, array('latitude', 'longitude'));

		// $parameter check initial
		if($flag_update && $flag_loc) // if params needed is completed
		{
			// update lastlogin_*
			$status_update = $model->upLastLogin($data_update);

			// search location
			list($flag_location, $data_location) = $model->alias(array('id','name','address', 'city'))->selectArea($data_loc);	

			if($data_location) // filter location must exist
			{
				$final = array_merge($data_login, array($http->output('location') => $data_location));
				$http->getResponse($data_location, 'label_api_success', $final);
			} else
				$http->getResponse($data_location, 'label_api_missing_location');
		} else
			$http->getResponse(($flag_update && $flag_loc), 'label_api_missing_parameter');
	} else
		$http->getResponse($data_login, 'label_api_failed_login');
} else
	$http->getResponse($flag_user, 'label_api_missing_login');