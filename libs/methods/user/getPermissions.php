<?php

namespace user;

class getPermissions extends \Method {

    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array(3);

    public function run() {
        $post = $this->main->request->getPost();
        $column = $this->utilities->getValueFromArray($post, 'column');
        $column = explode(',', $column);
        $user_columns = $this->methodUtilities->getColumns();
        if ($column[0] != "") {
            foreach ($column as $col) {
                if (!in_array($col, $user_columns)) {
                    return $this->setError(1, "A Column does not exist!");
                }
            }
            $user_columns = $column;
        }
        return $this->getPermissionsForColumns($user_columns);
    }

    private function getPermissionsForColumns($user_columns) {
        $ret = array();
        foreach ($user_columns as $column) {
            $ret[$column] = $this->methodUtilities->getPermissionForColumn($column, $this->getUserId());
        }
        return $ret;
    }

}
