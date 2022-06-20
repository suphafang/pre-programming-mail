<?php
	header("Content-Type: application/json; charset=UTF-8");
	$json_data = json_decode(file_get_contents("php://input"));

	if (!isset($json_data->username) && !isset($json_data->password)) {
		http_response_code(400);
        echo json_encode(["msg" => "Bed request"], JSON_PRETTY_PRINT);
        die();
	}

	if (strtolower($json_data->username) === "admin" && $json_data->password === "password") {
		echo json_encode(true, JSON_PRETTY_PRINT);
		session_start();
		$_SESSION['authorization'] = true;
		session_write_close();
	} else {
		http_response_code(401);
		echo json_encode(false, JSON_PRETTY_PRINT);
	}
?>