<?php
namespace ACES\Core;

use ACES\Common\Constants;
use ACES\Common\domain\JosBaseResponse;
use ACES\Common\domain\JosMasterKeyGetResponse;
use ACES\Common\HttpsClient;
use ACES\TDEClient;
use Exception;
use RuntimeException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use ACES\Common\TDEStatus;
use ACES\Common\Exception\ServiceErrorException;
use ACES\Common\MKey;
use JsonMapper;
use ACES\Common\KStoreType;
use ACES\Common\KeyRequest;
use ACES\Common\Token;
use ACES\Common\Exception\NoValidKeyException;
use ACES\Common\CacheKeyStore;
use ACES\Utils\UtilTools;
use Common\Exception\CorruptKeyException;
/**
 * KMClient Implementation
 *
 * @author JD Security (tenma.lin, wei.gao, mozhiyan, xuyina)
 * @version 1.0.0
 */

define("KMSLOGFILE", getenv("HOME")."/aces-log/kmclient.log");
if(!defined("LOGCONSOLE")){
    define("LOGCONSOLE", __DIR__."/../../../tde.log");
}
if(!defined("LOGLEVEL")){
    define("LOGLEVEL", Logger::DEBUG);
}
if(!defined("EMPTYSTR")){
    define("EMPTYSTR", "");
}

final class KMClient{
//    const TVALUE =  3;    // Threshold value, 3 should be good
//    const KMS_SERVER_ENDPOINT = Constants::KMS_SERVER_ENDPOINT;
//    const INDEX_SERVER_ENDPOINT = Constants::INDEX_SERVER_ENDPOINT;
    private $tde;
    private $jsonMapper;
    /**
     * @var HttpReportClient
     */
    private $reporter;
    private $cacheKeyStore;
    private $userToken;
    private $corruptKeyList;
    private $availableKeyList;
    private $majorSdkVer = 0;           // major sdk version number (for major upgrade)
    private $log;
//    private $keyCacheDisabled = 0;      // key cache capability (0 for enabled, only disabled by KMS)
//    private $keyBackupDisabled = 0;     // key backup capability (0 for enabled, only disabled by KMS)
    private $keyChainIsReady = False;   // flag to indicate encryption/decryption keys are ready in memory
//    private $keyCacheFolder;            // key cache file folder
//    private $keyCacheFile;              // key cache file location
//    private $keyBackupFolder;           // key Backup file folder
//    private $keyBackupFile;             // key backup file location
    private $majorKeyVer;               //newest version holder for major service
//     private $epoch = 28800;
    private $epoch = 5;//todo: remove, just for test

    // local variables for kms and index server endpoints, default settings are IDC
//    private $kmsURL = self::KMS_SERVER_ENDPOINT;
//    private $idxURL = self::INDEX_SERVER_ENDPOINT;
    /**
     * @var TDEClient
     */
//    private $josBaseInfo;
    /* KMClient constructor
     * @param JMQClient $mq
     * @param CacheKeyStore $keyStore
     * @param Token $token
     * @param string version
     * 
     * @return KMClient
     */
    public function __construct(TDEClient $tde, HttpReportClient $reporter, CacheKeyStore $keyStore, Token $token, $version) {
        $this->tde = $tde;
        $this->reporter = $reporter;
        $this->cacheKeyStore = $keyStore;
        $this->userToken = $token;
        $this->majorSdkVer = (int)substr($version, 0, 1);
        
        $this->corruptKeyList = array();
        $this->availableKeyList = array();
        
        // confige log
        $this->log = new Logger('kmclient');
        $formatter = new LineFormatter("[%datetime%] %channel%.%level_name%: %message%\r\n");
        $handle = new StreamHandler(LOGCONSOLE, LOGLEVEL);
        $handle->setFormatter($formatter);
        $this->log->pushHandler($handle);
        
        $this->jsonMapper = new JsonMapper();
    }
    
    public function run(){
        $this->log->info("Key Management Thread Performs Key Updating...");
        
        // catch all exceptions
        try{
            $this->fetchMKeys(0);
        } catch (Exception $e){
            $this->log->critical($e->getMessage());
            
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_THROW_JDK_EXCEPTION["code"],
                $e->getMessage(),
                UtilTools::extractStackTrace($e),
                MSG_LEVEL::ERROR);
        } catch (\Throwable $t){
            $this->log->critical($t->getMessage());
            
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_THROW_JDK_EXCEPTION["code"],
                $t->getMessage(),
                UtilTools::extractStackTrace($t),
                MSG_LEVEL::ERROR);
        }
    }
    
    /* Fetch master key from kms server
     * Could give a 'delay' parameter to delay fetching operation
     * @param int $delay
     *
     * @return void
     */
    public function fetchMKeys($delay) {
        try{
            $this->log->info("Fetch keys from " . $this->tde->getJosBaseInfo()->getServerUrl() . Constants::KMS_ENDPOINT_REQUEST_MK . ". With delay = " . $delay . " ms.");
            if($delay > 0){
                // sleep for millis
                sleep($delay / 1000);
            }

            // todo: consider thread safe lock
            $josResponse = $this->josKeyRequest();
            if (!$josResponse || $josResponse->getCode() !== 0) {
//                $this->log->error("jos response error code: " . $josResponse->getCode() . ". error message: " . $josResponse->getEnDesc());
                throw new Exception('code=' . $josResponse->getCode() . ', message=' . $josResponse->getEnDesc());
            }
            $keyResponse = $josResponse->getResponse();

            // prepare corrupt key list
            $this->corruptKeyList = array();
            
            if($keyResponse->getStatus_code() === 0){
                $this->importMKeys($keyResponse);
            }else{
                $this->log->info("KMS reponse error code: " . $keyResponse->getStatus_code() . ". error message: " . $keyResponse->getErrorMsg());
                if($keyResponse->getStatus_code() == TDEStatus::$TMS_REQUEST_VERIFY_FAILED["code"]
                    || $keyResponse->getStatus_code() == TDEStatus::$TMS_TOKEN_EXPIRE["code"]
                    || $keyResponse->getStatus_code() == TDEStatus::$TMS_NO_AVAILABLE_GRANTS_FOR_SERVICE["code"]
                    || $keyResponse->getStatus_code() == TDEStatus::$TMS_TOKEN_IS_FROZEN["code"]
                    || $keyResponse->getStatus_code() == TDEStatus::$TMS_TOKEN_IS_REVOKE["code"]
                    || $keyResponse->getStatus_code() == TDEStatus::$TMS_DB_DATA_NOTFOUND_ERROR["code"]){
                        // Errors from TMS
                        $this->reporter->insertErrReport(
                            $keyResponse->getStatus_code(), 
                            $keyResponse->getErrorMsg(),
                            EMPTYSTR, 
                            MSG_LEVEL::SEVER);
        
                        // Handle cases: frozen, expired, verify failed, revoke
                        $this->cacheKeyStore->removeAllMKeys();     // For security reason, better to remove all keys
//                         $this->deleteKeyCache();                    // Delete key cache of this token only because token has issues
                        $this->keyChainIsReady = False;             // Set flag to false
                }else{
                    // other errors
                    $this->reporter->insertErrReport(
                        $keyResponse->getStatus_code(),
                        $keyResponse->getErrorMsg(),
                        EMPTYSTR,
                        MSG_LEVEL::ERROR);
                      
                }
                throw new ServiceErrorException($keyResponse->getErrorMsg());
            }
        } catch (RuntimeException $e){
            $this->log->critical($e->getMessage());

            $this->reporter->insertErrReport(
                TDEStatus::$SDK_CANNOT_REACH_KMS["code"],
                TDEStatus::$SDK_CANNOT_REACH_KMS["message"].$e->getMessage(),
                UtilTools::extractStackTrace($e),
                MSG_LEVEL::SEVER);
            
            throw new \RuntimeException(TDEStatus::$SDK_CANNOT_REACH_KMS["message"]);
        }
        // todo: interruptedException
        catch (Exception $e){
            $this->log->critical($e);
            throw $e;
        }
    }

    private function josKeyRequest()
    {
        //TODO 只有在voucher被冻结时才应该重新获取voucher
//        if ($this->josBaseInfo->getAccessToken() != null && $this->isKeyChainReady()) {
//            //request voucher
//            $userToken = Token::requestJosVoucher($this->josBaseInfo);
//            $this->userToken->transferToken($userToken);
//        }

        //request mk
        $requestUrl = $this->tde->getJosBaseInfo()->getServerUrl();
        $keyRequest = new KeyRequest($this->userToken, $this->majorSdkVer);
        $payload = $keyRequest->toFormParams($this->tde->getJosBaseInfo());
        $this->log->info('master key request url -> ' . $requestUrl . ', payload -> ' . json_encode($payload));
        $josResponse = HttpsClient::postForm($requestUrl, $payload);
        $response = JosBaseResponse::parse($josResponse, new JosMasterKeyGetResponse());
        return $response;
    }

    private function importMKeys($keyResponse){
        if(strcmp($keyResponse->getEnc_service(), $this->userToken->get_service_name())!=0){
            $this->log->critical(TDEStatus::$SDK_RECEIVED_WRONG_KEYRESPONSE1['message']);
            
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_RECEIVED_WRONG_KEYRESPONSE1["code"],
                TDEStatus::$SDK_RECEIVED_WRONG_KEYRESPONSE1["message"],
                EMPTYSTR,
                MSG_LEVEL::ERROR);
            
            throw new ServiceErrorException(TDEStatus::$SDK_RECEIVED_WRONG_KEYRESPONSE1['message']);
        }
       
        if(strcmp($keyResponse->getTid(), $this->userToken->get_id())!=0){
            $this->log->critical(TDEStatus::$SDK_RECEIVED_WRONG_KEYRESPONSE2['message']);
            
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_RECEIVED_WRONG_KEYRESPONSE2["code"],
                TDEStatus::$SDK_RECEIVED_WRONG_KEYRESPONSE2["message"],
                EMPTYSTR,
                MSG_LEVEL::ERROR);
            
            throw new ServiceErrorException(TDEStatus::$SDK_RECEIVED_WRONG_KEYRESPONSE2['message']);
        }
        
        // get two lists of key IDs to make sure old keys could be removed from cache_ks
        $enc_rmv_list = $this->cacheKeyStore->getKeyIDList(KStoreType::ENC_STORE);
        $dec_rmv_list = $this->cacheKeyStore->getKeyIDList(KStoreType::DEC_STORE);
        
        $this->cacheKeyStore->resetFutureKeyIDs();
        
        foreach ($keyResponse->getService_key_list() as $service){
            $mkeys = $service->getKeys();
            
            $this->availableKeyList[$service->getService()] = sizeof($mkeys)-1;
            
            foreach ($mkeys as $key){
                $k = new MKey(
                    $service->getService(), 
                    base64_decode($key->getId()), 
                    base64_decode($key->getKey_string()), 
                    $key->getKey_digest(), 
                    $key->getVersion(), 
                    $key->getKey_effective(), 
                    $key->getKey_exp(), 
                    $key->getKey_type(), 
                    $service->getGrant_usage(), 
                    $key->getKey_status());
                if($k->isValid()){
                    if(strcmp($service->getService(), $this->userToken->get_service_name())===0){
                        $this->majorKeyVer = $service->getCurrent_key_version();
                        // update to enc/dec key cache if neccessary
                        $this->cacheKeyStore->updateKey($key->getId(), $k, KStoreType::ENC_STORE);
                        $this->cacheKeyStore->updateKey($key->getId(), $k, KStoreType::DEC_STORE);
                        unset($enc_rmv_list[array_search($key->getId(), $enc_rmv_list)]);
                        unset($dec_rmv_list[array_search($key->getId(), $dec_rmv_list)]);
                    }else{
                        // update to decryption key cache only
                        $this->cacheKeyStore->updateKey($key->getId(), $k, KStoreType::DEC_STORE);
                        unset($dec_rmv_list[array_search($key->getId(), $dec_rmv_list)]);
                    }
                }else{
                    // The key is corrupted
                    $this->corruptKeyList[] = base64_encode($k->getID());
                }
            }
            $this->cacheKeyStore->updateFutureKeyIDs($service->getService(), $service->getCurrent_key_version());
        }
        
        // todo: generate key update report with assigned key list information
        $this->reporter->insertKeyUpdateEventReport(TDEStatus::$SDK_REPORT_CUR_KEYVER['code']
            , TDEStatus::$SDK_REPORT_CUR_KEYVER['message'] . $this->majorSdkVer, $this->majorSdkVer, $this->availableKeyList);
        $this->availableKeyList = array();
        
        // adjust key store cache
        if(sizeof($enc_rmv_list) > 0){
            $this->log->info(sizeof($enc_rmv_list));
            $this->cacheKeyStore->removeKeysViaList($enc_rmv_list, KStoreType::ENC_STORE);
        }
        if(sizeof($dec_rmv_list) > 0){
            $this->cacheKeyStore->removeKeysViaList($dec_rmv_list, KStoreType::DEC_STORE);
        }
        
        // verify key store by compare their digest
        $this->sendCorruptReport();
        // check valid key chain
        $this->checkValidKeyChain();
        TDEClient::getClientCache()->set($this->tde->getJosBaseInfo()->getAccessToken(), $this->tde);
    }
    
    private function sendCorruptReport(){
        if(!empty($this->corruptKeyList)){
            $this->log->critical(TDEStatus::$SDK_HAS_CORRUPTED_KEYS["message"]);
            // prepare string
            $keyids = "keyids:";
            foreach ($this->corruptKeyList as $corruptkey){
                $keyids .= $corruptkey . ",";
            }
            $keyids = substr($keyids, 0, strlen($keyids)-1);
            
            $this->reporter->insertErrReport(TDEStatus::$SDK_HAS_CORRUPTED_KEYS["code"], TDEStatus::$SDK_HAS_CORRUPTED_KEYS["message"], $keyids, MSG_LEVEL::ERROR);
            
            throw new CorruptKeyException(TDEStatus::$SDK_HAS_CORRUPTED_KEYS["message"]);
        }
    }
    
    private function checkValidKeyChain(){
        $this->keyChainIsReady = FALSE;
        
        $total_keys = $this->cacheKeyStore->numOfKeys(KStoreType::DEC_STORE) + $this->cacheKeyStore->numOfKeys(KStoreType::ENC_STORE);

        // fail-fast
        if($total_keys === 0){
            $this->log->critical(TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"]);
            
            // should not happen, probably due to some internal error or other issues
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["code"],
                TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"],
                EMPTYSTR, 
                MSG_LEVEL::SEVER);
            
            throw new NoValidKeyException(TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"]);
        }
        
        $this->log->info("# of enc keys: " . $this->cacheKeyStore->numOfKeys(KStoreType::ENC_STORE) . " and # of dec keys:".$this->cacheKeyStore->numOfKeys(KStoreType::DEC_STORE));
        $this->log->info("Max key version for major service: ".$this->majorKeyVer);
        
        // at least the memory has functional keychain already
        $this->keyChainIsReady = TRUE;
    }
    
    /* Set KMS server url where SDK fetches keys
     * @param string $kmsURL
     * 
     * @return void
     */
//    public function setKMSEndpoint($kmsURL){
//        $this->kmsURL = $kmsURL;
//    }
    
    /* Set Index server url
     * @param string $idxURL
     * 
     * @return void
     */
//    public function setIDXEndpoint($idxURL) {
//        $this->idxURL = $idxURL;
//    }
    
    /* Get major key version
     * 
     * @return int
     */
    public function getMajorKeyVersion() {
        return $this->majorKeyVer;
    }
    
    /* Return true if key chain is ready
     * 
     * @return bool
     */
    public function isKeyChainReady(){
        return $this->keyChainIsReady;
    }
    
    /* Reset key chain flag to false
     * 
     * @retunr void
     */
    public function resetKeyChainFlag(){
        $this->keyChainIsReady = FALSE;
    }
    
    public function setEpoch($epoch){
        $this->epoch = $epoch;
    }
}