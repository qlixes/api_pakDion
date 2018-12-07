<?php

require __DIR__ . '/Config.php';

class Models extends Config
{
	var $pdo;
	var $sql;

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

		if($stmt->rowCount() > 1 )
			$this->result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		else
			$this->result = $stmt->fetch(PDO::FETCH_ASSOC);

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

	function alias($field = array())
	{
		require __DIR__ . '/../conf/alias.php';
		$result = '';
		foreach($field as $j => $key)
		{
			$result .= $key . ' as "' . $output[$key] . '"'; // litle hacks for whitespace
			if($j < count($field)-1)
				$result .= ', ';
		}
		$this->sql = $result;
		unset($result);
		return $this;
	}

	function show()
	{
		return $this->sql;
	}
}