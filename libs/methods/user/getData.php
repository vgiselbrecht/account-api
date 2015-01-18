<?php

namespace user;

class getData extends \Method {

    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array(1);

    public function run() {
        $ret = array();
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
        $ret = $this->getValueFromDB($user_columns);
        return array('user_id' => $this->getUserId(), "values" => $ret);
    }

    private function getValueFromDB($columns) {
        $ret = array();
        $users = $this->dbUtilities->getArray("SELECT * FROM users WHERE user_id = " . $this->getUserId());
        foreach ($columns as $column) {
            $ret[$column] = $users[0][$column];
        }
        return $ret;
    }

}

?>