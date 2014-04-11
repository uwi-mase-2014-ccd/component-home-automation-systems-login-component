<?php

use GuzzleHttp\Client;

try {
	ini_set("display_errors", 1);
	ini_set("track_errors", 1);
	ini_set("html_errors", 1);
	error_reporting(E_ALL);

	require 'vendor/autoload.php';


	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Parse JSON Request
		$inputJSON = file_get_contents('php://input');
		$data = json_decode( $inputJSON, TRUE ); //convert JSON into array
	
		if ($data !== NULL) {
			$_POST = $data;
		}
	

		if (!isset($_POST["username"]) || !isset($_POST["password"])) {
			$response = array(
				'code' => 400,
				'data' => new stdClass,
				'debug' => array(
					'data' => new stdClass,
					'message' => 'This service requires the following arguments [username, password].'
				)
			);

			die(json_encode($response, JSON_PRETTY_PRINT));
		}
		
		$username = $_POST["username"];
		$password = $_POST["password"];

		$client = new Client();

		// Send request to DB Component
		$res = $client->get('http://usermanagement.somee.com/UserManagementComponent/RestServiceImpl.svc/AuthenticateUser/' . $username . '/' . $password);

		// Check if it succeeded
		if ($res->getStatusCode() == 200) {
			$body = $res->getBody();
			$body = json_decode($body, TRUE);
		
			if ($body === NULL || !isset($body['AuthenticateUserResult'])) {
				$response = array(
					'code' => 500,
					'data' => new stdClass,
					'debug' => array(
						'data' => new stdClass,
						'message' => 'The response from the user management component is invalid.'
					)
				);

				if (isset($_GET['_debug'])) {
					var_dump($res);
				}
			
				die(json_encode($response, JSON_PRETTY_PRINT));
			}
			
			// Check returned UN and PW if its the same
			if ($body['AuthenticateUserResult']) {
				// If it is then return success
				$response = array(
					'code' => 200,
					'data' => [
						'success' => true
					],
					'debug' => new stdClass
				);

				echo json_encode($response, JSON_PRETTY_PRINT);
			} else {
				// If credentials are wrong then return failure with code 403
				$response = array(
					'code' => 403,
					'data' => [
						'success' => false
					],
					'debug' => new stdClass
				);

				echo json_encode($response, JSON_PRETTY_PRINT);
			}
		} else { 
			if (isset($_GET['_debug'])) {
				var_dump($res);
			}
		
			$response = array(
				'code' => 500,
				'data' => new stdClass,
				'debug' => array(
					'data' => $res,
					'message' => 'The request to the user management component failed.'
				)
			);

			echo json_encode($response, JSON_PRETTY_PRINT);
		}

		/*
		// Stub until the DB code is up
		$USER_DB = [
			'aston' => 'password',
			'renee' => 'sherm'
		];

		if (isset($USER_DB[$username]) &&
			$USER_DB[$username] === $password
		) {
			// If it is then return success
			$response = array(
				'code' => 200,
				'data' => [
					'success' => true
				],
				'debug' => []
			);

			echo json_encode($response, JSON_PRETTY_PRINT);
		} else {
			// If credentials are wrong then return failure with code 403
			$response = array(
				'code' => 403,
				'data' => [
					'success' => false
				],
				'debug' => []
			);

			echo json_encode($response, JSON_PRETTY_PRINT);
		}
		*/
	} else {
		$response = array(
			'code' => 400,
			'data' => new stdClass,
			'debug' => array(
				'data' => new stdClass,
				'message' => 'This service only accepts a POST Request.'
			)
		);

		echo json_encode($response, JSON_PRETTY_PRINT);
	}
} catch (Exception $e) {
	if (isset($_GET['_debug'])) {
		var_dump($res);
	}

	$response = array(
		'code' => 500,
		'data' => new stdClass,
		'debug' => array(
			'data' => array(
				'Caught exception: ' => $e->getMessage(),
			),
			'message' => 'An exception has occured.'
		)
	);
}