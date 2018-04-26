<?php

class Database {
    protected static $connection;

    public function query($query) {
        $connection = $this->connect();
        $result = $connection->query($query);
        return $result;
    }

    public function last_insert_id() {
        $connection = $this->connect();
        $result = $connection->insert_id;
        return $result;
    }

    public function connect() {
        if(!isset(self::$connection)) {
            $config = parse_ini_file('./config.ini');
            self::$connection = new mysqli($config['host'], $config['username'], $config['password'], $config['dbname']);
        }

        if(self::$connection === false) {
            return false;
        }
        return self::$connection;
    }

}