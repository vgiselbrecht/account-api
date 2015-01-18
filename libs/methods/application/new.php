<?php

namespace application;

class new_ extends \Method {

    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array(4);

    public function run() {
        $post = $this->main->request->getPost();
        $app_name = $this->utilities->getValueFromArray($post, 'app_name');
        $rights = $this->utilities->getValueFromArray($post, 'rights');
        $rights = explode(",", $rights);
        return $this->checkValues($app_name, $rights);
    }

    private function checkValues($app_name, $rights) {
        if (!$app_name) {
            return $this->setError(1, "No Application Name!");
        } else if ($this->dbUtilities->getCount("SELECT application_id FROM applications WHERE name like " . $this->dbUtilities->escape($app_name))) {
            return $this->setError(3, "Application name already exists!");
        } else {
            if ($rights[0] != "") {
                foreach ($rights as $right) {
                    $right = intval($right);
                    if (!$this->dbUtilities->getCount("SELECT right_id FROM rights WHERE right_id = " . $right)) {
                        return $this->setError(2, "Right does not exists!");
                    }
                }
            }
            return $this->setApplication($app_name, $rights);
        }
    }

    private function setApplication($app_name, $rights) {
        $this->dbUtilities->query("INSERT INTO applications (name,admin_id) VALUES (" . $this->dbUtilities->escape($app_name) . "," . $this->getUserId() . ")");
        $new_app_id = $this->dbUtilities->getLastId();
        foreach ($rights as $right) {
            $right = intval($right);
            $this->dbUtilities->query("INSERT INTO application_rights (application_id,right_id) VALUES ($new_app_id,$right)");
        }
        return array('user_id' => $this->getUserId(),'app_id'=>$new_app_id);
    }

}

?>