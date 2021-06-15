<?php
	header("Content-Type: application/json; charset=UTF-8");

	$file = fopen('list.csv', 'r');
	$data = [];

	fgetcsv($file);

	while (($get = fgetcsv($file)) !== false) {
		$prepare = [
			'name' => [
				'firstname' => $get[1],
				'lastname' => $get[2]
			],
			'email' => $get[3],
			'comment' => $get[4],
			'ejudge' => [
				'username' => $get[5],
				'password' => $get[6]
			]
		];
		$data[] = $prepare;
	}

	fclose($file);

	require('source/config.php');

	$conn = conn::mysqli();

	$stmt_check = $conn->prepare('SELECT * FROM list WHERE list_ejudge_username = ?');
	$stmt_check->bind_param('s', $username);

	$stmt = $conn->prepare('INSERT INTO list (list_firstname, list_lastname, list_email, list_comment, list_ejudge_username, list_ejudge_password, list_created_at, list_updated_at) VALUES (?,?,?,?,?,?,?,?)');
	$stmt->bind_param('ssssssss', $firstname, $lastname, $email, $comment, $username, $password, $time, $time);

	$res = [];

	foreach ($data as $value) {
		$firstname = $value['name']['firstname'];
		$lastname = $value['name']['lastname'];
		$email = $value['email'];
		$comment = $value['comment'];
		$username = $value['ejudge']['username'];
		$password = $value['ejudge']['password'];
		$time = date('Y-m-d H:i:s');

		$stmt_check->execute();
		$stmt_check_reuslt = $stmt_check->get_result();

		if ($stmt_check_reuslt->num_rows === 0) {
			if ($stmt->execute()) {
				$res[] = [
					'timestamp' => $time, 
					'status' => true, 
					'msg' => "Succeed to insert {$firstname} {$lastname} ({$email}) to database.<br>"
				];
			} else {
				$res[] = [
					'timestamp' => $time, 
					'status' => false, 
					'msg' => "Failed to insert {$firstname} {$lastname} ({$email}) to database.<br>"
				];
			}
		} else {
			$stmt_check_get = $stmt_check_reuslt->fetch_object();
			$res[] = [
				'timestamp' => $time, 
				'status' => false, 
				'msg' => "E-Judge username \"{$username}\" is already exist in the name \"{$stmt_check_get->list_firstname} {$stmt_check_get->list_lastname}\"."
			];
		}
	}

	echo json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>