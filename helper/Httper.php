<?php

require __DIR__ . '/Utils.php'; 

class Httper extends Utils
{
	function getResponse($flag, $data = array())
	{
		header("Content-Type: application/json; charset=UTF-8");
		if($flag)
			$output = array(
				'status' => 200,
				'data' => $data
			);
		else
			$output = array(
				'status' => 404,
				'message' => 'Not Found'
			);

		echo json_encode($output);
	}

	function getRequestParams()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST' || 'post')
		{
			$json = json_decode(file_get_contents('php://input'), true);

			if(!is_array($json))
				$json = array();

			$data = array_merge($_POST, $json
				//jika ada tambahan secara default
			);
		}
		else
			$data = $_GET;

		return $data;
	}
}