<?php

namespace user;

class Utilities {

    var $method = null;

    public function __construct($method) {
        $this->method = $method;
    }

    public function getColumns() {
        $columnsArray = $this->method->dbUtilities->getArray("SELECT name FROM columns");
        $ret = array();
        foreach ($columnsArray as $column) {
            $ret[] = $column['name'];
        }
        return $ret;
    }

    public function relationshipExists($foreign_user_id) {
        return $this->method->dbUtilities->getCount("SELECT r.relationship_id FROM relationships r,groups g WHERE r.group_id = g.group_id AND g.user_id = $foreign_user_id AND r.user_id = " . $this->method->getUserId());
    }
    
    public function userInApplication($foreign_user_id){
        return $this->method->dbUtilities->getCount("SELECT user_in_application_right_id FROM user_in_application_rights WHERE application_id = ".$this->method->getAppId()." AND user_id = $foreign_user_id");
    }

    public function getColumnId($column) {
        $column = $this->method->dbUtilities->escape($column);
        $columnsArray = $this->method->dbUtilities->getArray("SELECT column_id FROM columns WHERE name like $column");
        if (isset($columnsArray[0]['column_id'])) {
            return $columnsArray[0]['column_id'];
        }
    }

    public function getPermissionForColumn($column, $user_id) {
        $column_id = $this->getColumnId($column);
        $permissionArray = $this->method->dbUtilities->getArray("SELECT permission_id FROM user_data_permission WHERE user_id = $user_id AND column_id = $column_id");
        if (!isset($permissionArray[0]['permission_id'])) {
            return 2;
        }
        return intval($permissionArray[0]['permission_id']);
    }

}

?>