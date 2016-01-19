<?php

class DBConexion extends SpoonDatabase
{
    private $driver = "mysql";
    private $hostname = "localhost;charset=utf8";
    private $username = "root";
    private $password = "**";
    private $database = "denunciasdb";

    public function __construct(){
        parent::__construct($this->driver, $this->hostname, $this->username, $this->password, $this->database);
    }
}
