<?php

class DbUtilities {

    private $db = null;

    public function __construct() {
        $this->db = new DB();
    }

    public function query($sql) {
        if ($result = $this->db->query($sql)) {
            return $result;
        }
        return null;
    }

    public function getArray($sql) {
        $output = array();
        if ($result = $this->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $output[] = $row;
            }
        }
        return $output;
    }
    
    public function getLastId(){
        return $this->db->insert_id;
    }

    public function getCount($sql) {
        if ($result = $this->query($sql)) {
            return $result->num_rows;
        }
    }
    
    public function escape($value){
        return "'".$this->db->real_escape_string($value)."'";
    }

}

?> 