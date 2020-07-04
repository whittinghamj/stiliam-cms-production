<?php

class AutoLoader extends Base {

    public function __construct(){
        return $this;
    }

    public function load_file($path_to_file){
        $approved_formats = array();
        if(is_file($path_to_file)){
            $file_info = pathinfo($path_to_file);
            if(in_array($file_info["extension"], $approved_formats)){
                require_once($path_to_file);
                return true;
            }
        }

        return false;
    }

    public function load_directory($directory_to_load, $recursive = false){
        if(is_dir($directory_to_load)){
            $scan_dir = scandir($directory_to_load);
            foreach($scan_dir as $item){
                if($item == "." || ".."){ continue; }
                $path_to_item = $directory_to_load . DIRECTORY_SEPARATOR . $dir;
                if(is_dir($path_to_item)){
                    if($recursive == true){
                        $this->load_directory($path_to_item, $recursive);
                    }
                }

                if(is_file($path_to_item)){
                    $this->load_file($path_to_item);
                }
            }
            return true;
        }

        return false;
    }
}
