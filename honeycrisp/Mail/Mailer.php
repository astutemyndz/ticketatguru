<?php 
namespace Honeycrisp\Mail;

use PHPMailer\PHPMailer\PHPMailer;
class Mailer extends PHPMailer {

    public function __construct()
    {
        parent::__construct();
    }
   
    // Instantiation and passing `true` enables exceptions
    
    // Enable verbose debug output
    public function setSMTPDebug($SMTPDebug) {
        $this->SMTPDebug = $SMTPDebug;
        return $this;
    }
     // Set mailer to use SMTP
    public function setMailer($Mailer) {
        $this->Mailer = $Mailer;
        return $this;
    }
    // Specify main and backup SMTP servers
    public function setHost($Host) {
        $this->Host= $Host;
        return $this;
    }
    // Enable SMTP authentication
    public function setSMTPAuth($SMTPAuth) {
        $this->SMTPAuth = $SMTPAuth;
        return $this;
    }
     // SMTP username
    public function setUsername($Username) {
        $this->Username= $Username;
        return $this;
    }
    // SMTP password
    public function setPassword($Password) {
        $this->Password= $Password;
        return $this;
    }
    // Enable TLS encryption, `ssl` also accepted
    public function setSMTPSecure($SMTPSecure) {
        $this->SMTPSecure= $SMTPSecure;
        return $this;
    }
    // TCP port to connect to
    public function setPort($Port) {
        $this->Port= $Port;
        return $this;
    }

    public function setSubject($Subject) {
        $this->Subject = $Subject;
        return $this->Subject;
    }
    public function setBody($Body) {
        $this->Body = $Body;
        return $this->Body;
    }
    public function setAltBody($AltBody) {
        $this->AltBody = $AltBody;
        return $this->AltBody;
    }
    public function getErrorInfo() {
        return $this->ErrorInfo;
    }
}