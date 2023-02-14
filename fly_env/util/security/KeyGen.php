<?php namespace FLY\Security;

/**
 * @author K.B Brew <flyartisan@gmail.com>
 * @version 2.0.0
 * @package FLY_ENV\Util\Security
 */

class KeyGen 
{
    private static $charbank = [];

    private static $tokenbank = [];

    private static $primaryKey = "";

    private static $charbankLength;

    private static $tokenbankLength;

    private static $tokenLength;

    private static $token;

    private static $charlength;

    public function __construct()
    {
        self::$charbank = [
            "QWERTYUIOP123",
            "ASDFGHJKL4560",
            "ZXCVBNM789479"
        ];

        self::$charbankLength = count(self::$charbank) - 1;
        self::$charlength = strlen(self::$charbank[0]) - 1;
        self::$primaryKey = "";
    }

    private static function set_tokens()
    {
        self::$tokenbank = [
            "QqWwEeRrTtYyUuIiOoP123$",
            "AaSsDdFfGgHhJjKkLl4560.",
            "ZzXxCcVvBbNnMm78947t95_"
        ];
        self::$tokenbankLength = count(self::$tokenbank) - 1;
        self::$tokenLength = strlen(self::$tokenbank[0]) - 1;
        self::$token = "";
    }

    public static function primaryKeys(int $MAX_RANGE,string $format="",bool $set_time=false)
    {
        new Self;
        $flag = TRUE;
        if(!(strpos($format,'%key'))) {
            $flag = FALSE;
            self::$primaryKey = $format;
        } 

        if(isset($MAX_RANGE)) {
            
            for($i = 0; $i < $MAX_RANGE; $i++) {
                $randIndexcontroller = mt_rand(0, self::$charbankLength);
                $randIndex = mt_rand(0, self::$charlength); 
                self::$primaryKey .= self::$charbank[$randIndexcontroller][$randIndex];

                if(strlen(self::$primaryKey) === $MAX_RANGE) break;
            }
           
            $temp = self::$primaryKey;
            if($set_time === true)
                $temp = self::get_time().self::$primaryKey;
            self::$primaryKey = str_replace('%key',$temp,$format);
            return $flag ? (self::$primaryKey) : $temp;
        }else {
            throw new \Exception("Unset key range");
        }
    }

    public static function token(int $MAX_RANGE,string $format="")
    {
        self::set_tokens();
        $flag = TRUE;
        if(!(strpos($format,'%key'))) {
            $flag = FALSE;
            self::$token = $format;
        } 

        if(isset($MAX_RANGE)) {
            for($i = 0; $i < $MAX_RANGE; $i++) {
                $randIndexcontroller = mt_rand(0, self::$tokenbankLength);
                $randIndex = mt_rand(0, self::$tokenLength); 
                self::$token .= self::$tokenbank[$randIndexcontroller][$randIndex];

                if(strlen(self::$token) === $MAX_RANGE) break;
            }
           
            $temp = self::$token;
            self::$token = str_replace('%key',$temp,$format);
            return $flag ? (self::$token) : $temp;
        }else {
            throw new \Exception("Unset key range");
        }
    }

    static private function get_time()
    {
        $datetime = new \DateTime;
        $date_str = preg_replace('/\-|(?:\s+)|\:|0/','',$datetime->format('Y-m-d H:i:s.u'));
        $date_str = explode('.',$date_str)[0];
        return $date_str;
    }
}