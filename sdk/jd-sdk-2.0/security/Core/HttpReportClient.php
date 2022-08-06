<?php
namespace ACES\Core;

use ACES\Common\domain\JosBaseResponse;
use ACES\Common\domain\TimeRecorder;
use ACES\Common\HttpsClient;
use ACES\TDEClient;
use Exception;
use ACES\Common\ProduceRequest;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use JsonMapper;
use ACES\Common\ProduceResponse;

if(!defined("LOGCONSOLE")){
    define("LOGCONSOLE", __DIR__."/../../../tde.log");
}
if(!defined("LOGLEVEL")){
    define("LOGLEVEL", Logger::ERROR);
}

class HttpReportClient
{
    private static $DEFAULT_EPOCH = 600;

    private $reports;               // array of exception, exception name => report.
    private $tdeClient;             // TDEClient instance

    private $log;
//    private $mapper;    // jsonmapper

    private $timeRecorder;
    /**
     * @var JosBaseInfo
     */
//    private $josBaseInfo;
    public function __construct(TDEClient $tde) {
        $this->reports = array();
        $this->tdeClient = $tde;

        // confige log
        $this->log = new Logger('httpReport');
        $formatter = new LineFormatter("[%datetime%] %channel%.%level_name%: %message%\r\n");
        $handle = new StreamHandler(LOGCONSOLE, LOGLEVEL);
        $handle->setFormatter($formatter);
        $this->log->pushHandler($handle);
        
//        $this->mapper = new JsonMapper();
        if (TDEClient::getClientCache() && TDEClient::getClientCache()->cacheable()) {
            $this->timeRecorder = new TimeRecorder(self::$DEFAULT_EPOCH);
        }else{
            $this->timeRecorder = new TimeRecorder(0);
        }
    }

    //FIXME 并发问题
    private function sendReport($report){
        $ret = FALSE;
        try{
            $this->log->info("http Request: ". json_encode($report));
            $serverUrl = 'https://api.jd.com/routerjson';
            // TODO
            $param = $report;
            $response = HttpsClient::postForm($serverUrl, $param);
            $this->log->info("sendReport Response: ".$response);
            $resObj = JosBaseResponse::parse($response, new ProduceResponse());
            if($resObj !== null && $resObj->getCode()===0
                && $resObj->getResponse() !== null && $resObj->getResponse()->getErrorCode() === 0){
                $this->log->info("report reply: ".$resObj->getResponse()->getErrorMsg());
                $ret = TRUE;
            } else{
                if ($resObj === null){
                    $this->log->critical("request jos failed");
                } elseif ($resObj->getCode() !== 0) {
                    $this->log->critical("request jos failed, errorMsg: " . $resObj->getEnDesc());
                } elseif ($resObj->getResponse() === null) {
                    $this->log->critical("report log failed");
                }else{
                    $this->log->critical("report log failed, errorMsg: " . $resObj->getResponse()->getErrorMsg());
                }
            }
        } catch (Exception $e){
            $this->log->critical($e->getMessage());
        }
        return $ret;
    }
    //FIXME 并发问题
    private function sendAllReports(){
        if (!$this->timeRecorder->timeout()) {
            TDEClient::getClientCache()->set($this->tdeClient->getJosBaseInfo()->getAccessToken(), $this->tdeClient);
            return;
        }
        $this->insertStatisticReport();
        $toReport = $this->reports;
        $this->log->info('------->' . json_encode($toReport));
        $this->reports = array();
        TDEClient::getClientCache()->set($this->tdeClient->getJosBaseInfo()->getAccessToken(), $this->tdeClient);
        $keys = array_keys($toReport);
        foreach ($keys as $key){
            if($this->sendReport($toReport[$key])){
                unset($toReport[$key]);
            }
        }
        if(sizeof($toReport) == 0){
            $this->log->info("reporter flushed all messages.");
        } else {
            $this->log->info("reporter buffered ".sizeof($toReport)." messages in queue.");
        }
    }

    private function insertStatisticReport(){
        $stat = null;
        if($this->tdeClient !== null) {
            $stat = $this->tdeClient->getStatistics(true);
        }

        $req = ProduceRequest::getStatisticRequest($this->tdeClient->getJosBaseInfo()->getAccessToken(), $this->tdeClient->getJosBaseInfo()->getServerUrl(), $this->getServiceId(), $this->getTokenId(), $stat);
        $josReq = $req->toFormParams($this->tdeClient->getJosBaseInfo());
        $this->log->info("Add statistic report: ".json_encode($josReq));
//        $this->reports[MSG_TYPE::STATISTIC] = json_encode($req);
        $this->reports[MSG_TYPE::STATISTIC] = $josReq;
    }

    public function insertInitReport(){
        $req = ProduceRequest::getInitRequest($this->tdeClient->getJosBaseInfo()->getAccessToken(), $this->tdeClient->getJosBaseInfo()->getServerUrl(), $this->getServiceId(), $this->getTokenId());
        $josReq = $req->toFormParams($this->tdeClient->getJosBaseInfo());
        $this->log->info("Add init report: ".json_encode($josReq));
//        $this->reports[MSG_TYPE::INIT] = json_encode($josReq);
        $this->reports[MSG_TYPE::INIT] = $josReq;
        $this->sendAllReports();
    }
    public function insertStatReport($statType){
        if($this->tdeClient !== null) {
//            $stat = $this->tdeClient->getStatistics();
//            ++ $stat[StatisticType::type($statType)];
            $this->tdeClient->stat($statType);
        }
        $this->sendAllReports();
    }

    
    public function insertEventReport($event_code, $eventDetail){
        $req = ProduceRequest::getEventRequest($this->tdeClient->getJosBaseInfo()->getAccessToken(), $this->tdeClient->getJosBaseInfo()->getServerUrl(), $this->getServiceId(), $this->getTokenId(), $event_code, $eventDetail);
        $josReq = $req->toFormParams($this->tdeClient->getJosBaseInfo());
        $this->log->info("Add event report: ".json_encode($josReq));
        // send event right away
        $this->sendReport($josReq);
    }
    
    public function insertKeyUpdateEventReport($event_code, $eventDetail, $major_key_ver, $keylist){
        $req = ProduceRequest::getKPEventRequest($this->tdeClient->getJosBaseInfo()->getAccessToken(), $this->tdeClient->getJosBaseInfo()->getServerUrl(), $this->getServiceId(), $this->getTokenId(), $event_code, $eventDetail,$major_key_ver, $keylist);
        $josReq = $req->toFormParams($this->tdeClient->getJosBaseInfo());
        $this->log->info("Add keyupdate event report: ".json_encode($josReq));
        // send event right away
        $this->sendReport($josReq);
    }
    
    public function insertErrReport($errcode, $detail, $stacktrace, $level){
        $detailLocal = $detail == null ? "":$detail;
        
        $req = ProduceRequest::getErrorRequest($this->tdeClient->getJosBaseInfo()->getAccessToken(), $this->tdeClient->getJosBaseInfo()->getServerUrl(), $this->getServiceId(), $this->getTokenId(), $level, $errcode,$detailLocal, $stacktrace);
        $josReq = $req->toFormParams($this->tdeClient->getJosBaseInfo());
        $this->log->info("Add error report: ".json_encode($josReq));
        
        if($level === MSG_LEVEL::ERROR || $level === MSG_LEVEL::SEVER){
            // send event right away
            $this->log->info("Send urgent messages.");
            $this->sendReport($josReq);
        }else{
            // buffer it
            $this->reports[$errcode] = $josReq;
            $this->sendAllReports();
        }
    }
    
    private function getServiceId()
    {
        if ($this->tdeClient !== null) {
            return $this->tdeClient->getServiceIdentifier();
        }else{
            return "Unknown Service";
        }
    }

    private function getTokenId()
    {
        if ($this->tdeClient !== null) {
            return $this->tdeClient->getTokenIdentifier();
        }else{
            return "Unknown TID";
        }
    }
}
abstract class MSG_TYPE{
    const INIT = 1;
    const EXCEPTION = 2;
    const STATISTIC = 3;
    const EVENT = 4;
}

abstract class MSG_LEVEL{
    const INFO = 1;
    const WARN = 2;
    const ERROR = 3;
    const SEVER =  4;
}