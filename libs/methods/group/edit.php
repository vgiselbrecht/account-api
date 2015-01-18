<?php

namespace group;

class edit extends \Method {
    
    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array(3);

    public function run() {
        $post = $this->main->request->getPost();
        $group_name = $this->utilities->getValueFromArray($post, 'group_name');
        $group_id = $this->utilities->getValueFromArray($post, 'group_id');
        $group_id = intval($group_id);
        return $this->checkValues($group_name, $group_id);
    }

    private function checkValues($group_name, $group_id) {
        if (!$group_name) {
            return $this->setError(3, "No Group Name!");
        } else if (!$this->dbUtilities->getCount("SELECT group_id FROM groups WHERE group_id = $group_id AND user_id = " . $this->getUserId())) {
            return $this->setError(2, "You have no Permission to change this Group!");
        } else if ($this->dbUtilities->getCount("SELECT group_id FROM groups WHERE user_id = " . $this->getUserId() . " AND name = " . $this->dbUtilities->escape($group_name)." AND group_id != $group_id")) {
            return $this->setError(1, "Group Name alread exists!");
        } else {
            return $this->updateGroup($group_name, $group_id);
        }
    }

    private function updateGroup($group_name, $group_id) {
        $this->dbUtilities->query("UPDATE groups SET name = " . $this->dbUtilities->escape($group_name) . " WHERE group_id = $group_id");
        return array('user_id' => $this->getUserId(), 'group_id' => $group_id);
    }

}

?>