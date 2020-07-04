<?php

class Token extends Base {

    public $token;
    public $ip;
    public $authorized;
    public $database;

    public function __construct($MySQL){
        if(method_exists($MySQL, "connection")) {
            if (!method_exists($MySQL->connection, "query")) {
                $MySQL->reconnect();
                $this->database = $MySQL;
            }
        }

        if(isset($_POST) && !empty($_POST)){
            if(array_key_exists("token", $_POST)){
                $this->token = $_POST["token"];
                $this->ip    = $_SERVER["REMOTE_ADDR"];
                return $this->check_token();
            }
        }

        return false;
    }

    public function check_token(){
        $sql = "SELECT `config_value` FROM `global_settings` WHERE `config_name` = 'api_token'";
        $query = $this->database->connection->query($sql);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if($result["config_value"] == $this->token){
           $token_ip_sql    = "SELECT `config_value` FROM `global_settings` WHERE `config_name` = `api_token_ips`";
           $token_ip_query  = $this->database->connection->query($$token_ip_sql);
           $token_ip_result = $token_ip_query->fetch(PDO::FETCH_ASSOC);
           $token_ip_decode = json_decode($token_ip_result["config_value"]);
           if(is_array($token_ip_decode) && !empty($token_ip_decode)){
               if(in_array($this->ip, $token_ip_decode)){
                   return true;
               }
           }
        }

        return false;
    }

    public static function generate_token(){
        require_once("../../../inc/functions.php");

        $current_time = time();
        $current_ip   = str_replace(".", "", $_SERVER["SERVER_ADDR"]);

        return encrypt($current_time . $current_ip);
    }

}