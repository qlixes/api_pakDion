<?php

require __DIR__ . '/../helper/Models.php';

class Users_model
{
	var $model;

	function __construct()
	{
		$this->model = new Models();
	}

	function selectUser($params = array())
	{
		$query = 'select username, name, position  from master_user where username = :username and password = :password;';

		$sql = $this->model->execute($query, $params);

		return array($sql->status(), $sql->results());
	}

	function upLastCheckIn($params = array())
	{
		$query = 'update master_user set lastcheckin_datetime = :lastcheckin_datetime, lastcheckin_ipaddress = :lastcheckin_ipaddress, lastcheckin_useragent = :lastcheckin_useragent, lastcheckin_location = :lastcheckin_location where username = :username;'; 

		$sql = $this->model->edit($query, $params);
		
		return $sql->status();
	}

	function upLastLogin($params = array())
	{
		$query = 'update master_user set lastlogin_datetime = :lastlogin_datetime, lastlogin_ipaddress = :lastlogin_ipaddress, lastlogin_useragent = :lastlogin_useragent where username = :username;'; 

		$sql = $this->model->edit($query, $params);
		
		return array($sql->status());
	}

	function addHistory($params = array())
	{
		$query = 'insert into  data_checkin(username, datetime, location, ipaddress, useragent, note, flag) values (:username, :password, :datetime, :location, :ipaddress, :useragent, :note, :flag);';

		$sql = $this->model->edit($query, $params);

		return array($sql->status(), $this->model->insertID());
	}

	function selectArea($params = array())
	{
		$query = 'select * from master_location where lattitude_min >= :latitude and lattitude_max <= :latitude and longitude_min <= :longitude and longitude_max >= :longitude and username = :username;';

		$sql = $this->model->show($query, $params);

		return array($sql->status(), $sql->results());
	}
}