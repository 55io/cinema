<?php
class Request {
    private $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function select($query) {
        $rows = array();
        $result = $this->database->query($query);
        if($result === false) {
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function insert($query) {
        $query_result = $this->database->query($query);
        return $query_result;
    }

    public function last_insert_id(){
        return $this->database->last_insert_id();
    }
}