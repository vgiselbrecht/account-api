<?php

class Response {

    private $main = null;

    public function __construct($main) {
        $this->main = $main;
    }

    public function sendOutput($output) {
        $return = "";
        if (!is_array($output) && get_class($output) == "Error") {
            $return = json_encode(array('Error' => array('errorCode' => $output->getErrorCode(), 'method' => $output->getMethod(), 'message' => $output->getMessage())));
        } else {
            $return = json_encode(array($this->main->request->getMethodName(false) => $output));
        }
        if (isset($_GET['jsonp'])) {
            $return = $_GET['jsonp']."(".$return.")";
        }
        return $return;
    }

}

?>