<?php namespace FLY\DSource;

/**
 * @author K.B Brew <flyartisan@gmail.com>
 * @version 2.0.0
 * @package FLY\DSource
 */

use FLY_ENV\Util\Syncs\Config;
use FLY\MVC\Model;
use FLY\Libs\Request;
use FLY_ENV\Util\DSource\ModelQueries;

abstract class DSource_Model extends Config {
    
    use IDSource_Model;

    private $table_name;
    
    static private $model;

    protected $pk_names = [];

	protected $pk_values = [];

    protected $fk_names = [];

    static protected $currentModel = null;

    static private $filter_set = false;

    static private $filter_query = null;

    static private $query_limit = null;

    static private $model_changed = false;

    static private $request_set = false;

    static private $request_model = null;

    public function __construct(DSource_Model $currentModel = null)
    {
        parent::__construct(null);

        self::$currentModel = $currentModel;

        self::$filter_set = false;

        self::$filter_query = null;

        self::$query_limit = null;

        $this->set_table_name();
        $this->set_model();
    }

    public function __destruct()
    {
        self::$currentModel = null;
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    private function set_model()
    {
        $this->set_protocols();
        Model::$CONFIG_TYPE = 'USE_OVERRIDE';
        self::$model = new Model; 
        self::$model->set_table_name($this);  
        self::$filter_set = false;   
    }

    private function set_table_name()
    {
        $table_name_arr = explode('\\',$this->child_class());
        $this->table_name = $table_name_arr[count($table_name_arr) - 1];  
    }
    
	protected function field_is_valid($field_name,$class_object = null)
	{
        if($class_object === null) {
            $class_object = $this;
        }
        if(property_exists($class_object,$field_name)) return true;
        return false;
    }

    public function __get($field_name)
    {
		if($this->field_is_valid($field_name)) {
            $this->set_primary_key_value();
            self::$model->set_table_name($this);  
			return (
                $this->all_fields_empty() 
                ?
                self::$model->select_by_field($field_name)
                :
                self::$model->select_value_by_row($field_name, $this->pk_names,$this->pk_values)
            );
		} else {
			return $this->get_config_keys($field_name);
		}
    }
    
    private function all_fields_empty()
    {
        $fields = $this->get_child_class_vars();
        $flag   = true;
        
        foreach($fields as $field) {
            if(!$this->is_empty($this->{$field})) {
                $flag = false;
            break;
            }
        }
        return $flag;
    }
    
    static protected function get_last_id(DSource_Model $model)
    {
        return self::$model::last_id(isset($model->pk_names[0]) ? $model->pk_names[0]:'');
    }

    static protected function get_first_id(DSource_Model $model)
    {
        return self::$model::first_id(isset($model->pk_names[0]) ? $model->pk_names[0]:'');
    }

    static protected function get_last_record()
    {
        return self::$model::last_record();
    }

    static protected function get_first_record()
    {
        return self::$model::first_record();
    }

    static protected function get_by_ids($ids, DSource_Model $model_object) 
    {
        $_self = $model_object;
        if(is_array($ids)) {
            foreach($ids as $key => $id) {
                if(in_array($key,$_self->pk_names)) {
                    $_self->{$key} = $id;
                } else throw new \Exception('The key '.$key.' is not defined as primary key');
            }
        } else if(is_object($ids)) {
            foreach($_self->pk_names as $pk_name) {             
                if($ids->{$pk_name} !== null) {
                    $_self->{$pk_name} = $ids->{$pk_name};
                } 
            }                         
        } else if(isset($_self->pk_names[0])){
            $_self->{$_self->pk_names[0]} = $ids;
        } else throw new \Exception('No primary key found remigrate model');
        return $_self;
    }

    static protected function get_fetch($query_string, DSource_Model $model)
    {
        $model = self::$request_set && self::$request_model !== null ? self::$request_model : $model;
        $qry_model = new ModelQueries($query_string);
        $assign_ref_vars = $qry_model->get_assigned_ref_vars();
        $self_ref_vars = $qry_model->get_self_ref_vars();
        
        self::key_exists(array_merge(
            self::get_assign_keys($assign_ref_vars),
            $self_ref_vars
            ),
            $model
        );

        $token_string = $qry_model->get_query_string();
        $token_string = self::set_assigned_vars($assign_ref_vars,$token_string,$model);
        $token_string = self::set_self_vars($self_ref_vars,$token_string,$model);
        self::$filter_query = $token_string;
        self::$query_limit = $qry_model->get_limit_value();
        self::$filter_set = true;
        self::$request_set = false;
        self::$request_model = null;
        self::$currentModel = null;
    
        return self::anonymousDSource_Model(
            $model,
            self::$model,
            self::$filter_query,
            self::$query_limit
        );
    }

    static function get_assign_keys($assign_vars)
    {
        $data = [];
        foreach($assign_vars as $var) {
            array_push($data,$var['key']);
            array_push($data,$var['value']);
        }
        return $data;
    }

    static private function anonymousDSource_Model($model,$mainModel,$filterQry,$qryLmt) 
    {
        return new class($model,$mainModel,$filterQry,$qryLmt) {

            private $currentModel;

            private $mainModel;

            private $filterQuery;

            private $queryLimit;

            public function __construct($model,$mainModel,$filterQry,$qryLmt)
            {
                $this->currentModel = $model;
                $this->mainModel    = $mainModel;
                $this->filterQuery  = $filterQry;
                $this->queryLimit   = $qryLmt;
            }

            public function __get($field_name) 
            {
                $this->mainModel->setTableName($this->model_name());
                try {
                    if(in_array($field_name,$this->currentModel->get_child_class_vars())) {
                        return $this->mainModel->select_by_filtering(
                            $field_name,
                            $this->filterQuery,
                            $this->queryLimit
                        );
                    } else {
                        $currentModel = get_class($this->currentModel);
                        throw new \Exception(
                            "Warning: Field Name '{$field_name}'".
                            " does not exists in model '{$currentModel}'."
                        );
                    }
                } catch(\Exception $err) {
                    echo(PHP_EOL.$err->getMessage().PHP_EOL);
                }
            }

            public function get_object()
            {
                return $this->currentModel;
            }

            private function model_name()
            {
                $table_name_arr = explode('\\',get_class($this->get_object()));
                return $table_name_arr[count($table_name_arr) - 1];  
            }
        };
    }

    static private function set_assigned_vars(array $assigned_ref_vars,$query_string_token,$model)
    {
        $query_string_token = preg_replace('/[:]\s*/',':',$query_string_token);
        $query_string_token = preg_replace('/(?:\s*)((\=)|(\>\=)|(\<\=)|(\>)|(\<))(?:\s*)/','$1',$query_string_token);
        foreach($assigned_ref_vars as $_var) {
            $pattern = ":{$_var['key']}{$_var['opera']}:{$_var['value']}";

            $query_string_token = str_replace($pattern,
                "{$_var['key']} {$_var['opera']} '{$model->{$_var['value']}}'",
                $query_string_token
            );
        }
        return $query_string_token;
    }

    static private function set_self_vars(array $self_ref_vars,$query_string_token,$model)
    {
        foreach($self_ref_vars as $_var) {
            $pattern = '/:\s*'.$_var.'/';
            $query_string_token = preg_replace($pattern,$_var.'='."'{$model->{$_var}}'",$query_string_token);
        }
        
        return $query_string_token;
    }

    static private function key_exists($keys, DSource_Model $model)
    {
        $child_vars = $model->get_child_class_vars();

        foreach($keys as $key) {
            if(!in_array($key,$child_vars)) {
                throw new \Exception('The field name '.$key. ' does not exists in model '.$model->get_table_name());
                break;
            }
        }
    }

    static public function field_exists($key, DSource_Model $model)
    {
        return in_array($key,$model->get_child_class_vars());
    }

    public function __set($field_name, $value) 
    {
		if($this->field_is_valid($field_name)) {
            $this->{$field_name} = $value;
            self::$model->set_table_name($this);  
        } 
    }

    public function edit()
    {
        $this->reset_foreign_keys();
        $this->set_primary_key_value();
        $data = $this->set_non_empty_class_child_fields();
        return self::$model->edit_by_id($data,$this->pk_names,$this->pk_values);   
    }

    public function edit_models()
    {
        $this->edit_foreign_model();
        $data = $this->set_non_empty_class_child_fields();
        return self::$model->edit_by_id($data,$this->pk_names,$this->pk_values);
    }

    public function model_changed(): bool
    {
        return self::$model_changed;
    }

    public function delete()
    {
        if(self::$filter_set) {
            return self::$model->delete_by_filter(self::$filter_query);   
        }
        
        $this->reset_foreign_keys();
        $this->set_primary_key_value();     
        return self::$model->delete_by_id($this->pk_names,$this->pk_values);   
    }

    public function delete_models()
    {
        $this->set_primary_key_value();
        $flag = self::$model->delete_by_id($this->pk_names,$this->pk_values);
        $this->delete_foreign_model();        
        return $flag;
    }

    public function save()
    {
        $this->save_foreign_model();
        $data = $this->set_non_empty_class_child_fields();
        return self::$model->insert_row($data);
    }

    static protected function save_request_payload(DSource_Model $model, Request $request)
    {
        if(!$request::has_error()) {
            $model->set_request_payload($model,$request);
            $model->save();
        } else throw new \Exception('Request must be containing an invalid data');
    }

    static protected function edit_request_payload(DSource_Model $model, Request $request) 
    {
        if(!$request::has_error()) {
            $model->set_request_payload($model,$request);
            $model->edit();
        } else throw new \Exception('Request must be containing an invalid data');
    }

    static protected function set_request_payload(DSource_Model $model, Request $request)
    {
        if(!$request::has_error()) {
            $fields = $model->get_child_class_vars();
            foreach($fields as $field) {
                if(array_key_exists($field,$request::all())) {
                    $model->{$field} = $request::get($field);
                }           
            }
            self::$request_set = true;
            self::$request_model = $model;
            return $model;
        } else throw new \Exception('Request must be containing an invalid data');
    }

    static public function all_records()
    {
        return self::$model::get_all();
    }

    private function save_foreign_model()
    {
        foreach($this->fk_names as $fk => $ref_key) {
            if(isset($this->{$fk}) && is_object($this->{$fk}) && (get_class($this->{$fk}) instanceof DSource_Model)) {
                $foreign_model =  $this->{$fk};
                $foreign_model->save();
                $this->{$fk} = $foreign_model->{$ref_key};
            }
        }
    }

    private function delete_foreign_model()
    {   
        foreach($this->fk_names as $fk => $ref_key) {
            if(isset($this->{$fk}) && is_object($this->{$fk}) && (get_class($this->{$fk}) instanceof DSource_Model)) {
                $foreign_model =  $this->{$fk};
                $this->{$fk} = $foreign_model->{$ref_key};
                $foreign_model->delete();
            }
        }
    }

    private function edit_foreign_model()
    {
        foreach($this->fk_names as $fk => $ref_key) {
            if(isset($this->{$fk}) && is_object($this->{$fk}) && (get_class($this->{$fk}) instanceof DSource_Model)) {
                $foreign_model =  $this->{$fk};
                $foreign_model->edit();
                $this->{$fk} = $foreign_model->{$ref_key};
            }
        }
    }

    private function reset_foreign_keys()
    {
        foreach($this->fk_names as $fk => $ref_key) {
            if(isset($this->{$fk}) && is_object($this->{$fk}) && (get_class($this->{$fk}) instanceof DSource_Model)) {
                $foreign_model =  $this->{$fk};
                $this->{$fk} = $foreign_model->{$ref_key};
            }
        }
    }

    private function set_non_empty_class_child_fields()
    {
        $data = [];
        $child_class_vars = $this->get_child_class_vars();
        foreach($child_class_vars as $var) {
            if($this->is_empty($this->{$var})) continue;
            $data[$var] = $this->{$var};
            self::$model_changed = true;       
        }
        return $data;
    }

    private function is_empty($var)
    {
        $flag1 = (!isset($var) xor (trim($var) === "" || $var === NULL));
        $flag2 = is_array($var) && count($var) === 0;

        return $flag1 || $flag2;
    }

    private function set_primary_key_value()
    {
        if(empty($this->pk_values)) {
            foreach($this->pk_names as $name) {
                array_push(
                    $this->pk_values,
                    $this->{$name}
                );
            }
        }
    }

    public function is_related()
    {
        return !$this->is_empty($this->fk_names);
    }

    public function get_child_class_vars()
    {
        $ds_model_vars = get_class_vars(DSource_Model::class);
        $child_class_vars = get_class_vars($this->child_class());
        $actual_class_child_vars = [];

        foreach($child_class_vars as $key => $var) {
            if(!\array_key_exists($key,$ds_model_vars)) {
                $actual_class_child_vars[] = $key;
            }
        }
        return $actual_class_child_vars;
    }

    protected function init_protocols($host,$user,$password,$model)
    {
        $this->set_host->{$host};
        $this->set_user->{$user};
        $this->set_password->{$password};
        $this->set_model->{$model};
    }

}