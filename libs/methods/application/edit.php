<?php

namespace application;

class edit extends \Method {

    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array(4);
    
    public function run() {
        $post = $this->main->request->getPost();
        $change_app_id = $this->utilities->getValueFromArray($post, 'change_app_id');
        $app_name = $this->utilities->getValueFromArray($post, 'app_name');
        $rights = $this->utilities->getValueFromArray($post, 'rights');
        $rights = explode(",", $rights);
        $change_app_id = intval($change_app_id);
        return $this->checkValues($app_name, $rights, $change_app_id);
    }

    private function checkValues($app_name, $rights, $change_app_id) {
        if (!$this->dbUtilities->getCount("SELECT application_id FROM applications WHERE application_id = $change_app_id")) {
            return $this->setError(4, "Application Id does not exists!");
        } else if (!$this->dbUtilities->getCount("SELECT application_id FROM applications WHERE application_id = $change_app_id AND admin_id = " . $this->getUserId())) {
            return $this->setError(3, "You have no Permission to change this Application!");
        } else if ($this->dbUtilities->getCount("SELECT application_id FROM applications WHERE application_id != $change_app_id AND name like " . $this->dbUtilities->escape($app_name))) {
            return $this->setError(2, "Application name already exists!");
        } else {
            if ($rights[0] != "") {
                foreach ($rights as $right) {
                    $right = intval($right);
                    if (!$this->dbUtilities->getCount("SELECT right_id FROM rights WHERE right_id = " . $right)) {
                        return $this->setError(1, "Right does not exists!");
                    }
                }
            }
            return $this->changeApplications($app_name, $rights, $change_app_id);
        }
    }

    private function changeApplications($app_name, $rights, $change_app_id) {
        if ($app_name) {
            $this->dbUtilities->query("UPDATE applications SET name = " . $this->dbUtilities->escape($app_name) . " WHERE application_id = $change_app_id");
        }
        if ($rights[0] != "") {
            $this->dbUtilities->query("DELETE FROM application_rights WHERE application_id = $change_app_id");
            foreach ($rights as $right) {
                $right = intval($right);
                $this->dbUtilities->query("INSERT INTO application_rights (application_id,right_id) VALUES ($change_app_id,$right)");
            }
        }
        return array('user_id' => $this->getUserId(),'app_id'=>$change_app_id);
    }

}

?>