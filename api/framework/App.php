<?php

class App extends Base {

    public $SS_Uri;
    public $Agent_Uri;
    public $Token;

    public $Uri;
    public $AutoLoader;

    public $database;

    public $Controller;

    public function __construct(){
        if(!defined("SS_API_ROOT")){
            define("SS_API_ROOT",  realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
        }

        if(!defined("SS_API_BASE")){
            define("SS_API_BASE", SS_API_ROOT . "Base" . DIRECTORY_SEPARATOR);
        }

        if(!defined("SS_API_CONTROLLER")){
            define("SS_API_CONTROLLER", SS_API_ROOT . "Controllers" . DIRECTORY_SEPARATOR);
        }

        if(!defined("SS_API_HANDLER")){
            define("SS_API_HANDLER", SS_API_ROOT . "Handlers" . DIRECTORY_SEPARATOR);
        }

        if(!defined("SS_API_MODEL")){
            define("SS_API_MODEL", SS_API_ROOT . "Models" . DIRECTORY_SEPARATOR);
        }

        if(file_exists(SS_API_BASE . "Base.php")){
            require_once(SS_API_BASE . "Base.php");
        } else {
            exit("API base is broken");
        }

        if(file_exists(SS_API_HANDLER . "AutoLoader.php")){
            require_once(SS_API_HANDLER . "AutoLoader.php");
        } else {
            exit("API Handler is broken");
        }

        $this->Uri        = new Uri();
        $this->AutoLoader = new AutoLoader();

        $this->AutoLoader->load_directory(SS_API_BASE, true);
        $this->AutoLoader->load_directory(SS_API_HANDLER, true);

        $this->database   = new MySQL();

        $this->Token      = new Token($this->database);
        if($this->Token == false){
            exit("Unauthorized");
        }

        if($this->Uri->Controller == "functions"){
            $Controller_Action = $this->Uri->Action;
            $this->AutoLoader->load_file(SS_API_BASE . "V1" . DIRECTORY_SEPARATOR . "Index.php");
        } else {
            $this->load_controller();
        }

    }

    private function load_controller(){
        $controller_name    = ucfirst(strtolower($this->Uri->Controller));
        $path_to_controller = SS_API_CONTROLLER . $controller_name . ".php";

        $this->AutoLoader->load_file($path_to_controller);

        $this->Controller   = new $controller_name();

        $needed_models      = $this->Controller->required_models;
        if(is_array($needed_models) && !empty($required_models)){
            $load_models    = $this->load_models($needed_models);
            $this->Controller->Load_Models($load_models);
        }
    }

    private function load_models($models_to_load){
        $return_models = array();
        foreach($models_to_load as $model){
            $model_name            = ucfirst(strtolower($model));
            $path_to_model         = SS_API_MODEL . $model_name . ".php";

            $this->AutoLoader->load_file($path_to_model);
            $return_models[$model] = new $model_name($MySQL);
        }
        return $return_models;
    }




}