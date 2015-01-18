<?php

class DB extends mysqli{
    
    public function __construct() {
        $host = "localhost";
        $user = "root";
        $password = "";
        $db = "account";
        parent::__construct($host,$user,$password,$db);
    }
    
}

?>