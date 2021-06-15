<?php
    header("Content-Type: application/json; charset=UTF-8");
    
    session_start();
    if(!isset($_SESSION['authorization']) || !$_SESSION['authorization']) {
        http_response_code(401);
        echo json_encode(["msg" => "unauthorized"], JSON_PRETTY_PRINT);
        die();
    }
?>
<?php
	require('config.php');
	require('encrypt_function.php');

	class listClass {
		private $conn = null;

		public function __construct() {
			$this->conn = conn::mysqli();
		}

		public function get_list() {
			$stmt = $this->conn->prepare('SELECT * FROM list LEFT JOIN email_log ON list.list_id = email_log.email_log_recipient_id ORDER BY list.list_id ASC');
			$stmt->execute();

			$result = $stmt->get_result();

			$json_data = [];

			while ($data = $result->fetch_object()) {

				$prepare = [
					'ID' => $data->list_id, 
					'FIRSTNAME' => $data->list_firstname, 
					'LASTNAME' => $data->list_lastname, 
					'EMAIL' => $data->list_email,
					'HASH' => encrypt($data->list_id)
				];

				if ($data->email_log_id !== null) {
					$prepare['SEND'] = true;
				} else {
					$prepare['SEND'] = false;
				}

				$json_data[] = $prepare;
			}

			echo json_encode($json_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
		}
	}

	$list = new listClass();

	$list->get_list();
?>