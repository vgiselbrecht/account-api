<?php

namespace user;

class setPermission extends \Method {

    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array(3);

    public function run() {
        $post = $this->main->request->getPost();
        $column = $this->utilities->getValueFromArray($post, 'column');
        $permission_id = $this->utilities->getValueFromArray($post, 'permission_id');
        $permission_id = intval($permission_id);
        return $this->checkValues($column, $permission_id);
    }

    private function checkValues($column, $permission_id) {
        $user_columns = $this->methodUtilities->getColumns();
        if (!in_array($column, $user_columns)) {
            return $this->setError(1, "Colums does not exists!");
        } else if (!$this->dbUtilities->getCount("SELECT permission_id FROM permissions WHERE permission_id = $permission_id")) {
            return $this->setError(2, "Permission does not exists!");
        } else {
            return $this->addPermission($column, $permission_id);
        }
    }

    private function addPermission($column, $permission_id) {
        $column_id = $this->methodUtilities->getColumnId($column);
        if ($this->dbUtilities->getCount("SELECT user_data_permission_id FROM user_data_permission WHERE user_id = " . $this->getUserId() . " AND column_id = $column_id")) {
            $this->dbUtilities->query("UPDATE user_data_permission SET permission_id = $permission_id WHERE user_id = " . $this->getUserId() . " AND column_id = $column_id");
        } else {
            $this->dbUtilities->query("INSERT INTO user_data_permission (column_id,user_id,permission_id) VALUES ($column_id," . $this->getUserId() . ",$permission_id)");
        }
        return array('user_id' => $this->getUserId());
    }

}
