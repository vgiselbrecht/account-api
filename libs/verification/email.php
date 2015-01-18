<?php

class Email{
    
    /* @var $verification Verification */
    public $verification = null;
    
    public function __construct($verification) {
        $this->verification = $verification;
    }
    
    public function register($key){
        $email = $this->verification->methode->utilities->getUserProperty("email");
        $this->sendMail($email,"Email bestätigen",$key);
    }
    
    public function login($key,$rights){
        $email = $this->verification->methode->utilities->getUserProperty("email");
        $rightString = "";
        foreach($rights as $right){
            $rightString .= $right['right_id'];
        }
        $this->sendMail($email,"Login bestätigen","Key:".$key."Rights:".$rightString);
    }
    
    public function forgotPassword($password){
        $email = $this->verification->methode->utilities->getUserProperty("email");
        $this->sendMail($email,"Passwort zurücksetzen",$password);
    }
    
    public function sendMail($email,$betreff,$content){
        mail($email,$betreff,$content,"From: vgiselbrecht@hotmail.com");
    }
    
}

?>