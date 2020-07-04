<?php

//test for direct call

class Index extends BaseController {

    public function __construct($requested_action){
        $this->required_action = $requested_action;
        $this->required_models = array();
        $this->Process_Controller();
    }

    public function index(){

    }
}