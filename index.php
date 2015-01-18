<?php

header("Content-Type: application/json; charset=utf-8");
include_once 'libs/classes.php';
include_once 'conf/db.php';

class Main {
    
    /* @var $request Request */
    public $request = null;
    /* @var $response Response */
    public $response = null;
    /* @var $methods Methods */
    public $methods = null;

    public function __construct() {
        $this->request = new Request($this);
        $this->response = new Response($this);
        $this->methods = new Methods($this);
        $this->request->getParams();
        $output = $this->methods->executeMethods();
        echo $this->response->sendOutput($output);
    }

}

new Main();
?>