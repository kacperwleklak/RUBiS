<?php

class DBConnection
{

    protected $host = NULL;
    protected $port = NULL;
    protected $host_type = NULL;
    protected $username = NULL;
    protected $password = NULL;
    protected $database = NULL;
    protected $connection = NULL;
    protected $status = FALSE;

    function __construct()
    {
        $this->host = getenv("RUBIS_DB_HOST");
        $this->port = getenv("RUBIS_DB_PORT");
        $this->host_type = getenv("RUBIS_DB_HOST_TYPE");
        $this->username = getenv("RUBIS_DB_USERNAME");
        $this->password = getenv("RUBIS_DB_PASSWORD");
        $this->database = getenv("RUBIS_DB_DATABASE");
    }

    function connect()
    {
        try {
            $this->connection = new PDO("$this->host_type:host=$this->host;port=$this->port;dbname=$this->database", $this->username, $this->password);
            $this->status = TRUE;
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->connection = NULL;
            $this->status = FALSE;
        }
    }

    function close_connection()
    {
        $this->connection = NULL;
        $this->status = FALSE;
    }

    function get_connection()
    {
        return $this->connection;
    }

    function get_status()
    {
        return $this->status;
    }

    function get_host_type()
    {
        return $this->host_type;
    }

}
