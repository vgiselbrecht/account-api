<?php

namespace group;

class removeUser extends \Method {
    
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
            return $this->setError(1, "You have no Permission to remove a user from this Group!");
        } else if (!$this->dbUtilities->getCount("SELECT user_id FROM users WHERE user_id = $foreign_user_id")) {
            return $this->setError(2, "Wrong foreign User Id!");
        } else if (!$this->dbUtilities->getCount("SELECT relationship_id FROM relationships WHERE user_id = $foreign_user_id AND group_id = $group_id")) {
            return $this->setError(3, "User is not in this Group!");
        } else {
            return $this->deleteUserFromGroup($group_id, $foreign_user_id);
        }
    }

    private function deleteUserFromGroup($group_id, $foreign_user_id) {
        $this->dbUtilities->query("DELETE FROM relationships WHERE group_id = $group_id AND user_id = $foreign_user_id");
        return array('user_id' => $this->getUserId());
    }

}

?>