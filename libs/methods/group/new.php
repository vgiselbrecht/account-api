<?php

namespace group;

class new_ extends \Method {
    
    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array(3);

    public function run() {
        $post = $this->main->request->getPost();
        $group_name = $this->utilities->getValueFromArray($post, 'group_name');
        return $this->checkValues($group_name);
    }

    private function checkValues($group_name) {
        if (!$group_name) {
            return $this->setError(2, "No Group Name!");
        } else if ($this->dbUtilities->getCount("SELECT group_id FROM groups WHERE user_id = " . $this->getUserId() . " AND name = " . $this->dbUtilities->escape($group_name))) {
            return $this->setError(1, "Group Name alread exists!");
        } else {
            return $this->insertGroup($group_name);
        }
    }

    private function insertGroup($group_name) {
        $this->dbUtilities->query("INSERT INTO groups (user_id,name) VALUES (" . $this->getUserId() . "," . $this->dbUtilities->escape($group_name) . ")");
        return array('user_id'=>$this->getUserId(),'group_id'=>$this->dbUtilities->getLastId());
    }

}

?>