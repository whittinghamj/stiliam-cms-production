<?php

abstract class Base {

    public function get_value($value_to_get){
        if($this->$value_to_get){
            return $this->$value_to_get;
        }
        return false;
    }

    public function set_value($value_to_set, $value){
        if($this->$value_to_set){
            $this->$value_to_set = $value;
            return true;
        }
        return false;
    }

}
