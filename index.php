<?php

use PHPMailer\PHPMailer\PHPMailer;

require 'PHPMailer-6.9.2/src/Exception.php';
require 'PHPMailer-6.9.2/src/PHPMailer.php';
require 'PHPMailer-6.9.2/src/SMTP.php';

const USERNAME_MAILER = "sabr_projects@mail.ru";
const PASSWORD_MAILER = "";
const MAIL_TO = "po_fasty_grisha@mail.ru";
const SMTP_HOST = "ssl://smtp.mail.ru";
const SMTP_PORT = 465;


if(!empty($_POST)) {
    $email = $_POST["email"];
    $lastname = $_POST["lastname"];
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $organization = $_POST["organization"];
    $message = $_POST["message"];

    $validator = new Validator();
    $errors = $validator->validateForm();

    if (!empty($errors)){
        echo $errors;
        return;
    }

    $message = "Email: $email\n Name: $name\n Phone: $phone\n Organization: $organization\n Message: $message";
    $mailer = new Mailer(USERNAME_MAILER, PASSWORD_MAILER);
    $mailer->sendMail(MAIL_TO, "Заявка с сайта", $message);
    return;
}

class Mailer {
    private $mail;
    function __construct(string $username, string $password) {
        $this->mail = new PHPMailer;

        $this->mail->isSMTP();

        $this->mail->Host = SMTP_HOST;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $username;
        $this->mail->Password = $password;
        $this->mail->SMTPSecure = 'SSL';
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Port = SMTP_PORT;
    }

    public function sendMail(string $toEmail, string $subject, string $message)
    {
        try {
            $this->mail->setFrom('sabr_projects@mail.ru');
            $this->mail->addAddress($toEmail);
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            echo $e->getMessage();
        }

        $this->mail->Subject = $subject;
        $this->mail->Body = $message;

        try {
            if (!$this->mail->send()) {
                echo 'Email sending failed.';
                echo 'Mailer Error: ' . $this->mail->ErrorInfo;
            } else {
                echo 'Success!';
            }
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}




class Validator {
    public function validate($data, string $validateRules ):bool
    {
        $validateRules = explode("|", $validateRules);
        foreach ($validateRules as $validateRule) {
            if (!call_user_func([$this, $validateRule], $data)) {
                return false;
            }
        }
        return true;
    }

    public function validateForm():string
    {
        $emailValid = $this->validate($_POST["email"], "email|required");
        $lastnameValid = $this->validate($_POST["lastname"], "null");
        $nameValid = $this->validate($_POST["name"], "required");
        $phone = $this->validate($_POST["phone"], "required|phone");
        $organization = $this->validate($_POST["organization"], "required");
        $message = $this->validate($_POST["message"], "required");

        $errors = "";
        if (!$emailValid) {
            $errors .= "email error ";
        }
        if (!$lastnameValid) {
            $errors .= "bot error ";
        }
        if (!$nameValid) {
            $errors .= "name not valid ";
        }
        if (!$phone) {
            $errors .= "phone not valid ";
        }
        if (!$organization) {
            $errors .= "organization not valid ";
        }
        if (!$message) {
            $errors .= "message not valid ";
        }

        return $errors;
    }

    public static function required( $data ): bool
    {
        return !empty($data);
    }

    public static function phone( $data ): bool
    {
        return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function null( $data ): bool
    {
        return empty($data);
    }

    public static function email($data): bool {
        return filter_var($data, FILTER_VALIDATE_EMAIL);
    }
}
?>
