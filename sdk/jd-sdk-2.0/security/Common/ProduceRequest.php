<?php
namespace ACES\Common;

use ACES\Common\domain\JosBaseInfo;
use ACES\TDEClient;
use ACES\Core\MSG_LEVEL;
use ACES\Core\MSG_TYPE;

class ProduceRequest{
    private $accessToken;
    private $customerUserId;
    private $serverUrl;
    /**
     * @var BasicMessage
     */
    private $messages;

    /**
     * ProduceRequest constructor.
     * @param $accessToken string
     * @param $serverUrl string
     * @param $messages BasicMessage
     */
    public function __construct($accessToken, $serverUrl, $messages){
        // 区分accessToken和customerUserId
        if (isset($accessToken)) {
            if (stripos($accessToken, '_') === 0) {
                $split = explode('_', $accessToken);
                $this->customerUserId = $split[1];
            }else{
                $this->accessToken = $accessToken;
            }
        }
        $this->serverUrl = $serverUrl;
        $this->messages = $messages;
    }
    
    public static function getInitRequest($accessToken, $serverUrl, $service, $tid){
//        $messages = array();
//        $messages[] = new InitMessage($service, $tid);
        return new ProduceRequest($accessToken, $serverUrl, new InitMessage($service, $tid));
    }
    
    public static function getEventRequest($accessToken, $serverUrl, $service, $tid, $event_code, $event) {
//        $messages = array();
//        $messages[] = new EventMessage($service, $tid, $event_code, $event);
        return new ProduceRequest($accessToken, $serverUrl, new EventMessage($service, $tid, $event_code, $event));
    }
    
    public static function getKPEventRequest($accessToken, $serverUrl, $service, $tid, $event_code, $event, $major_kver, $keylist) {
//        $messages = array();
//        $messages[] = new KPEventMessage($service, $tid, $event_code, $event, $major_kver, $keylist);
        return new ProduceRequest($accessToken, $serverUrl, new KPEventMessage($service, $tid, $event_code, $event, $major_kver, $keylist));
    }
    
    public static function getErrorRequest($accessToken, $serverUrl, $service, $tid,$level, $err_code, $err_msg, $stack_trace) {
//        $messages = array();
//        $messages[] = new ErrorMessage($service, $tid, $level, $err_code, $err_msg, $stack_trace);
        return new ProduceRequest($accessToken, $serverUrl, new ErrorMessage($service, $tid, $level, $err_code, $err_msg, $stack_trace));
    }
    
    public static function getStatisticRequest($accessToken, $serverUrl, $service, $tid, $stat) {
//        $messages = array();
//        $messages[] = new StatisticMessage($service, $tid, $stat);
        return new ProduceRequest($accessToken, $serverUrl, new StatisticMessage($service, $tid, $stat));
    }
    
//    public function jsonSerialize()
//    {
//        $vars = get_object_vars($this);
//
//        return $vars;
//    }

    /**
     * @param JosBaseInfo $josBaseInfo
     * @return array
     */
    public function toFormParams($josBaseInfo)
    {
        return $josBaseInfo->getFormParams($this);
    }

    public function to360buyParamJson()
    {
        $paramJson = array();
        if (isset($this->customerUserId)) {
            $paramJson['customer_user_id'] = $this->customerUserId;
        }else {
            $paramJson['access_token'] = $this->accessToken;
        }

        $paramJson['businessId'] = $this->messages->getBusinessId();
        $paramJson['text'] = $this->messages->getText();
        $paramJson['attribute'] = $this->messages->getAttributes();

        return json_encode($paramJson);
    }
    public function getJosMethod()
    {
        return 'jingdong.jos.secret.api.report.get';
    }
}

class BasicMessage {
    protected $businessId;
    protected $text;
    protected $attributes;
    
//    public function jsonSerialize()
//    {
//        $vars = get_object_vars($this);
//
//        return $vars;
//    }
    
    public static function getRandomString(){
        $abc = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz=+-*/_|<>^~@?%&";
        $res = "";
        for($i = 0;$i<40;$i++){
            $r = rand(0,39);
            $res = $res.$abc[$r];
        }
        return $res;
    }

    /**
     * @return mixed
     */
    public function getBusinessId()
    {
        return $this->businessId;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        $json = json_encode($this->attributes);
        return $json;
    }

}

class Environment {
    private static $hostInfo = NULL;
    private static $systemInfo = NULL;
    
    public static function getHost(){
        if(self::$hostInfo == NULL){
            try {
                $preg = "/((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))/";
                
                $out = NULL;
                $stats = NULL;
                if(PHP_OS == "Windows" || PHP_OS == "WINNT"){
                    // windows get host info
                    exec("ipconfig", $out, $stats);
                    if (!empty($out)) {
                        foreach ($out AS $row) {
                            if (strstr($row, "IP") && strstr($row, ":") && !strstr($row, "IPv6")) {
                                $tmpIp = explode(":", $row);
                                if (filter_var(trim($tmpIp[1]), FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
                                    self::$hostInfo = trim($tmpIp[1]);
                                    break;
                                }else{
                                    self::$hostInfo = trim($tmpIp[1]);
                                }
                            }
                        }
                    }
                }elseif (PHP_OS == "Darwin"||PHP_OS == "Linux") {
                    exec("ifconfig", $out, $stats);
                    if (!empty($out)) {
                        foreach ($out as $oneline){
                            if(preg_match($preg, $oneline)){
                                $tmp = preg_grep($preg, explode(" ", $oneline));
                                $ip = current($tmp);
                                if(strstr($ip, "addr:")){
                                    $ip_ = explode(":", $ip);
                                    $ip = $ip_[1];
                                }
                                if (isset($ip) && $ip != "127.0.0.1") {
                                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
                                        self::$hostInfo = $ip;
                                        break;
                                    }else{
                                        self::$hostInfo = $ip;
                                    }
                                }
                            }
                        }
                    }
                }
                
                if(self::$hostInfo == NULL){
                    self::$hostInfo = "Unknown host";
                }
                
            } catch (\Exception $e) {
                self::$hostInfo = "Unknown host";
            }
        }
        return self::$hostInfo;
    }
    
    public static function getSystemInfo(){
        if(self::$systemInfo == NULL){
            try{
                $cpuout = NULL;
                $stats = NULL;
                $phpv = NULL;
                $version = "php version unknown";
                if(PHP_OS == "Linux"){
                    exec("php -v",$phpv, $stats);
                    if($phpv != NULL && sizeof($phpv)!=0){
                        $version = $phpv[0];
                    }else{
                        $version = " PHP " . PHP_VERSION;
                    }

                    exec("cat /proc/cpuinfo | grep 'model name'|uniq", $cpuout, $stats);
                    $cpuInfo = explode(":", $cpuout[0]);
                    $cpuInfo = $cpuInfo[1];
                    self::$systemInfo = PHP_OS."|".$version."|".$cpuInfo;
                // mac
                } elseif (PHP_OS == "Darwin"){
                    exec("php -v",$phpv, $stats);
                    if($phpv != NULL && sizeof($phpv)!=0){
                        $version = $phpv[0];
                    }
                    exec("sysctl -n machdep.cpu.brand_string", $cpuout, $stats);
                    $cpuInfo = $cpuout[0];
                    self::$systemInfo = PHP_OS."|".$version."|".$cpuInfo;
                } elseif (PHP_OS == "Windows" || PHP_OS == "WINNT"){
                    exec("php -v", $phpv,$stats);
                    if($phpv != NULL && sizeof($phpv)!=0){
                        $version = $phpv[0];
                    }
                    exec("wmic cpu get name", $cpuout, $stats);
                    $cpuInfo = $cpuout[1];
                    self::$systemInfo = PHP_OS."|".$version."|".$cpuInfo;
                }
                
                if(self::$systemInfo == NULL){
                    self::$systemInfo = PHP_OS . "|" . " PHP " . PHP_VERSION;
                }
            } catch (\Exception $e){
                self::$systemInfo = PHP_OS . "|" . " PHP " . PHP_VERSION;
            }
        }
        return self::$systemInfo;
    }
}

class BasicAttribute {
    public $type;
    public $host;
    public $level;
    public $service;
    public $tid;
    public $sdk_ver;
    public $env;
    public $ts;
    
    public function __construct($type, $level, $service, $tid){
        $this->type = $type;
        $this->host = Environment::getHost();
        $this->level = $level;
        $this->service = $service;
        $this->tid = $tid;
        $this->sdk_ver = TDEClient::getSDKVer();
        $this->env = Environment::getSystemInfo();
        $this->ts = date_timestamp_get(new \DateTime()) * 1000;
    }
    
//    public function jsonSerialize()
//    {
//        $vars = get_object_vars($this);
//
//        return $vars;
//    }
}

class EventAttribute extends BasicAttribute{
    public $code;
    public $event;
    
    public function __construct($type, $level, $service, $tid, $code, $event){
        parent::__construct($type, $level, $service, $tid);
        $this->code = $code;
        $this->event = $event;
    }
    
//    public function jsonSerialize()
//    {
//        $vars = get_object_vars($this);
//        return $vars;
//    }
}

class KPEventAttribute extends EventAttribute{
    public $cur_key;
    public $keylist;
    
    public function __construct($type, $level, $service, $tid, $code, $event, $major_kver, $keylist) {
        parent::__construct($type, $level, $service, $tid, $code, $event);
        $this->cur_key = $major_kver;
        $this->keylist = $keylist;
    }
}

class ErrorAttribute extends BasicAttribute {
    public $code;
    public $msg;
    public $heap;
    
    public function __construct($type, $level, $service, $tid, $err_code, $err_msg, $stacktrace) {
        parent::__construct($type, $level, $service, $tid);
        $this->code = $err_code;
        $this->msg = $err_msg;
        $this->heap = $stacktrace;
    }
}

class StatisticAttribute extends BasicAttribute {
    public  $enccnt;
    public  $deccnt;
    public  $encerrcnt;
    public  $decerrcnt;
    public  $signcnt;
    public  $verifycnt;
    public  $signerrcnt;
    public  $verifyerrcnt;
    
    public function __construct($type, $level, $service, $tid, $stat) {
        parent::__construct($type, $level, $service, $tid);
        $this->enccnt = $stat === null ? "0":$stat[0];
        $this->deccnt = $stat === null ? "0":$stat[1];
        $this->encerrcnt = $stat === null ? "0":$stat[2];
        $this->decerrcnt = $stat === null ? "0":$stat[3];
        $this->signcnt = $stat === null ? "0":$stat[4];
        $this->verifycnt = $stat === null ? "0":$stat[5];
        $this->signerrcnt = $stat === null ? "0":$stat[6];
        $this->verifyerrcnt = $stat === null ? "0":$stat[7];
    }
}

class InitMessage extends BasicMessage {
    public function __construct($service, $tid) {
        $this->businessId = BasicMessage::getRandomString();
        $this->text = "INIT";
        $this->attributes = new BasicAttribute(MSG_TYPE::INIT, MSG_LEVEL::INFO, $service, $tid);
    }
}

class EventMessage extends BasicMessage {
    
    public function __construct($service, $tid, $event_code, $event) {
        $this->businessId = BasicMessage::getRandomString();
        $this->text = "EVENT";
        $this->attributes = new EventAttribute(MSG_TYPE::EVENT, MSG_LEVEL::INFO, $service, $tid, $event_code, $event);
    }
}

class KPEventMessage extends BasicMessage {
    
    public function __construct($service, $tid, $event_code, $event, $major_kver, $keylist) {
        $this->businessId = BasicMessage::getRandomString();
        $this->text = "EVENT";
        $this->attributes = new KPEventAttribute(MSG_TYPE::EVENT, MSG_LEVEL::INFO, $service, $tid, $event_code, $event, $major_kver, $keylist);
    }
}

class ErrorMessage extends BasicMessage {
    
    public function __construct($service, $tid, $level, $err_code, $err_msg, $stacktrace) {
        $this->businessId = BasicMessage::getRandomString();
        $this->text = "EXCEPTION";
        $this->attributes = new ErrorAttribute(MSG_TYPE::EXCEPTION, $level, $service, $tid, $err_code, $err_msg, $stacktrace);
    }
}

class StatisticMessage extends BasicMessage {
    
    public function __construct($service, $tid, $stat) {
        $this->businessId = BasicMessage::getRandomString();
        $this->text = "STATISTIC";
        $this->attributes = new StatisticAttribute(MSG_TYPE::STATISTIC, MSG_LEVEL::INFO, $service, $tid, $stat);
    }
}

