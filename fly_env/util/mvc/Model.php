<?php namespace FLY\MVC;

use FLY_ENV\Util\Syncs\Config;
use FLY_ENV\DB\SQLPDO;
use FLY\DSource\DSource_Model;

class Model extends Config {
    
    private static $connected_db;

    private static $table_name;

    public static $CONFIG_TYPE = null;

    public function __construct()
    {
        parent::__construct(self::$CONFIG_TYPE);
        $this->boot_database();
    }

    public function set_table_name(DSource_Model $ds_model) 
    {
        self::$table_name = $ds_model->get_table_name();
    }

    public function setTableName(string $tableName)
    {
        self::$table_name = $tableName;
    }

    static public function data_table()
    {
        return self::$table_name;
    }

    static public function last_id($id_name)
    {
       return self::record_id(count(self::get_all()) - 1,$id_name);       
    }

    static public function first_id($id_name)
    {
        return self::record_id(0,$id_name);
    }

    static public function query($query)
    {
        new Self;
        return self::$connected_db->command($query);
    }
    
    static private function record_id($record_position,$id_name)
    {
        $record = self::get_all();
        return (isset($record[0])) ? $record[$record_position][$id_name] : "";
    }

    static private function get_record($record_position)
    {
        $record = self::get_all();
        return (isset($record[0])) ? $record[$record_position] : $record;
    }

    static public function last_record()
    {
        return self::get_record(count(self::get_all()) - 1);
    }

    static public function first_record()
    {
        return self::get_record(0);
    }
    
    public function select_value_by_row(string $field_name, array $pk_names, array $pk_values)
    {
        $response = "";
        if(isset($pk_names[0]) && isset($pk_values[0])) {
            $sql_expression = $this->construct_and_expression($pk_names,$pk_values);
            $query  = "SELECT {$field_name} FROM ".self::$table_name;
            $query .=  " WHERE ".$sql_expression;
            $response = self::$connected_db->fetchData($query);
            if($this->data_exists($response)) $response = $response[0][$field_name];
            else $response = "";
        }
        return $response;
    }

    public function select_by_field(string $field_name)
    {
        return (
            trim($field_name) <> "" 
            ?
            self::$connected_db->fetchData("SELECT {$field_name} FROM ".self::$table_name)
            : []
        );
    }

    public function select_by_filtering(string $field_name,$where_query,$limit) 
    {
        $lmt = "";
        if(is_int($limit)) $lmt = " LIMIT ".$limit;
        
        $query  = "SELECT {$field_name} FROM ".self::$table_name;
        $query .= " WHERE ".$where_query.$lmt;
        $response = self::$connected_db->fetchData($query);
    
        return (!$this->data_exists($response)) ? $response = [] : $response;
    }

    public function delete_by_filter(string $where_query)
    {
        return self::$connected_db->delete_by_filter(self::$table_name,$where_query);
    }

    static public function get_all()
    {
        return self::$connected_db->command("SELECT * FROM ".self::$table_name);
    }

    public function insert_row(array $fields_and_values)
    {
        return self::$connected_db->insert(self::$table_name,$fields_and_values);
    }

    public function delete_by_id(array $pk_names, array $pk_values)
    {
        $response = false;
        if(isset($pk_names[0]) && isset($pk_values[0])) {
            $response = self::$connected_db->delete_by_id(
                    self::$table_name,
                    $this->construct_and_expression($pk_names,$pk_values)
            );
        }
        return $response;
    }
    
    public function edit_by_id($fields_payload,array $pk_names, array $pk_values) 
    { 
        $response = false;
        if(isset($pk_names[0]) && isset($pk_values[0])) {
            $response = self::$connected_db->update_by_id(
                self::$table_name,
                $fields_payload,
                $this->construct_and_expression($pk_names,$pk_values)
            );
        }
        return $response;
    }

    private function construct_and_expression(array $pk_names, array $pk_values)
    {
        $sql_expression = " {$pk_names[0]}='{$pk_values[0]}' ";
        foreach($pk_names as $key => $name) {
            if($key === 0) continue;
            $sql_expression .= "AND $name='{$pk_values[$key]}' ";
        }
        return $sql_expression;
    }

    private function data_exists($response)
    {
        return (!empty($response) && isset($response[0])); 
    }

    private function boot_database()
    {
        self::$connected_db = SQLPDO::getInstance(
            self::getHost(),
            self::getDBase(),
            self::getUser(),
            self::getPassword()
        );
    }

}