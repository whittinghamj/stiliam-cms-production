<?php

abstract class BaseController extends Base{

    public $required_action;
    public $required_models;

    public function Process_Controller(){
        if(method_exists($this, $this->required_action)){

        }

        return false;
    }

    public function Load_Models($loaded_modules){
        if(is_array($loaded_modules) && !empty($loaded_modules)){
            $this->required_models = $loaded_modules;
        }
    }

}