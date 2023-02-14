<?php namespace FLY_ENV\Util\DSource;

/**
 * @author K.B Brew <flyartisan@gmail.com>
 * @version 2.0.0
 * @package FLY\DSource
 */

class ModelQueries {

    private $query_string;

    private $assigned_ref_vars = [];

    private $self_ref_vars = [];

    private $limit_value;

    public function __construct($query_string)
    {
        $this->query_string = $query_string;
        $this->set_limit($this->query_string);
        $this->parse();
    }

    private function setOperations()
    {
        $this->query_string = str_replace('&','AND',$this->query_string);
        $this->query_string = str_replace('|','OR',$this->query_string);
    }

    private function parse()
    {
        $this->set_self_ref_vars($this->set_assigned_ref_vars());
        $this->setOperations();
    }

    public function get_query_string()
    {
        return $this->query_string;
    }
    
    private function set_self_ref_vars($qryString)
    {
        $pattern = '%
          [:]\s*([_a-zA-Z][_a-zA-Z0-9]*)
        %xm';
        while(preg_match($pattern,$qryString,$match)) {
            array_push($this->self_ref_vars,$match[1]);
            $qryString = str_replace($match[0],'',$qryString);
        }
    }

    private function set_assigned_ref_vars()
    {
        $qryString = $this->query_string;
        $pattern = '%
            [:]\s*(?P<key>[a-zA-Z_][a-zA-Z0-9_]*)
            (?:\s*)(?P<opera>(?:(?:\=)|(?:\>\=)|(?:\<\=)|(?:\>)|(?:\<)))(?:\s*)(?:[:]\s*(?P<value>[a-zA-Z_][a-zA-Z0-9_]*))
        %xm';

        while(preg_match($pattern,$qryString,$match)) {
            array_push($this->assigned_ref_vars,[
                'key'   => $match['key'],
                'opera' => $match['opera'],
                'value' => $match['value']
            ]);
            $qryString = str_replace($match[0],'',$qryString);
        }
        return $qryString;
    }
    

    public function get_assigned_ref_vars()
    {
        return $this->assigned_ref_vars;
    }

    public function get_self_ref_vars()
    {
        return $this->self_ref_vars;
    }

    public function get_limit_value()
    {
        if($this->limit_value !== null)
            return (int) $this->limit_value;
        return $this->limit_value;
    }

    private function set_limit()
    {
        $pattern = "/\{(?:\s*)([0-9]+)(?:\s*)\}/";
        $matched = preg_match($pattern,$this->query_string,$match);
        if($matched) {
            $this->limit_value = $match[1];
            $this->query_string = str_replace($match[0],'',$this->query_string);
        } else {
            $pattern = "/(?:\{(?:\s*)([a-zA-Z]+)(?:\s*)\})/";
            if(preg_match($pattern,$this->query_string)) {
                throw new \Exception('Model query string error: limit is set to alphabet');
            }
        }
    }
}