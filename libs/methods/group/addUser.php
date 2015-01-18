<?php

namespace group;

class addUser extends \Method {
    
    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array(3);

    public function run() {
        $post = $this->main->request->getPost();
        $group_id = $this->utilities->getValueFromArray($post, 'group_id');
        $foreign_user_id = $this->utilities->getValueFromArray($post, 'foreign_user_id');
        $group_id = intval($group_id);
        $foreign_user_id = intval($foreign_user_id);
        return $this->checkValues($group_id, $foreign_user_id);
    }

    private function checkValues($group_id, $foreign_user_id) {
        if (!$this->dbUtilities->getCount("SELECT group_id FROM groups WHERE group_id = $group_id AND user_id = " . $this->getUserId())) {
            return $this->setError(1, "You have no Permission to add a user to this Group!");
        } else if (!$this->dbUtilities->getCount("SELECT user_id FROM users WHERE user_id = $foreign_user_id")) {
            return $this->setError(2, "Wrong foreign User Id!");
        } else if ($foreign_user_id == $this->getUserId()) {
            return $this->setError(3, "It is not allowed to include yourself to a group!");
        } else if ($this->dbUtilities->getCount("SELECT relationship_id FROM relationships WHERE user_id = $foreign_user_id AND group_id = $group_id")) {
            return $this->setError(4, "User is already in this Group!");
        } else {
            return $this->insertUserToGroup($group_id, $foreign_user_id);
        }
    }

    private function insertUserToGroup($group_id, $foreign_user_id) {
        $this->dbUtilities->query("INSERT INTO relationships (group_id,user_id) VALUES ($group_id,$foreign_user_id)");
        return array('user_id' => $this->getUserId());
    }

}

?>