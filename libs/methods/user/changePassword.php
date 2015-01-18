<?php

namespace user;

class changePassword extends \Method {

    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array(3);
    
    function run() {
        $post = $this->main->request->getPost();
        $old_password = $this->utilities->getValueFromArray($post, 'old_password');
        $new_password = $this->utilities->getValueFromArray($post, 'new_password');
        $password = $this->utilities->getUserProperty('password');
        if(md5($old_password) != $password){
            return $this->setError(1, "Old Password is wrong!");
        } else if(!$new_password){
            return $this->setError(2, "No Password!");
        } else if(strlen($new_password) < 8){
            return $this->setError(3, "Password length must be at least 8!");
        }
        $this->dbUtilities->query("UPDATE users SET password = '".  md5($new_password). "' WHERE user_id = ".$this->getUserId());
        return array('user_id'=>$this->getUserId());
    }

}

?>