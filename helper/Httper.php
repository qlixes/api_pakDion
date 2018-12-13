<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require HELPERPATH . 'Utils.php'; 

class Httper extends Utils
{
	var $format = array();

	function getResponse($flag, $label, $data = array())
	{
		require __DIR__ . '/Lang.php';

		header("Content-Type: application/json; charset=UTF-8");
		if($flag)
		{
			http_response_code(200);
			$output = array(
				'code' => 200,
				'message' => lang($label),
				'data' => $data
			);
		} else {
			http_response_code(404);
			$output = array(
				'code' => 404,
				'message' => lang($label),
			);
		}
		http_response_code();
		echo json_encode($output, JSON_PRETTY_PRINT);
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
			$final = $this->parser($data);
		}
		else
			$final = $this->parser($_GET);
			// $data = $_GET;

		return $final;
	}
}
