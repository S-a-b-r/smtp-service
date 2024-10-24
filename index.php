<?php
// Get raw incoming POST data
$json = file_get_contents('php://input');

// Parse JSON data
$data = json_decode($json, true);
if(!empty($data)) {
    $lastname = $data["lastname"];
    $name = $data["name"];
    $phone = $data["phone"];
    $email = $data["email"];
    $organization = $data["organization"];
    $message = $data["message"];

}

print_r($data["test"]);

function validEmail(string $email): bool
{
    return true;
}

class Validator {
    private bool $isValid;
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
        $this->isValid = true;
    }

    public function validate(array $validateFunctions )
    {
        foreach ($validateFunctions as $validateFunction) {
            if
            if (!call_user_func($validateFunction, $this->data)){
                $this->isValid = false;
            }
        }

    }

    public static function validateEmail($email): bool {
        return true;
    }

    public function isValid()
    {
        return $this->isValid;
    }
}
?>
