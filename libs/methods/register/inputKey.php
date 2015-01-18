<?php

namespace register;

class inputKey extends \Method {

    public $need_user_id = true;

    public function run() {
        $key = $this->utilities->getValueFromArray($this->main->request->getPost(), "register_key");
        if (!$key) {
            return $this->setError(1, "No Key!");
        }
        $user_id = $this->getUserId();
        $app_id = $this->getAppId();
        $key = $this->dbUtilities->escape($key);
        $codes = $this->dbUtilities->getArray("SELECT key_id, remember_key FROM codes WHERE user_id = $user_id AND application_id = $app_id AND register_key = $key");
        if (!$codes) {
            return $this->setError(2, "Wrong Key!");
        }else{
            $this->dbUtilities->query("UPDATE codes SET register_key = NULL WHERE key_id = ".$codes[0]['key_id']);
            $this->dbUtilities->query("UPDATE users SET verified = 1 WHERE user_id = ".$user_id);
            $this->utilities->setRights();
            return array("user_id"=>$user_id,"remember_key"=>$codes[0]['remember_key']);
        }
    }

}

?>