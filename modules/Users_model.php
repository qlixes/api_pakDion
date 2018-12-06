<?php

require __DIR__ . '/../helper/Models.php';

class Users_model extends Models
{
	function selectUser($params = array())
	{
		$query = 'select * from master_user where username = :username';
		
		if(!empty($params['password']))
			$query .= ' and password = :password';

		$query .= ';'; //end sql

		$sql = $this->read($query, $params);

		return array($sql->status(), $sql->results());
	}

	function upLastCheckIn($params = array())
	{
		// $filter = $this->filter_used($params, array('lastcheckin_datetime','lastcheckin_ipaddress','lastcheckin_useragent', 'lastcheckin_location', 'username'));
		$query = 'update master_user set lastcheckin_datetime = :lastcheckin_datetime, lastcheckin_ipaddress = :lastcheckin_ipaddress, lastcheckin_useragent = :lastcheckin_useragent, lastcheckin_location = :lastcheckin_location where username = :username;'; 

		$sql = $this->edit($query, $params);
		
		return $sql->status();
	}

	function upLastLogin($params = array())
	{
		// $filter = $this->filter_used($params, array('lastlogin_datetime', 'lastlogin_ipaddress', 'lastlogin_useragent','username', 'flag_login'));
		$query = 'update master_user set lastlogin_datetime = :lastlogin_datetime, lastlogin_ipaddress = :lastlogin_ipaddress, lastlogin_useragent = :lastlogin_useragent, flag_login = :flag_login where username = :username;'; 

		$sql = $this->edit($query, $params);
		
		return array($sql->status());
	}

	function upLastLogout($params = array())
	{
		$query = 'update master_user set flag_login = :flag_login where username = :username;'; 

		$sql = $this->edit($query, $params);
		
		return array($sql->status());
	}

	function addHistory($params = array())
	{
		// $filter = $this->filter_used($params, array('username','password','datetime','location','ipaddress','useragent','note','flag'));
		$query = 'insert into  data_checkin(username, datetime, location, ipaddress, useragent, note, flag) values (:username, :password, :datetime, :location, :ipaddress, :useragent, :note, :flag);';

		$sql = $this->edit($query, $params);

		return array($sql->status(), $this->model->insertID());
	}

	function selectArea($params = array())
	{
		// $filter = $this->filter_used($params, array('lattitude','longitude'));
		$query = 'select * from master_location where latitude_min >= :latitude and latitude_max <= :latitude and longitude_min <= :longitude and longitude_max >= :longitude;';

		$sql = $this->read($query, $params);

		return array($sql->status(), $sql->results());
	}
}