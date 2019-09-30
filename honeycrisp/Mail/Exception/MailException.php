<?php 
namespace Honeycrisp\Mail\Exception;

use PHPMailer\PHPMailer\Exception;

class MailException extends Exception {

    public static function getErrorMessage() {
        return self::errorMessage();
    }
}
