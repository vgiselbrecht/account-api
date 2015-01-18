<?php

namespace user;

class getPermissionInformation extends \Method {

    public function run() {
        return $this->dbUtilities->getArray("SELECT * FROM permissions");
    }

}
