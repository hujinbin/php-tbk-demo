<?php
namespace ACES\Common;

use RuntimeException;
use ACES\Common\Exception as Ex;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

if(!defined("LOGCONSOLE")){
    define("LOGCONSOLE", __DIR__."/../../../tde.log");
}
if(!defined("LOGLEVEL")){
    define("LOGLEVEL", Logger::DEBUG);
}



final class HttpClient
{
    public static function sendData($requestUrl, $method, $payload, $additional){
        
        // confige log
        $log = new Logger('httpClient');
        $formatter = new LineFormatter("[%datetime%] %channel%.%level_name%: %message%\r\n");
        $handle = new StreamHandler(LOGCONSOLE, LOGLEVEL);
        $handle->setFormatter($formatter);
        $log->pushHandler($handle);
        
        $response = null;
        $rootCause = '';
        $hasConn = False;
        
        for($retry=0;$retry<Constants::HTTP_RETRY_MAX && !$hasConn;$retry++){
            try {
                $ch = curl_init($requestUrl);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, Constants::HTTP_TIMEOUT);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, Constants::HTTP_TIMEOUT);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);  // True to return the transfer as a string of the return value
                curl_setopt($ch, CURLOPT_HTTPHEADER, $additional);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//                 curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
                $response = curl_exec($ch);
                
                // Get the error number for the last cURL operation, if no error occurs then return 0.
                if(curl_errno($ch)){
                    $rootCause = curl_error($ch);
                    throw new Ex\HttpConnectionException(curl_error($ch));
                }
                
                $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if($response_code != 200){
                    $rootCause = "Wrong http reponse code:".$response_code;
                    throw new Ex\HttpConnectionException("Wrong http reponse code:".$response_code);
                }
                
                // todo: can this way keep connection alive?
                curl_close($ch);
                $hasConn = True;
                
            } catch (Ex\HttpConnectionException $e) {
                $log->critical("Http sendData error: " . $e->getMessage());
            }
        }
        
        if(!$hasConn){
            $log->critical("HTTP Client cannot establish connection:".$rootCause);
            throw new RuntimeException("HTTP Client cannot establish connection:".$rootCause);
        }
        
        return $response;
    }
}
