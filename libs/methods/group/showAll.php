<?php

namespace group;

class showAll extends \Method {
    
    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array(2);

    public function run() {
        $groupArray = $this->dbUtilities->getArray("SELECT group_id,name FROM groups WHERE user_id = " . $this->getUserId());
        return array('user_id' => $this->getUserId(), 'groups' => $groupArray);
    }

}

?>