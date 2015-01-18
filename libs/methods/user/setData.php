<?php

namespace user;

class setData extends \Method {

    public $need_user_id = true;
    public $need_remember_key = true;
    public $need_rights = array(3);

    public function run() {
        $post = $this->main->request->getPost();
        $firstname = $this->utilities->getValueFromArray($post, 'firstname');
        $lastname = $this->utilities->getValueFromArray($post, 'lastname');
        $gender_id = $this->utilities->getValueFromArray($post, 'gender_id');
        $gender_id = intval($gender_id);
        $birthday = $this->utilities->getValueFromArray($post, 'birthday');
        $phone = $this->utilities->getValueFromArray($post, 'phone');
        $picture = $this->utilities->getValueFromArray($post, 'picture');
        if ($error = $this->checkValues($gender_id, $birthday, $picture)) {
            return $error;
        }
        $this->updateUser($firstname, 'firstname');
        $this->updateUser($lastname, 'lastname');
        $this->updateUser($gender_id, 'gender_id');
        $this->updateUser($birthday, 'birthday');
        $this->updateUser($phone, 'phone');
        $this->updateUser($picture, 'picture');
        return array('user_id' => $this->getUserId());
    }

    private function checkValues($gender_id, $birthday, $picture) {
        if ($gender_id && !$this->dbUtilities->getCount("SELECT gender_id FROM gender WHERE gender_id = $gender_id")) {
            return $this->setError(1, "Wrong gender!");
        } else if ($birthday && !$this->utilities->check_date($birthday, "Ymd", "-")) {
            return $this->setError(1, "Wrong birthday Format!");
        } /* else if ($picture && base64_encode(base64_decode($picture, true)) !== $picture) {
            return $this->setError(3, "Picture is no valid!");
        }*/
    }

    private function updateUser($value, $column) {
        if ($value) {
            if (!is_int($value)) {
                $value = $this->dbUtilities->escape($value);
            }
            $this->dbUtilities->query("UPDATE users SET $column = $value WHERE user_id = " . $this->getUserId());
        }
    }

}

?>