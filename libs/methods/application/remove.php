<?php

namespace application;

class remove extends \Method {

    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array(4);

    public function run() {
        $post = $this->main->request->getPost();
        $delete_app_id = $this->utilities->getValueFromArray($post, 'delete_app_id');
        $delete_app_id = intval($delete_app_id);
        return $this->checkValues($delete_app_id);
    }

    private function checkValues($delete_app_id) {
        if (!$this->dbUtilities->getCount("SELECT application_id FROM applications WHERE application_id = $delete_app_id")) {
            return $this->setError(2, "Application Id does not exists!");
        } else if (!$this->dbUtilities->getCount("SELECT application_id FROM applications WHERE application_id = $delete_app_id AND admin_id = " . $this->getUserId())) {
            return $this->setError(1, "You have no Permission to change this Application!");
        } else {
            return $this->deleteApplications($delete_app_id);
        }
    }

    private function deleteApplications($delete_app_id) {
        $this->dbUtilities->query("DELETE FROM applications WHERE application_id = $delete_app_id");
        return array('user_id' => $this->getUserId(), 'app_id' => $delete_app_id);
    }

}

?>