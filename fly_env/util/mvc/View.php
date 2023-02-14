<?php namespace FLY\MVC;

use FLY_ENV\Util\Routers\Pipe;
use FLY_ENV\Util\Wave_Engine\Wave;
use FLY\DSource\App;
use FLY\DOM\{Application,Build};
use FLY\Security\Sessions;

class View {

    private static $controller_payload = [];
    
    private static $pipe;

    private static $wave_payload;
    
    private $wave = null;

    private static $context = [];

    private static $fresh_context_set = false;

    private static $fmlClassName = "";
    
    private static $fml_used = false;

    private static $global_payloads = [];

    public function __construct()
    {
        self::$controller_payload = [];
    }

    public function __destruct()
    {
        if(self::_fmlIsActive() !== null) {
            if(self::_fmlIsActive() && self::$fml_used && !empty(self::$pipe->template_path())) {
                $FML_PORT_FILE_URL = str_ireplace(FLY_ENV_UTIL_MVC_PATH,self::$pipe->template_path(),__DIR__);
                if(file_exists($FML_PORT_FILE_URL)) {
                    $temp_file = fopen($FML_PORT_FILE_URL,'w');
                    $text = "";
                    fwrite($temp_file, $text);
                    fclose($temp_file);
                    unlink($FML_PORT_FILE_URL);
                }
            }
        }

    }

    public function set_wave_payload($wave_payload)
    {
        self::$wave_payload = $wave_payload;
    }

    public function add_pipe(Pipe $pipe)
    {
        self::$pipe = $pipe;
    }

    public function get_pipe()
    {
        return self::$pipe;
    }

    public static function _fmlIsActive() 
    {
        return self::$pipe->fml_mode();
    }

    public function execute_template_request_render(array $view_payload)
    {
        $this->preserve_context($view_payload,self::$context);

        $this->addPayload($view_payload,self::$controller_payload);

        $this->wave = new Wave($this);
        $this->wave->interpret();
        $this->show($view_payload,
            $this->wave->get_template()
        );
    }

    public function setApplicationPayload(array $view_payload) 
    {
        $this->preserve_context($view_payload,self::$context);

        $this->addPayload($view_payload,self::$controller_payload);
        self::$context = $view_payload;
    }

    public function execute_post_get_request()
    {
        if(isset(self::$controller_payload) && is_array(self::$controller_payload)) {
            echo json_encode(self::$controller_payload);
        }
    }

    private function show($view_payload,$input)
    {
        self::$global_payloads['app_name'] = App::name();

        self::$global_payloads['app_logo'] = App::logo();
        
        if(isset(self::$wave_payload) && is_array(self::$wave_payload))
            self::$global_payloads['FLY_WAVE_PAYLOAD'] = self::$wave_payload;

        extract(array_merge(self::$global_payloads,$view_payload));
        eval(' ?>'.$input. '<?php ');
    }
    
    public function setCurrentData(array $controller_payload)
    {
        self::$controller_payload = $controller_payload;
    }

    private function addPayload(array &$payload,  array $adding_stack)
    {
        foreach($adding_stack as $key => $value) {
            $payload[$key] = $value;
        }
    }

    static public function save_context(array $context)
    {
        self::$context = array_merge(self::$context, $context);
    }

    static protected function save_fresh_context(bool $flag_context)
    {
        self::$fresh_context_set = $flag_context;
    }

    public function fresh_context_isset()
    {
        return self::$fresh_context_set;
    }

    private function preserve_context(array &$view_payload, array $context)
    {
        foreach($context as $key => $value) {
            if(array_key_exists($key, $view_payload)) continue;
            $view_payload[$key] = $value;
        }
    }

    static public function render_fml(string $fmlObject)
    {
        self::$fmlClassName = $fmlObject;  
    }

    public function fmlExecute($viewName,$method)
    {
        if(!self::$fmlClassName) {
            $err_msg = 'Error: Expected to render an fml object in class '.$viewName. ' in method '.$method.'()';
            $err_msg .= ' else set fml mode to false in its router';
            throw new \Exception($err_msg);
        }
        $FML_APP = isset(class_parents(self::$fmlClassName)["FLY\DOM\Application"]) ? 
        class_parents(self::$fmlClassName)["FLY\DOM\Application"] :  "";

        try{
            if((self::$fmlClassName instanceof Application) || $FML_APP === 'FLY\DOM\Application') {
                if($this->_fmlObjectHasRenderMethod(self::$fmlClassName)) {
                    $fmlObject = new self::$fmlClassName;  
                    $render = $fmlObject->render();

                    if(!$render instanceof Build) {
                        $fmlClassName = self::$fmlClassName;
                        throw new \Exception("Error: Expected a render method to return a Build object in class: {$fmlClassName}");                            
                    }
                    $render->get_widget();
                    $this->showFML($fmlObject->getDOM());
                    self::$fml_used = true;
                } else {
                    $fmlClassName = self::$fmlClassName;
                    throw new \Exception("Error: Expected a render method in class: {$fmlClassName}");
                }
                
            } else {
                throw new \Exception("Error: Expected an fml instance as an arguement");
            }
        } catch(\Exception $err) {
            echo $err->getMessage()."<br>";
            echo "Error Occured at line: ". $err->getTrace()[0]['line']."<br>";
            echo "Error Located at file: ". $err->getTraceAsString()."<br>";
        }
    }

    static public function csrf_token_valid()
    {
        $token_valid = false;
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            new Sessions;
            $token_valid = ($_POST && isset($_POST['csrf_token']) && is_array($_SESSION['csrf_token']) && in_array($_POST['csrf_token'], $_SESSION['csrf_token']));
            if(!$token_valid)
                throw new \Exception('Invalid request: CSRF token not specified'); 
        } 
        return $token_valid;      
    }

    private function showFML(Application $dom) 
    {
        $dom->saveHTMLFile(self::$pipe->template_path());
        $this->wave = new Wave($this);
        $this->wave->interpret();
        $this->show(self::$context,
            $this->wave->get_template()
        );
    }

    private function _fmlObjectHasRenderMethod($fmlObject)
    {
        return method_exists($fmlObject, 'render');
    }
}