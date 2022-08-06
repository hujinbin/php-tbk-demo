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
    define("LOGLEVEL", Logger::ERROR);
}

/**
 * ACES PHP HttpsClient
 * <P>
 *
 * @author JD Data Security Team (tenma.lin, wei.gao, mozhiyan, xuyina)
 * @version 1.0
 *        
 */
final class HttpsClient {
    private static $CAPath = '';
    
    public static function loadCACert($isProd){
        $prodCaPath = __DIR__ . Constants::PROD_CA_RELATIVE_PATH;
        $betaCaPath = __DIR__ . Constants::BETA_CA_RELATIVE_PATH;
        self::$CAPath = $isProd ? $prodCaPath : $betaCaPath;
    }

    public static function postForm($requestURL, $params) {

        // confige log
        $log = new Logger('httpsClient');
        $formatter = new LineFormatter("[%datetime%] %channel%.%level_name%: %message%\r\n");
        $handle = new StreamHandler(LOGCONSOLE, LOGLEVEL);
        $handle->setFormatter($formatter);
        $log->pushHandler($handle);

        $response = '';
        $rootCause = '';
        $hasConn = False;

        for($retry=0;$retry<Constants::HTTP_RETRY_MAX && !$hasConn;$retry++){
            try {
                $ch = curl_init($requestURL);
                curl_setopt($ch, CURLOPT_POST, True);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, Constants::HTTP_TIMEOUT);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, Constants::HTTP_TIMEOUT);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);  // True to return the transfer as a string of the return value
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // True verify the peer's certificate
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // True verify the peer's certificate
                curl_setopt($ch, CURLOPT_HTTPHEADER, self::httpFormHeader());
                $payload = http_build_query($params);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
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
                // curl_close($ch);
                $hasConn = True;

            } catch (Ex\HttpConnectionException $e) {
                $log->info("Https postJson error: " . $e->getMessage());
            }
        }

        if(!$hasConn){
            $log->error("HTTPS Client cannot establish connection:".$rootCause);
            throw new RuntimeException("HTTPS Client cannot establish connection:".$rootCause);
        }
        return $response;
    }
    public static function postJson($requestURL, $payload) {
        
        // confige log
        $log = new Logger('httpsClient');
        $formatter = new LineFormatter("[%datetime%] %channel%.%level_name%: %message%\r\n");
        $handle = new StreamHandler(LOGCONSOLE, LOGLEVEL);
        $handle->setFormatter($formatter);
        $log->pushHandler($handle);
        
        $response = '';
        $rootCause = '';
        $hasConn = False;
        
        for($retry=0;$retry<Constants::HTTP_RETRY_MAX && !$hasConn;$retry++){
            try {
                $ch = curl_init($requestURL);
                curl_setopt($ch, CURLOPT_POST, True);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, Constants::HTTP_TIMEOUT);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, Constants::HTTP_TIMEOUT);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);  // True to return the transfer as a string of the return value
                curl_setopt($ch, CURLOPT_CAINFO, self::$CAPath); // CA certificate
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, True);  // True verify the peer's certificate
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // True verify the peer's certificate
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // True verify the peer's certificate
                curl_setopt($ch, CURLOPT_HTTPHEADER, self::httpJsonHeader());
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
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
                // curl_close($ch);
                $hasConn = True;
                
            } catch (Ex\HttpConnectionException $e) {
                $log->info("Https postJson error: " . $e->getMessage());
            }
        }
        
        if(!$hasConn){
            $log->error("HTTPS Client cannot establish connection:".$rootCause);
            throw new RuntimeException("HTTPS Client cannot establish connection:".$rootCause);
        }
        return $response;
    }
    
    public static function httpJsonHeader() {
        $header = array(
            "Accept:application/json",
            "Content-Type:application/json;charset=UTF-8",
            "Connection:keep-alive"
        );
        return $header;
    }
    public static function httpFormHeader() {
        $header = array(
            "Accept:application/json",
            "Content-Type:application/x-www-form-urlencoded;charset=UTF-8",
            "Connection:keep-alive"
        );
        return $header;
    }
}

