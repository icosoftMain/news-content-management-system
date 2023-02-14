<?php namespace FLY\Libs;

/**
 * @author K.B Brew <flyartisan@gmail.com>
 * @package libs
 */

abstract class Validator extends FLYFormValidator {

    protected $request;

    protected $validator;

    protected $model    = [];

    protected $response = [];
    
    public function __construct(Request $request)
    {
        $this->validator = self::check($request,$this->error_report());
        $this->request = $this->validator->get_request();
    }

    abstract protected function error_report(): array;
}