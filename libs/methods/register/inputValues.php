<?php

namespace register;

class inputValues extends \Method {

    public function run() {
        $post = $this->main->request->getPost();
        $email = $this->utilities->getValueFromArray($post, 'email');
        $password = $this->utilities->getValueFromArray($post, 'password');
        return $this->checkValues($email, $password);
    }

    private function checkValues($email, $password) {
        if ($email == "") {
            return $this->setError(3, "No Email!");
        } else if ($password == "") {
            return $this->setError(4, "No Password!");
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->setError(5, "Wrong Email!");
        } else if (strlen($password) < 8) {
            return $this->setError(2, "Password length must be at least 8!");
        } else if ($this->checkIfEmailExist($email)) {
            return $this->setError(1, "Email exists");
        } else {
            return $this->doRegister($email, $password);
        }
    }

    private function checkIfEmailExist($email) {
        $email = $this->dbUtilities->escape($email);
        return $this->dbUtilities->getCount("SELECT user_id FROM users WHERE email = $email");
    }

    private function doRegister($email, $password) {
        $emaile = $this->dbUtilities->escape($email);
        $password = md5($password);
        $password = $this->dbUtilities->escape($password);
        $this->dbUtilities->query("INSERT INTO users (email,password) VALUES ($emaile,$password)");
        $user_id = $this->dbUtilities->getLastId();
        if (!$this->checkIfEmailExist($email)) {
            return $this->setError(1, "Errors in the registration!");
        }
        $this->setUserId($user_id);
        $this->verification->register();
        return $this->generateResponse($user_id, $email);
    }
    
    

    private function generateResponse($user_id, $email) {
        return array('user_id' => $user_id, 'email' => $email);
    }

}

?>