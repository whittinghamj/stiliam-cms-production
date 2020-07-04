<?php

class Uri {

    public $BaseUri      = "";
    public $Controller;
    public $Action;
    public $Uri_Params   = array();
    public $Query_Params = array();

    public function __construct(){
        if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on'){
            $this->BaseUri = "http";
        } else {
            $this->BaseUri = "https";
        }

        $this->BaseUri .= "://" . $_SERVER["HTTP_HOST"]."/";
        $this->parseUri();
    }

    public function parseUri(){
        $strip_api = ltrim($_SERVER["REQUEST_URI"], "/api");
        if($strip_api = "" || $strip_api == "/"){
            $this->Controller = "index";
            $this->Action     = "index";
        } else {
            if(strtok($strip_api, "?")){
                $split_query        = explode("?", $strip_api);
                $uri                = $split_query[0];
                if($uri == "c"){
                    $this->Controller = "functions";
                    $this->Action     = $uri;
                    return;
                } else {
                    $query              = $split_query[1];
                    $this->Query_Params = $this->processParams(0, $query);
                }
            } else {
                $uri = $strip_api;
            }

            $bomb_uri = explode("/", ltrim($uri, "/"));
            if(count($bomb_uri) == 1){
                $this->Controller = $bomb_uri[0];
                $this->Action     = "index";
                return;
            }

            if(count($bomb_uri) == 2){
                $this->Controller = $bomb_uri[0];
                $this->Action     = $bomb_uri[1];
                return;
            }

            if(count($bomb_uri) >= 3){
                $this->Controller = $bomb_uri[0];
                $this->Action     = $bomb_uri[1];
                $this->Uri_Params = $this->processParams(2, $bomb_uri);
                return;
            }
        }
    }

    private function processParams($times_to_shift, $path_to_params){
        if(is_string($path_to_params)){
            parse_str($path_to_params,$params_array);
        } else {
            $params_array = $path_to_params;
        }

        for ($a = 0; $a <= $times_to_shift; $a++) {
            $params_array = array_shift($params_array);
        }

        return $params_array;
    }

    public function build_uri($controller, $action, $params = null){
        $uri = $this->BaseUri.$controller."/".$action;
        if($params != null){
            $uri.="/".$params;
        }
        return $uri;
    }

}
