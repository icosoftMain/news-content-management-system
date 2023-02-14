<?php namespace App\Controllers\Packs;

use FLY\Security\Sessions;

trait Pages {

    private static $sessions;

	public function __construct()
	{
		parent::__construct();
		self::$sessions = new Sessions;
	}

	static private function clearReportSession()
    {
        if(self::$sessions::exists('reports'))
            self::$sessions::remove('reports');
        if(self::$sessions::exists('requestValues'))
            self::$sessions::remove('requestValues');
    }

    /**
     * @return array
     */

    static private function getRequestReports()
    {
        $alertType = ""; $alertText = "";

        if(self::$sessions::exists('reports')) {
            $alertText = self::$sessions::get('reports')['payload'];
            if(self::$sessions::get('reports')['state']) {
                $alertType = 'success';
                self::$sessions::remove('reports');
                if(self::$sessions::exists('requestValues'))
                    self::$sessions::remove('requestValues');
            }
            else $alertType = 'error';
        }
        return array(
            'alertType' => $alertType,
            'alertText' => $alertText
        );
    }
    
    static public function displayLimit(array $data, int $limit)
    {
        $payload = [];
        $dataLen = count($data);
        $pagLen  = 1;
        $flag    = true;
        $payload = self::limitFields($data,$limit);

        for($index = 0; ($index + 1) < $dataLen; $index++) {
            if($limit === 0) break;
            if(($index + 1) > $limit && ($index + 1) % $limit === 0) $flag = true;    
                  
            if(($index + 1) > $limit && $flag) {
                ++$pagLen;
                $flag = false;
            }
        }
        
        if(empty($payload)) 
            $data['pagLim'] = $pagLen;
        else
            $payload['pagLim'] = $pagLen;
        return !empty($payload) ? $payload: $data;        
    }

    static private function limitFields($data,int $limit)
    {
        $payload = [];
        for($index = 0; $index < $limit; $index++) {
            if(!isset($data[$index])) break;
            array_push($payload,$data[$index]);
        }
        return $payload;
    }
	
}