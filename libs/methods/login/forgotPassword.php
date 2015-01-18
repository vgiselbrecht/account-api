<?php

namespace login;

class forgotPassword extends \Method {

    function run() {
        $post = $this->main->request->getPost();
        $email = $this->utilities->getValueFromArray($post, 'email');
        if (!$email) {
            return $this->setError(1, "No Email!");
        } else if (!$this->utilities->checkIfEmailExist($email)) {
            return $this->setError(2, "Email does not exists");
        }
        $userArray = $this->dbUtilities->getArray("SELECT user_id FROM users WHERE email = " . $this->dbUtilities->escape($email));
        $this->setUserId($userArray[0]['user_id']);
        $this->verification->forgotPassword();
        return array('user_id'=>$this->getUserId());
    }

}

?>