<?php

class Utilities {
    /* @var $methode Method */

    protected $methode = null;

    public function __construct($methode) {
        $this->methode = $methode;
    }

    public function getValueFromArray($array, $value) {
        if (isset($array[$value])) {
            return $array[$value];
        }
        return "";
    }

    public function getUserProperty($property, $userId = null) {
        if (!$userId) {
            $userId = $this->methode->getUserId();
        }
        $userArray = $this->methode->dbUtilities->getArray("SELECT $property FROM users WHERE user_id = $userId");
        return $userArray[0][$property];
    }

    public function getApplicationRights() {
        $app_id = $this->methode->getAppId();
        return $this->methode->dbUtilities->getArray("SELECT right_id FROM application_rights WHERE application_id = " . $app_id);
    }

    public function getUserRights() {
        $app_id = $this->methode->getAppId();
        $user_id = $this->methode->getUserId();
        return $this->methode->dbUtilities->getArray("SELECT right_id FROM user_in_application_rights WHERE application_id = $app_id AND user_id = $user_id");
    }

    public function setRights() {
        $app_id = $this->methode->getAppId();
        $user_id = $this->methode->getUserId();
        foreach ($this->getApplicationRights() as $right) {
            if (!$this->methode->dbUtilities->getCount("SELECT user_in_application_right_id FROM user_in_application_rights WHERE application_id = $app_id AND right_id = " . $right['right_id'] . " AND user_id = $user_id")) {
                $this->methode->dbUtilities->query("INSERT INTO user_in_application_rights (application_id,right_id,user_id) VALUES ($app_id," . $right['right_id'] . ",$user_id)");
            }
        }
    }

    public function check_date($date, $format, $sep) {
        $pos1 = strpos($format, 'd');
        $pos2 = strpos($format, 'm');
        $pos3 = strpos($format, 'Y');
        $check = explode($sep, $date);
        return checkdate($check[$pos2], $check[$pos1], $check[$pos3]);
    }

    public function checkIfEmailExist($email) {
        $email = $this->methode->dbUtilities->escape($email);
        return $this->methode->dbUtilities->getCount("SELECT user_id FROM users WHERE email = $email");
    }

}

?>