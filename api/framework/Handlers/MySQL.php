<?php

class MySQL extends Base {

    private $hostname = "localhost";
    private $username = "slipstream";
    private $password = "admin1372";
    private $database = "slipstream_cms";
    private $port     = 3306;
    private $dsn;

    public $connection;

    public function __construct(){

    }

    private function create_dsn(){
        $this->dsn = "mysql:host=" . $this->hostname .";";
        if($this->port != "3306" || !is_null($this->port)){
            $this->dsn .= "port=" . $this->port . ";";
        }
        $this->dsn .= "dbname=" . $this->database;
        return true;
    }

    private function connect(){
        if(isset($this->dsn) && !empty($this->dsn)){
            try {
                $this->connection = new PDO($this->dsn, $this->username, $this->password, array(PDO::ATTR_PERSISTENT => true));
                return true;
            } catch (Exception $e){
                return $e;
            }
        }
    }

    public function reconnect(){
        return $this->connect();
    }

}