<?php

class Error {

    private $errorCode = null;
    private $method = null;
    private $message = null;

    public function __construct($errorCode, $method = null, $message = null) {
        $this->errorCode = $errorCode;
        $this->method = $method;
        $this->message = $message;
    }
    
    function getErrorCode() {
        return $this->errorCode;
    }

    function getMethod() {
        return $this->method;
    }

    function getMessage() {
        return $this->message;
    }



}

?>