<?php

class CVA_Gen {

    private static $path;

    private static $namespace;

    private static $className;

    private static $labelAttached;

    private static $classDir = "";

    private static $filePath = "";

    private static $controllerName = "";

    private static $viewName = "";

    private static $activityName = "";

    private static $actionClassName = "";

    public function __construct(string $path, array $dirNames,string $dirClass, bool $labelAttached)
    {
        self::$path = $path.'app/';    
        self::$labelAttached = $labelAttached;
        self::createDirectories($dirNames, $dirClass);
    }

    static public function controllerName()
    {
        return self::$controllerName;
    }

    static public function viewName()
    {
        return self::$viewName;
    }

    static public function activityName()
    {
        return self::$activityName;
    }

    static public function actionClassName()
    {
        return self::$actionClassName;
    }

    static public function createController(string $className, string $path)
    {
        self::create($className,$path,'c');
    }

    static public function createView(string $className, string $path)
    {
        self::create($className,$path,'v');
    }

    static public function createControllerView(string $className, string $path)
    {
        self::create($className,$path,'c',true);
        self::create($className,$path,'v',true);
    }

    static public function createActivity(string $className, string $path)
    {
        self::create($className,$path,'act');
    }

    static public function createClass(string $className, string $path)
    {
        self::create($className,$path,'cls');
    }

    static private function create(string $className, string $path, string $classType, $attachLabel = false)
    {
        $className = preg_replace('%_{2,}%','_',implode('_',explode(' ',$className)));
        new Self($path,explode('/',preg_replace('%\"|\'%','',$className)),$classType,$attachLabel);
        self::createFile($classType);
    }

    static private function createDirectories(array $dirNames, string $type)
    {
        $lenOfDirNames = count($dirNames); 
        self::$filePath = self::$path;
        switch($type) {
            case 'c':
                self::$filePath .= 'controllers/';
            break;
            case 'v':
                self::$filePath .= 'views/';
            break;
            case 'act': case 'cls': default:
                self::$filePath .= "actors/";
            break;
        }
        if($lenOfDirNames > 1) {
            $dirs = "";
            self::$className = array_pop($dirNames);
            foreach($dirNames as $directory) {
                if($directory === "") break;
                $dirs .= $directory."/";
                $dirs = strtolower($dirs);
                if(!file_exists(self::$filePath.$dirs))
                   mkdir(self::$filePath.$dirs);
            }
            self::$filePath .= $dirs;
            self::$classDir = '\\'.implode('\\',$dirNames);
        } else self::$className = $dirNames[0];
    }

    static private function createFile($type)
    {
        self::$className .= (
            self::$labelAttached && $type === 'c' 
            ? 'Controller'
            : (
                self::$labelAttached && $type === 'v' 
                ? 'View' 
                : ''
            )
        );
        self::$filePath .= self::$className.'.php';
        if($type === 'c') {
            self::$namespace = '<?php namespace App\Controllers'.self::$classDir;
            self::saveFile(self::controller());
            self::$controllerName = self::$className;
        } else if($type === 'v') {
            self::$namespace = '<?php namespace App\Views'.self::$classDir;
            self::saveFile(self::view());
            self::$viewName = self::$className;
        } else if($type === 'act') {
            self::$namespace = '<?php namespace App\Actors'.self::$classDir;
            self::saveFile(self::activity());
            self::$activityName = self::$className;
        } else if($type === 'cls') {
            self::$namespace = '<?php namespace App\Actors'.self::$classDir;
            self::saveFile(self::actionClass());
            self::$actionClassName = self::$className;
        }
    }

    static private function saveFile($cmd_text)
    {
        $class_file = fopen(self::$filePath,'w');
        fwrite($class_file,$cmd_text);
        fclose($class_file);
    }

    static private function actionClass()
    {
        $activity_name = self::$className;
        $custom_namespace = self::$namespace;
        return <<<CLS
$custom_namespace;

class $activity_name {

}
CLS;
    }

    static private function activity()
    {
        $activity_name = self::$className;
        $custom_namespace = self::$namespace;
        $validator = '$this->validator->has_error()';
        $error_msg = '$this->validator->get_error_message()';
        return <<<ACT
$custom_namespace;
use FLY\Libs\{ Validator };

class $activity_name extends Validator {

\tpublic function customizeName()
\t{
\t\tif($validator) {
\t\t\treturn $error_msg;
\t\t}
\t}

\tprotected function error_report():array
\t{
\t\treturn [
\t\t\t'fieldName:dataType' => 'error message here'
\t\t];
\t}
}
ACT;
    }

    static private function controller()
    {
        $controller_name = self::$className;
        $index_method = self::get_index_method('c');
        $custom_namespace = self::$namespace;
        return <<<CTR
$custom_namespace;
use FLY\MVC\Controller;

final class $controller_name extends Controller {

$index_method

}
CTR;
    }

    static private function view()
    {
        $view_name = self::$className;
        $context = '$context';
        $index_method = self::get_index_method('v');
        $custom_namespace = self::$namespace;
        return <<<VW
$custom_namespace;
use FLY\MVC\View;

final class $view_name extends View {

\tstatic $context;

$index_method


}
VW;
    }

    static private function get_index_method($type)
    {
        $view_callback = $type === 'c' ? 'self::render_view();': '';
        return <<<ID
\tstatic function index()
\t{
    \t// code here
    \t$view_callback
    \t// code here
\t}
ID;
    }

}