<?php namespace FLY_ENV\DB;

use FLY\DSource\App;

class SQLPDO extends \PDO {
    private static $_instance = null;
    private $_query,
            $_error = false,
            $_result,
            $_count = 0,
            $_activeModel;

    public function __construct($host="",$db="",$user="",$password="") {
        $CONFIG = $this->serverConfig($host,$db,$user,$password);
        $this->_activeModel = App::app_model_name();
        try {
            parent::__construct('mysql:host='. $CONFIG['Host'] . ';dbname=' . $CONFIG['Database'], $CONFIG['Username'], $CONFIG['Password']);
            $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $err) {
            // Show An error message
            die($err->getMessage());
        }
        try {
            if($this->_activeModel !== "" && method_exists($this->_activeModel, 'transactionActive')) {
                if($this->_activeModel::transactionActive()) {
                    $this->beginTransaction();
                }
                $this->_activeModel::setTransactionMode(TRUE);
            }
        } catch(\Exception $err) {
            $this->_activeModel::setTransactionMode(FALSE);
            $this->rollBack();
        }
    }

    public function __destruct()
    {
        if($this->_activeModel !== "" && method_exists($this->_activeModel, 'transactionActive')) {
            if($this->_activeModel::transactionActive()) {
                $this->commit();
            }
        }
    }

    public function delete_by_filter(string $table_name,string $where_query)
    {
        if(!empty($where_query)) {
            $query = "DELETE FROM ".$table_name." WHERE ".$where_query;
            $this->exec($query); 
            return true;     
        }  
        throw new \Exception('Unable to delete: filter query not set');
        return false;
    }

    private function serverConfig($host, $db, $user, $password) 
    {
        $HOST     = $host;
        $DATABASE = $db;
        $USER     = $user;
        $PASSWORD = $password;

        return [
            'Host'     => $HOST, 
            'Database' => $DATABASE,
            'Username' => $USER,
            'Password' => $PASSWORD
        ];
    }

    public static function getInstance($host="",$db="",$user="",$password="") {
        self::$_instance = new SQLPDO($host,$db,$user,$password);
        return self::$_instance;
    }

    public function query($record, $fields = []) {
        $this->_error = false;
        $flag = 1;
      
        if($this->_query = $this->prepare($record)) {
            if(is_array($fields)) {

                foreach ($fields as $field) {
                    $this->_query->bindValue($flag, $field);
                    $flag++;
                }
            }
            $this->perform();
        }
        return $this;
    }

    private function perform() {
        if($this->_query->execute()) {
            $this->_count = $this->_query->rowCount();
        } else {
            $this->_error = true;
        }
    }

    private function action($action, $table, $where = array()) {
        if(count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if(in_array($operator, $operators)) {
                $sql = "{$action} * FROM ${table} WHERE ${field} {$operator} ?";
                if(!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
    }

    private function remove($action, $table, $where = []) {
        if(count((array) $where)) {
            $operators = array('=', '>', '<', '>=', '<=');
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if(in_array($operator, $operators)) {
            
                $sql = "{$action} FROM ${table} WHERE ${field} {$operator}'{$value}'";
                
                $this->exec($sql);
                return true;
            } else return false;
        }
    }

    public function delete_by_id($table_name,$where_exp)
    {
        if(!empty($where_exp)) {
            $query = "DELETE FROM ".$table_name." WHERE ".$where_exp;
            $this->exec($query); 
            return true;     
        }  
        throw new \Exception('Unable to delete: primary keys not set');
        return false;
    }

    public function get($table, $where) {
        return $this->action('SELECT *', $table, $where);
        
    }

    public function delete($table, $where) {
        return $this->remove('DELETE', $table, $where);
    }

    public function insert($table, $fields = []) {
        if(is_array($fields)) {
            $keys = array_keys($fields);
            $values = null;
            $x = 1;

            foreach($fields as $field) {
                $values .= "?";
                if($x < count($fields)) {
                    $values .= ', ';
                }
                $x++;
            }


            $sql = "INSERT INTO {$table} (`" . implode('`,`', $keys) ."`) VALUES ({$values})";
            
            if(!$this->query($sql, $fields)->error()) {
                return true;
            }
        }
        return false;
    }

    public function fetchData($query) {
       return $this->execQueries($query);
    }

    private function execQueries($query) 
    {
        $this->_query = $this->prepare($query);
        $this->_query->execute();
        if(strpos($query,"SELECT") === 0 xor strpos($query,"CALL") === 0) {
            if($this->_query->setFetchMode(\PDO::FETCH_ASSOC)) {
               return $this->_result = $this->_query->fetchAll();
            }
        } 
        return TRUE;
    }

    public function command($query) 
    {
       return $this->execQueries($query);
    }

    public function update($table,$fields=[],$values=[],$where=[]) {

        if(is_array($fields) && !empty($where)) {
            $this->initUpdate(TRUE,$table,$fields,$values,$where);
            return true;
        } elseif(is_array($fields) && empty($where)) {
                $this->initUpdate(FALSE,$table,$fields,$values, $where);   
                return true;         
        }
        return false;
    }

    public function update_by_id(string $table,array $fields_payload, $where_exp)
    {
        $fields = \array_keys($fields_payload);
        $flag = false;
        foreach($fields as $field) {
            $query = "UPDATE {$table} SET {$field}='{$fields_payload[$field]}'";
            $query .= " WHERE ".$where_exp;
            $sql = $this->prepare($query);
            $flag = $sql->execute();
        }
        return $flag;
    }

    private function initUpdate($hasWhere, $table, $fields, $values, $where) 
    {
        $locationRequirementNumber = count($where);
        $numOfFields = count($fields);
        $numOfValues = count($values);
        if(($numOfFields === $numOfValues)) {
            $dataKey= 0;
            foreach($fields as $field) {
                if($hasWhere) {
                    if(($locationRequirementNumber !== 3))  throw new \Exception("Could not update values: Location fields is not completed expected a 'WHERE [field , operator, value] ");
                    $locationField = $where[0];
                    $operator = $where[1];
                    $locationFieldValue = $where[2];
                    $this->setUpdate($table,$field,$values[$dataKey++],$locationField,$operator,$locationFieldValue);

                } else {
                    $this->setUpdate($table,$field,$values[$dataKey++]);
                }
            }
               
        } else {
            throw new \Exception("Could not update values: Number of fields and Number of Values does not match");
        }
    }

    private function setUpdate($table,$field,$fieldValue,$locationField = "",$operator="",$locationFieldValue="") 
    {
        $hasWhere = ($locationField !== "") && ($operator !== "") && ($locationFieldValue !=="");
        $sql = "UPDATE {$table} SET {$field}="."'{$fieldValue}'";
        if($hasWhere) {
            $operators = array('=', '>', '<', '>=', '<=');
            if(in_array($operator,$operators)) {
                $sql .= " WHERE {$locationField}{$operator}'{$locationFieldValue}'";
                
            } else throw new \Exception("The operator '{$operator}' is not supported");
        } 

        $query = $this->prepare($sql);
        $query->execute();
    }
    
    public function getIDs($query) {
       return $this->fetchData($query);
    }

    public function error() {
        return $this->_error;
    }

    public function count() {
        return $this->_count;
    }

    public function results() {
        return $this->_result;
    }

    public function first() {
        return $this->results()[0];
    }
}