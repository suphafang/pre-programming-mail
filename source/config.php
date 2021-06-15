<?php
	date_default_timezone_set("Asia/Bangkok");

	define('HOST_HOSTNAME', 'localhost');
	define('HOST_USER', 'root');
	define('HOST_PASS', '');
	define('HOST_DATABASE', 'pre_programming');

	class conn {
		static public function mysqli() {
			$conn = new mysqli(HOST_HOSTNAME, HOST_USER, HOST_PASS, HOST_DATABASE);
			if ($conn->connect_error) {
				die('Connection failed >>> ' . $conn->connect_error);
			}
			return $conn;
		}
	}
?>
