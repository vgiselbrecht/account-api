<?php

namespace group;

class show extends \Method {

    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array(2);

    public function run() {
        $post = $this->main->request->getPost();
        $group_id = $this->utilities->getValueFromArray($post, 'group_id');
        $group_id = intval($group_id);
        return $this->checkValues($group_id);
    }

    private function checkValues($group_id) {
        if (!$this->dbUtilities->getCount("SELECT group_id FROM groups WHERE group_id = $group_id AND user_id = " . $this->getUserId())) {
            return $this->setError(1, "You have no Permission to show this Group!");
        } else {
            return $this->showGroupInformation($group_id);
        }
    }

    private function showGroupInformation($group_id) {
        $userArray = $this->dbUtilities->getArray("SELECT user_id FROM relationships WHERE group_id = $group_id");
        return array('user_id' => $this->getUserId(), 'group_id' => $group_id, 'foreign_user_ids' => $userArray);
    }

}

?>