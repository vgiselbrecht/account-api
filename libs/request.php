<?php

class Request {

    private $get = null;
    private $post = null;

    public function getParams() {
        $url = $_SERVER['REQUEST_URI'];
        $url = strtolower($url);
        $get = explode("/", $url);
        array_shift($get);
        $post = $_POST;
        $this->get = $get;
        $this->post = $post;
    }

    public function getNamespace() {
        return $this->get[0];
    }

    public function getMethodName($backslash = true) {
        $slash = "/";
        if ($backslash) {
            $slash = "\\";
        }
        if (isset($this->get[1])) {
            return $this->get[0] . $slash . $this->get[1];
        }
    }

    public function getGetForPos($pos) {
        $pos--;
        if (isset($this->get[$pos])) {
            return $this->get[$pos];
        }
        return null;
    }

    public function getGet() {
        return $this->get;
    }

    public function getPost() {
        return $this->post;
    }

    public function setGet($get) {
        $this->get = $get;
    }

    public function setPost($post) {
        $this->post = $post;
    }

}

?>