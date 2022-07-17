<?php

class DBConnection {

    protected $host = "192.168.1.3";
    protected $port = "5433";
    protected $host_type = "pgsql";
    protected $username = "user";
    protected $password = "pass";
    protected $database = "db";
    protected $connection = NULL;
    protected $status = FALSE;

    function connect() {
        try {
            $this->connection = new PDO("$this->host_type:host=$this->host;port=$this->port;dbname=$this->database", $this->username, $this->password);
            $this->status = TRUE;
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->connection = NULL;
            $this->status = FALSE;
        }
    }

    function close_connection() {
        $this->connection = NULL;
        $this->status = FALSE;
    }

    function get_connection() {
        return $this->connection;
    }

    function get_status() {
        return $this->status;
    }

    function get_host_type(){
        return $this->host_type;
    }

}
