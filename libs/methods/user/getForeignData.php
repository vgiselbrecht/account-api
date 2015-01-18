<?php

namespace user;

class getForeignData extends \Method {

    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array();

    public function run() {
        $ret = array();
        $post = $this->main->request->getPost();
        $column = $this->utilities->getValueFromArray($post, 'column');
        $foreign_user_id = $this->utilities->getValueFromArray($post, 'foreign_user_id');
        $foreign_user_id = intval($foreign_user_id);
        if (!$this->dbUtilities->getCount("SELECT user_id FROM users WHERE user_id = $foreign_user_id")) {
            return $this->setError(1, "Foreign User id does not exist!");
        }
        $column = explode(',', $column);
        $user_columns = $this->methodUtilities->getColumns();
        if ($column[0] != "") {
            foreach ($column as $col) {
                if (!in_array($col, $user_columns)) {
                    return $this->setError(2, "A Column does not exist!");
                }
            }
            $user_columns = $column;
        }
        $ret = $this->getValueFromDB($user_columns, $foreign_user_id);
        return array('user_id' => $this->getUserId(), "values" => $ret);
    }

    private function getValueFromDB($columns, $foreign_user_id) {
        $ret = array();
        $users = $this->dbUtilities->getArray("SELECT * FROM users WHERE user_id = " . $foreign_user_id);
        foreach ($columns as $column) {
            $permission_id = 4;
            if ($foreign_user_id != $this->getUserId()) {
                $permission_id = $this->methodUtilities->getPermissionForColumn($column, $foreign_user_id);
            }
            $value = $users[0][$column];
            if ($permission_id == 1) {
                $value = null;
            } else if($permission_id == 2 && !$this->methodUtilities->userInApplication($foreign_user_id)){
                $value = null;
            } else if(($permission_id == 3 || $permission_id == 2)  && !$this->methodUtilities->relationshipExists($foreign_user_id)){
                $value = null;
            }
            $ret[$column] = $value;
        }
        return $ret;
    }

}

?>