<?php

namespace login;

class logout extends \Method {

    public $need_user_id = true;
    public $need_remember_key = true;

    public function run() {
        $remember_key = $this->dbUtilities->escape($this->getRememberKey());
        $this->dbUtilities->query("DELETE FROM codes WHERE user_id = " . $this->getUserId() . " AND application_id = " . $this->getAppId() . " AND remember_key = " . $remember_key);
        return array("user_id" => $this->getUserId());
    }

}

?>