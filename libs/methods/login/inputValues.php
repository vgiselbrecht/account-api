<?php

namespace login;

class inputValues extends \Method {

    public function run() {
        $post = $this->main->request->getPost();
        $email = $this->utilities->getValueFromArray($post, 'email');
        $password = $this->utilities->getValueFromArray($post, 'password');
        return $this->checkValues($email, $password);
    }

    private function checkValues($email, $password) {
        if ($email == "") {
            return $this->setError(1, "No Email!");
        } else if ($password == "") {
            return $this->setError(2, "No Password!");
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->setError(3, "Wrong Email!");
        } else if (!$this->utilities->checkIfEmailExist($email)) {
            return $this->setError(4, "Email does not exists");
        } else {
            return $this->doLogin($email, $password);
        }
    }
    
    private function doLogin($email, $password){
        $password = md5($password);
        $password = $this->dbUtilities->escape($password);
        $email = $this->dbUtilities->escape($email);
        if($user = $this->dbUtilities->getArray("SELECT user_id FROM users WHERE email = $email AND password = $password")){
            $user_id = $user[0]['user_id']; 
            $this->setUserId($user_id);
            if($this->dbUtilities->getCount("SELECT user_id FROM users WHERE user_id = $user_id AND verified = 0")){
                return $this->setError(6, "Account has not been verified!");
            }
        }else{
            return $this->setError(5, "Wrong Password");
        }
        $rights = $this->utilities->getApplicationRights();
        $this->verification->login($rights);
        return $this->generateResponse($user_id, $email, $rights);
    }
    
    private function generateResponse($user_id, $email, $rights) {
        return array('user_id' => $user_id, 'email' => $email, 'rights'=>  $rights);
    }

    

}

?>