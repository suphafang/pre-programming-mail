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
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require '../vendor/autoload.php';
    require('config.php');
    require('encrypt_function.php');

    //Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    $json_data = json_decode(file_get_contents("php://input"));

    if (
        empty($json_data->name) ||
        !isset($json_data->question) ||
        !isset($json_data->answer) ||
        empty($json_data->senior) ||
        empty($json_data->username) ||
        empty($json_data->password) ||
        empty($json_data->email) ||
        empty($json_data->hash)
    ) {
        http_response_code(400);
        echo json_encode(["msg" => "Bed request"], JSON_PRETTY_PRINT);
    } else {

        if (decrypt($json_data->hash) === '') {
            http_response_code(400);
            echo json_encode(["msg" => "Hash is invalid"], JSON_PRETTY_PRINT);
            die();
        }

        $conn = conn::mysqli();

        $stmt = $conn->prepare("SELECT * FROM list LEFT JOIN email_log ON list.list_id = email_log.email_log_recipient_id WHERE list.list_id = ? LIMIT 1");
        $stmt->bind_param("s", $id);

        $id = decrypt($json_data->hash);

        $stmt->execute();
        $result = $stmt->get_result();

        $data = $result->fetch_object();

        if ($result->num_rows < 1) {
            http_response_code(400);
            echo json_encode(["msg" => "ID not found"], JSON_PRETTY_PRINT);
            die();
        }

        if ($data->email_log_id !== null) {
            http_response_code(400);
            echo json_encode(["msg" => "This ID is already sent."], JSON_PRETTY_PRINT);
            die();
        }

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.hostinger.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'no-reply@codingtime.dev';                     //SMTP username
            $mail->Password   = '@Pre-programming64';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            $mail->CharSet = 'UTF-8';

            //Recipients
            $mail->setFrom('pre-programming@codingtime.dev', 'Pre-programming');
            $mail->addAddress($json_data->email, $json_data->name);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Welcome to Pre-programming 2021';

            $template = file_get_contents('../templates/mail.html');

            $template = str_replace("{{name}}", $json_data->name, $template);
            $template = str_replace("{{question}}", $json_data->question, $template);
            $template = str_replace("{{answer}}", $json_data->answer, $template);
            $template = str_replace("{{senior}}", $json_data->senior, $template);
            $template = str_replace("{{username}}", $json_data->username, $template);
            $template = str_replace("{{password}}", $json_data->password, $template);

            $mail->msgHTML($template, __DIR__);

            $mail->send();

            echo json_encode(true, JSON_PRETTY_PRINT);

            $stmt = $conn->prepare("INSERT INTO email_log (email_log_sender, email_log_recipient_id, email_log_reply) VALUES (?,?,?)");
            $stmt->bind_param("sis", $json_data->senior, $id, $json_data->answer);

            $stmt->execute();

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['msg' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"], JSON_PRETTY_PRINT);
        }
        
    }
