<?php

class Method {

    private $app_id = null;
    private $user_id = null;
    private $remember_key = null;
    public $need_user_id = false;
    public $need_remember_key = false;
    public $need_rights = array();


    /* @var $main Main */
    public $main = null;
    /* @var $utilities Utilities */
    public $utilities = null;
    /* @var $dbUtilities DbUtilities */
    public $dbUtilities = null;
    /* @var $verification Verification */
    public $verification = null;
    public $methodUtilities = null;

    public function __construct($methods) {
        $this->main = $methods->main;
        $this->utilities = new Utilities($this);
        $this->dbUtilities = new DbUtilities($this);
        $this->verification = new Verification($this);
        $this->includeMethodUtilities();
    }

    public function run() {
        return null;
    }

    public function check() {
        if ($error = $this->checkAppId()) {
            return $error;
        }
        if ($error = $this->checkUserId()) {
            return $error;
        }
        if ($error = $this->checkRememberId()) {
            return $error;
        }
        if ($this->remember_key && $error = $this->checkRights()) {
            return $error;
        }
    }

    public function checkAppId() {
        $app_id = $this->utilities->getValueFromArray($this->main->request->getPost(), "app_id");
        if (!$app_id) {
            $app_id = $this->main->request->getGetForPos(3);
            if (!$app_id) {
                return new Error(2, "", "No App-Id!");
            }
        }
        $app_id = intval($app_id);
        if (!$this->dbUtilities->getCount("SELECT application_id FROM applications WHERE application_id = $app_id")) {
            return new Error(3, "", "Wrong App-Id!");
        }
        $this->app_id = $app_id;
    }

    public function checkUserId() {
        $user_id = $this->utilities->getValueFromArray($this->main->request->getPost(), "user_id");
        if (!$user_id) {
            $user_id = $app_id = $this->main->request->getGetForPos(4);
        }
        if ($user_id) {
            $user_id = intval($user_id);
            if (!$this->dbUtilities->getCount("SELECT user_id FROM users WHERE user_id = $user_id")) {
                return new Error(5, "", "Wrong User-Id!");
            }
            $this->user_id = $user_id;
        } else if ($this->need_user_id) {
            return new Error(4, "", "No User-Id!");
        }
    }

    public function checkRememberId() {
        $remember_id = $this->utilities->getValueFromArray($this->main->request->getPost(), "remember_key");
        if (!$remember_id) {
            $remember_id = $app_id = $this->main->request->getGetForPos(5);
        }
        if ($remember_id) {
            $remember_id_escaped = $this->dbUtilities->escape($remember_id);
            if (!$this->dbUtilities->getCount("SELECT key_id FROM codes WHERE user_id = " . $this->user_id . " AND application_id = " . $this->app_id . " AND remember_key = $remember_id_escaped AND login_key IS NULL AND register_key IS NULL")) {
                return new Error(7, "", "Wrong Remember-Key!");
            }
            $this->remember_key = $remember_id;
        } else if ($this->need_remember_key) {
            return new Error(6, "", "No Remember-Key!");
        }
    }

    public function checkRights() {
        $userRights = $this->utilities->getUserRights();
        $applicationRights = $this->utilities->getApplicationRights();
        foreach ($applicationRights as $applicationRight) {
            foreach ($userRights as $userRight) {
                if ($applicationRight['right_id'] == $userRight['right_id']) {
                    continue 2;
                }
            }
            return new Error(8, "", "You need new Rights! Pleas make a relogin!");
        }
        foreach ($this->need_rights as $need_right) {
            foreach ($userRights as $userRight) {
                if ($need_right == $userRight['right_id']) {
                    continue 2;
                }
            }
            return new Error(9, "", "Your Have No Permission to use this method!");
        }
    }

    private function includeMethodUtilities() {
        $namespace = $this->main->request->getNamespace();
        if (file_exists("libs/methods/$namespace/Utilities.php")) {
            include_once "libs/methods/$namespace/Utilities.php";
            $class = $namespace . "\Utilities";
            if (class_exists($class)) {
                $this->methodUtilities = new $class($this);
            }
        }
    }

    protected function setError($code, $message) {
        return new Error($code, $this->main->request->getMethodName(false), $message);
    }

    public function getAppId() {
        return $this->app_id;
    }

    function getUserId() {
        return $this->user_id;
    }

    function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    function getRememberKey() {
        return $this->remember_key;
    }

}

class Methods {
    /* @var $main Main */

    public $main = null;

    public function __construct($main) {
        $this->main = $main;
    }

    public function executeMethods() {
        $method = $this->main->request->getMethodName();
        if (file_exists("libs/methods/$method.php")) {
            include_once "libs/methods/$method.php";
            if (!class_exists($method)) {
                if (class_exists($method . "_")) {
                    $method .= "_";
                }
            }
            if (class_exists($method)) {
                $parents = class_parents($method);
                if (in_array("Method", $parents)) {
                    return $this->startMethode($method);
                }
            }
        }
        return new Error(1, "system", "Method does not exist!");
    }

    private function startMethode($method) {
        $methodObject = new $method($this);
        $checkMethode = $methodObject->check();
        if (!$checkMethode) {
            return $methodObject->run();
        } else {
            return $checkMethode;
        }
    }

}

?>