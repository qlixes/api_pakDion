<?php

require __DIR__ . '/Utils.php'; 

class Httper extends Utils
{
	var $format = array();

	function getResponse($flag, $label, $data = array())
	{
		require __DIR__ . '/Lang.php';

		header("Content-Type: application/json; charset=UTF-8");
		if($flag)
			$output = array(
				'status' => 200,
				'message' => lang($label),
				'data' => $data
			);
		else
			$output = array(
				'status' => 404,
				'message' => lang($label),
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
			);
		}
		else
			$data = $_GET;

		return $data;
	}
}