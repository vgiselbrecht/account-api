<?php

namespace register;

class resendEmail extends \Method{
    
    public $need_user_id = true;
    
    public function run() {
        $user_id = $this->getUserId();
        $app_id = $this->getAppId();
        $codes = $this->dbUtilities->getArray("SELECT register_key FROM codes WHERE user_id = $user_id AND application_id = $app_id AND register_key is not NULL");
        if($codes){
            $key = $codes[0]['register_key'];
            $this->verification->resendRegister($key);
            return array('user_id'=>$user_id);
        }else{
            return $this->setError(1,"No key found!");
        }
    }
}

?>