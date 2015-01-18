<?php

include_once 'verification/email.php';

class Verification {
    /* @var $email Email */

    public $email = null;
    /* @var $methode Method */
    public $methode = null;

    public function __construct($methode) {
        $this->email = new Email($this);
        $this->methode = $methode;
    }

    public function register() {
        $key_register = $this->methode->dbUtilities->escape($this->rand_string(10));
        $key_remember = $this->methode->dbUtilities->escape($this->rand_string(50));
        $app_id = $this->methode->getAppId();
        $user_id = $this->methode->getUserId();
        $this->methode->dbUtilities->query("INSERT INTO codes (user_id,application_id,remember_key,register_key) VALUES ($user_id,$app_id,$key_remember,$key_register)");
        $this->email->register($key_register);
    }

    public function resendRegister($key_register) {
        $this->email->register($key_register);
    }

    public function login($rights) {
        $key_login = $this->methode->dbUtilities->escape($this->rand_string(10));
        $key_remember = $this->methode->dbUtilities->escape($this->rand_string(50));
        $app_id = $this->methode->getAppId();
        $user_id = $this->methode->getUserId();
        $this->methode->dbUtilities->query("INSERT INTO codes (user_id,application_id,remember_key,login_key) VALUES ($user_id,$app_id,$key_remember,$key_login)");
        $this->email->login($key_login, $rights);
    }

    public function forgotPassword() {
        $password = $this->rand_string(10);
        $this->methode->dbUtilities->query("UPDATE users SET password = '" . md5($password) . "' WHERE user_id = " . $this->methode->getUserId());
        $this->email->forgotPassword($password);
    }

    function rand_string($lng) {
        mt_srand((double) microtime() * 1000000);
        $charset = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
        $length = strlen($charset) - 1;
        $code = '';
        for ($i = 0; $i < $lng; $i++) {
            $code .= $charset{mt_rand(0, $length)};
        }
        return $code;
    }

}

?>