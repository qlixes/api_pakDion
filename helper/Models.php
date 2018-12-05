<?php

require __DIR__ . '/Config.php';

class Models extends Config
{
	var $pdo;

	function __construct()
	{
		try {
			// $this->pdo = new PDO('mysql:host=localhost;port=3306;dbname=api','root','');
		    $this->pdo = new PDO('mysql:host=' . $this->items('hostname') .';port=' . $this->items('port') . ';dbname=' . $this->items('database'), $this->items('username'), $this->items('password'));
		    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
		    echo 'ERROR: ' . $e->getMessage();
		}
	}

	function execute($sql, $params = array())
	{
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);

		$this->result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$this->status = (!empty($this->result));
		
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
}