<?php
namespace ACES\Utils;

class UtilTools
{
    const MAX_LINES = 3;
    
    public static function extractStackTrace($e){
        $res = "Caused by: ".get_class($e)." ".$e->getMessage();
        $traces = $e->getTrace();
        
        $line = 0;
        foreach ($traces as $trace){
            if(isset($trace['class']) && isset($trace['function']) && isset($trace['line'])){
                $res = $res . " @" . $trace['class'] . "->" . $trace['function'] . "()(" . $trace['line'] . ")";
                if ($line > self::MAX_LINES)
                    break;
                $line++;
            }else{
                break;
            }
        }
        
        return $res;
    }
    
    public static function getRandomString($size=40){
        $abc = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz=+-*/_|<>^~@?%&";
        $res = "";
        for($i = 0;$i<$size;$i++){
            $r = rand(0,39);
            $res = $res.$abc[$r];
        }
        return $res;
    }

    public static function getCurrentMilis()
    {
        $mill_time = microtime();
        $timeInfo = explode(' ', $mill_time);
        $milis_time = sprintf('%d%03d', $timeInfo[1], $timeInfo[0] * 1000);
        return $milis_time;
    }
}

