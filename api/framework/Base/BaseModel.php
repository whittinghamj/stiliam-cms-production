<?php

abstract class BaseModel extends Base {

    public $table;
    public $table_columns;

    public $database;

    public function init_base($sql_object, $table_name){
        if(is_object($sql_object) && method_exists($sql_object, "query")){
            $this->database = $sql_object;
        } else {
            die("Could not establish connection with database");
        }

        if(!is_null($table_name)){
            $this->table = $table_name;
        }

        $sql = "SHOW COLUMNS FROM `" . $this->table . "`";
        $result = $this->database->prepare($sql);
        $result->execute();

        $this->table_columns = $result->fetch();
    }

    public function select_data($what, $where, $limit = null){
        $select_string = "SELECT ";
        if(is_array($what) && !empty($what)){
            $column_count  = count($what) - 1;
            $current_column = 0;
            foreach($what as $column){
                $select_string .= "`$column`";
                if($current_column <= $column_count){
                    $select_string .= ", ";
                    $current_column++;
                }
            }
        } else {
            $select_string .= "`$what` ";
        }

        $select_string .= "FROM `" . $this->table ."` WHERE ";

        if(is_array($where) && !empty($where)){
            $count_where   = count($where) - 1;
            $current_where = 0;
            $where_keys    = array_keys($where);
            foreach($where_keys as $key){
                if($key == "operator"){
                    continue;
                }
                $select_string .= "`$key` = '" . $where[$key] . "'";
                if(array_key_exists("operator", $where_keys)){
                    if($current_where <= $count_where) {
                        $select_string .= " " . $where_keys['operator'] . " ";
                    }
                }
                $current_where++;
            }
        } else {
            $select_string .= $where;
        }

        if(!empty($limit)){
            $select_string .= " LIMIT " . $limit;
        }

        $select_query  = $this->database->connection->query($select_string);
        $select_result = $this->clean_query($select_query);
        return $select_result;

    }

    public function insert_data($what, $values){
        $insert_string = "INSERT INTO `$this->table`(";
        if(is_array($what) && !empty($what)){
            $what_count   = count($what) - 1;
            $current_what = 0;
            foreach($what as $column){
                $insert_string .= "`$column`";
                if($current_what <= $what_count){
                    $insert_string .= ", ";
                }
                $current_what++;
            }
        } else {
            $insert_string .= "`$what`";
        }

        $insert_string .= ") VALUES (";

        if(is_array($values) && !empty($values)){
            $values_count   = count($values) - 1;
            $values_current = 0;

        } else {
            $insert_string .= $values;
        }

        $insert_string .= ")";

    }

    public function update_data($sets, $where){

    }

    public function delete_data($what, $where){

    }

    public function raw($sql){

    }

    public function clean_query($result_set){

    }

}