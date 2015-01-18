<?php

namespace user;

class getGenderInformations extends \Method {

    public function run() {
        return $this->dbUtilities->getArray("SELECT * FROM gender");
    }

}

?>