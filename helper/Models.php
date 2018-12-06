<?php

require __DIR__ . '/Config.php';

class Models extends Config
{
	var $pdo;

	function __construct()
	{
		try {
		    $this->pdo = new PDO('mysql:host=' . $this->items('hostname') .';port=' . $this->items('port') . ';dbname=' . $this->items('database'), $this->items('username'), $this->items('password'));
		    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
		    echo 'ERROR: ' . $e->getMessage();
		}
	}

	function read($sql, $params = array())
	{
		$stmt = $this->pdo->prepare($sql);
		$result = $stmt->execute($params);

		$this->status = ($result);

		$this->result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// $this->status = (!empty($this->result));	
		return $this;
	}


	function edit($sql, $params = array())
	{
		$stmt = $this->pdo->prepare($sql);
		$result = $stmt->execute($params);

		$this->status = ($result);

		return $this;
	}

	function status()
	{
		return $this->status;
	}

	function results()
	{
		return $this->result;
	}

	function insertID()
	{
		return $this->pdo->lastInsertId();
	}

	function filter_used($data = array(), $filter = array())
	{
	    $result = array();
	    
	    foreach($filter as $i => $key)
	        $result[$key] = $data[$key];
	    
	    unset($data);
	    return $result;
	}
}