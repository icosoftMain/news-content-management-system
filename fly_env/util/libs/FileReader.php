<?php namespace FLY\Libs;

/**
 * @author K.B Brew <flyartisan@gmail.com>
 * @package libs
 */

class FileReader {
    
    private static $fileType;

    static public function fetchJSON(string $path) 
    {
        self::$fileType = 'json';
        return self::fetch(FLY_APP_ROOT_DIR.$path.'.json');
    }

    static public function fetchFile(string $path) 
    {
        $path_arr = explode('.',$path);
        self::$fileType = $path_arr[count($path_arr) - 1];
        return self::fetch($path);
    }

    static private function fetch(string $path) 
    {
        $content = NULL;
        if(file_exists($path)) {
            $content = file_get_contents($path);
            if(self::$fileType === 'json') $content = json_decode($content);
        }        
        return $content;
    }
}