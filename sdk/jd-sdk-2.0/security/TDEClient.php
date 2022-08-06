<?php
namespace ACES;

use ACES\Common\cache\ApcuCache;
use ACES\Common\cache\YacCache;
use ACES\Common\cache\iCache;
use ACES\Common\domain\JosBaseInfo;
use ACES\Common\Exception\IndexCalculateException;
use ACES\Core\HttpReportClient;
use ACES\Core\KMClient;
use ACES\Common\STATE;
use ACES\Common\Token;
use ACES\Common\CacheKeyStore;
use ACES\Common\Constants;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use ACES\Common\TDEStatus;
use ACES\Common\Exception\InvalidTokenException;
use ACES\Common\Exception\MalformedException;
use ACES\Common\Exception\NoValidKeyException;
use ACES\Common\Exception\ArgumentNullException;
use ACES\Utils\UtilTools;
use ACES\Core\MSG_LEVEL;
use ACES\Common\Exception\ServiceErrorException;
use ACES\Common\KEY_STATUS;
use ACES\Common\Exception\InvalidKeyException;
use ACES\Common\KEY_USAGE;
use ACES\Common\Exception\InvalidKeyPermission;
use ACES\Common\IndexCalculator;
use ACES\Common\KeyEncryption;
use ACES\Common\Salsa20\FieldElement;
use ACES\Common\Salsa20\IndexCalculationHelper;
use ACES\Common\Salsa20\Salsa20;

// Cipher status
define("CIPHER_ST_DECRYPTABLE", 0);    // valid cipher, can be decrypted
define("CIPHER_ST_MALFORMED", 1);      // invalid cipher because the format is malformed (by checking cipher header)
define("CIPHER_ST_FEASIBLE", 2);       // valid cipher but key are not ready
define("CIPHER_ST_UNDECRYPTABLE", 3);  // valid cipher but undecryptable

// Token origins
define("TOKEN_ORIGIN_UNDEFINED", 0);
define("TOKEN_ORIGIN_IDC", 1);
define("TOKEN_ORIGIN_BETA", 2);
define("TOKEN_ORIGIN_DEV", 3);
if(!defined("EMPTYSTR")){
    define("EMPTYSTR", "");
}

if(!defined("LOGCONSOLE")){
    define("LOGCONSOLE", __DIR__."/../../tde.log");
}
if(!defined("LOGLEVEL")){
    define("LOGLEVEL", Logger::DEBUG);
}

final class TDEClient
{
    const version = "php 1.0.7";
    const salt = "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00";
    const keyWordSalt = "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0A\x0B\x0C\x0D\x0E\x0F\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1A\x1B\x1C\x1D\x1E\x1F";

    /**
     * @var iCache
     */
    private static $clientCache;
    /**
     * @var KMClient
     */
    private $kmc;
    /**
     * @var CacheKeyStore
     */
    private $cache_ks;
    /**
     * @var Token
     */
    private $token;

    /**
     * @var HttpReportClient
     */
    private $reporter;
    private $statistic;

    private $log;
//    private $mapper;     // json mappper;
    /**
     * @var JosBaseInfo
     */
    private $josBaseInfo;

    /* TDEClient constructor
     *
     * @param string    $tokenStr
     * @param string    $rpath
     * @param string    $kmsUrl
     * @param string    $idxUrl
     * @param bool      $isProd
     *
     */
    public function __construct($tokenStr, $josBaseInfo) {
        // confige log
        $this->log = new Logger('tdeClient');
        $formatter = new LineFormatter("[%datetime%] %channel%.%level_name%: %message%\r\n");
        $handle = new StreamHandler(LOGCONSOLE, LOGLEVEL);
        $handle->setFormatter($formatter);
        $this->log->pushHandler($handle);

        // config jsonmapper;
//        $this->mapper = new \JsonMapper();

        $this->log->info("Creating tdeclient with given token string.");
        $this->josBaseInfo = $josBaseInfo;
        $this->InitClient($tokenStr);
    }

    /**
     * @return iCache
     */
    public static function getClientCache()
    {
        if (!self::$clientCache) {
            if (extension_loaded("yac") && ini_get('yac.enable')==1) {
                self::$clientCache = new YacCache();
            }else if (extension_loaded("apcu") && ini_get('apc.enabled')==1) {
                self::$clientCache = new ApcuCache();
            }else{
                throw new \RuntimeException("neither yac nor apcu enable");
            }
        }
        return self::$clientCache;
    }

    /**
     * @param $accessToken
     * @param $appKey
     * @param $appSecret
     * @param string $serverUrl
     * @return TDEClient
     * @throws Common\Exception\JosGwException
     * @throws Common\Exception\VoucherInfoGetException
     * @throws \JsonMapper_Exception
     */
    public static function getInstance($accessToken, $appKey, $appSecret, $serverUrl='https://api.jd.com/routerjson')
    {
        // TBA: consider multi-thread scenario
        $c = self::getClientCache()->get($accessToken);
        if(!$c) {
            $josBaseInfo = new JosBaseInfo($appKey, $appSecret, $accessToken, $serverUrl);
            $tokenStr = Token::requestJosVoucherString($josBaseInfo);
            $c = new TDEClient($tokenStr, $josBaseInfo);
            self::getClientCache()->set($accessToken, $c);
        }else {
            $nullParam = $c->checkNullParam(true);
            if ($nullParam) {
                self::getClientCache()->delete($accessToken);
                $josBaseInfo = new JosBaseInfo($appKey, $appSecret, $accessToken, $serverUrl);
                $tokenStr = Token::requestJosVoucherString($josBaseInfo);
                $c = new TDEClient($tokenStr, $josBaseInfo);
                self::getClientCache()->set($accessToken, $c);
            }
        }
        return $c;
    }

    private function InitClient($tokenStr)
    {
        try{
            // step 0: jmq client
            $this->reporter = new HttpReportClient($this);
            
            // step 1:load single token
            // throws NoSuchAlgorithmException, InvalidKeyException, SignatureException,
            // InvalidTokenException, MalformedException
            $this->token = Token::parseFromString(base64_decode($tokenStr), true);
            $this->log->info("Token ID: ".$this->token->get_id().", origins from ".$this->token->getTokenOrigin());

            $this->reporter->insertInitReport();
            // step 2: prepare mkey cache and corrupt key list
            $this->cache_ks = new CacheKeyStore();

            // step 3:prepare KM client
            $this->kmc = new KMClient($this, $this->reporter, $this->cache_ks, $this->token, self::version);
            
            // step 4: adjust settings
//            if(!empty($kmsUrl)){
//                $this->kmc->setKMSEndpoint($kmsUrl);
//            }
            
            // step 5: allocate some statistic
            $this->statistic = array(0,0,0,0,0,0,0,0);
            
            // step 6: prepare JMQ client (separate thread) immediately with a given epoch
            // todo: optimize with event
            
            $delay = 0;
            $this->kmc->fetchMKeys($delay);
            if(!$this->kmc->isKeyChainReady()){
                throw new \RuntimeException(TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"]);
            }
            // todo: optimize with event
            // todo: schedule run kmc
        } catch (InvalidTokenException $e){
            $this->log->critical($e->getMessage());
            
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_USE_INVALID_TOKEN["code"],
                $e->getMessage(), 
                UtilTools::extractStackTrace($e), 
                MSG_LEVEL::SEVER);
            throw $e;
        } catch (MalformedException $e){
            $this->log->critical($e->getMessage());
            
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_THROW_JDK_EXCEPTION["code"],
                $e->getMessage(),
                UtilTools::extractStackTrace($e),
                MSG_LEVEL::ERROR);
            throw $e;
        } catch(ServiceErrorException $e){
            throw $e;
        } catch(NoValidKeyException $e){
	        throw $e;
	    } catch (\RuntimeException $e){
            $this->log->critical($e->getMessage());
            
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_THROW_JDK_EXCEPTION["code"],
                $e->getMessage(),
                UtilTools::extractStackTrace($e),
                MSG_LEVEL::ERROR);
            throw $e;
        } catch (\Throwable $e){
            $this->log->critical($e->getMessage());
            
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_INTERNAL_ERROR["code"],
                $e->getMessage(),
                UtilTools::extractStackTrace($e),
                MSG_LEVEL::ERROR);
            throw $e;
        }
    }

    /**
     * @return JosBaseInfo
     */
    public function getJosBaseInfo()
    {
        return $this->josBaseInfo;
    }
    /* Calculate the given string's SHA256 index value
     *
     * @param string $ct
     *
     * @return string
     */
//    public static function calculateIndex($pt, $salt) {
//        $ret = IndexCalculator::sha256Index($pt, $salt);
//
//        return $ret;
//    }
//
//    public static function calculateStringIndex($pt, $salt) {
//        $ret = base64_encode(IndexCalculator::sha256Index($pt, $salt));
//
//        return $ret;
//    }

    public function calculateIndex($pt){
        $k0 = $this->cache_ks->getEncKeyByVersion(0);
        if($k0 == null){
            $this->log->critical(TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"]);
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["code"],
                TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"],
                EMPTYSTR,
                MSG_LEVEL::SEVER);
            throw new NoValidKeyException(TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"]);
        }
        $index = NULL;
        try{
            $computed_salt = KeyEncryption::wrap($k0, self::salt);
            $index = IndexCalculator::sha256Index($pt, $computed_salt);
        }catch (\Exception $e){
            throw new IndexCalculateException($e->getMessage());
        }
        return $index;
    }

    public function calculateStringIndex($pt){
        $index = $this->calculateIndex($pt);
        return base64_encode($index);
    }
    
    /* Encrypt data
     *
     * @param string $pt
     *
     * @return string base64encoded
     */
    public function encrypt($pt, $encoding="") {
        if($pt === NULL){
            throw new ArgumentNullException("Input string pt is null.");
        }
        
        if($encoding != NULL){
            $pt = mb_convert_encoding($pt, $encoding);
        }
        // validate token
        $this->validateToken();
        
        $k = $this->cache_ks->getEnckeyByVersion($this->kmc->getMajorKeyVersion());
        
        $this->check_key_status_forEncryption($k);
        $this->log->info("Weak encrypt with key version:".$k->getVersion());
        
        $ct = null;
        try{
            $ct = $k->encrypt($pt);

            $this->reporter->insertStatReport(StatisticType::ENCCNT);
        } catch (\Throwable $e){
            $this->reporter->insertStatReport(StatisticType::ENCERRCNT);
            throw $e;
        }
        return base64_encode($ct);
    }

    private function check_key_status_forEncryption($key, $isEncryption=TRUE){
        if($key == null){
            if($isEncryption){
                $this->log->critical(TDEStatus::$SDK_HAS_NO_AVAILABLE_ENC_KEYS["message"]);
                // should not happen, probably due to some internal error or other issues
                $this->reporter->insertErrReport(
                    TDEStatus::$SDK_HAS_NO_AVAILABLE_ENC_KEYS["code"],
                    TDEStatus::$SDK_HAS_NO_AVAILABLE_ENC_KEYS["message"],
                    EMPTYSTR, 
                    MSG_LEVEL::SEVER);
                $this->reporter->insertStatReport(StatisticType::ENCERRCNT);
                throw new NoValidKeyException(TDEStatus::$SDK_HAS_NO_AVAILABLE_ENC_KEYS["message"]);
            } else {
                $this->log->critical(TDEStatus::$SDK_HAS_NO_AVAILABLE_SIGN_KEYS["message"]);
                // should not happen, probably due to some internal error or other issues
                $this->reporter->insertErrReport(
                    TDEStatus::$SDK_HAS_NO_AVAILABLE_SIGN_KEYS["code"],
                    TDEStatus::$SDK_HAS_NO_AVAILABLE_SIGN_KEYS["message"],
                    EMPTYSTR,
                    MSG_LEVEL::SEVER);
                $this->reporter->insertStatReport(StatisticType::SIGNERRCNT);
                throw new NoValidKeyException(TDEStatus::$SDK_HAS_NO_AVAILABLE_ENC_KEYS["message"]);
            }
        }
        // encrypt only for ACTIVE key
        if($key->getKeyStatus() != KEY_STATUS::ACTIVE){
            $this->log->critical(TDEStatus::$SDK_OPERATE_WITH_INACTIVE_KEYS["message"]);
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_OPERATE_WITH_INACTIVE_KEYS["code"],
                TDEStatus::$SDK_OPERATE_WITH_INACTIVE_KEYS["message"],
                EMPTYSTR, 
                MSG_LEVEL::ERROR);
            if($isEncryption){
                $this->reporter->insertStatReport(StatisticType::ENCERRCNT);
            }else{
                $this->reporter->insertStatReport(StatisticType::SIGNERRCNT);
            }
            throw new InvalidKeyException(TDEStatus::$SDK_OPERATE_WITH_INACTIVE_KEYS["message"]);
        }
        
        if($key->getKeyUsage() == KEY_USAGE::N || $key->getKeyUsage() == KEY_USAGE::D){
            if($isEncryption){
                $this->reporter->insertStatReport(StatisticType::ENCERRCNT);
            }else {
                $this->reporter->insertStatReport(StatisticType::SIGNERRCNT);
            }
            throw new InvalidKeyPermission("Key Permission Invalid.");
        }
        
        // check key timestamp, millis
        $now = date_timestamp_get(new \DateTime()) * 1000;
        if($key->getExpiredTime()<$now){
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_OPERATE_WITH_EXPIRED_KEYS["code"],
                TDEStatus::$SDK_OPERATE_WITH_EXPIRED_KEYS["message"],
                EMPTYSTR, 
                MSG_LEVEL::WARN);
            $this->log->info(TDEStatus::$SDK_OPERATE_WITH_EXPIRED_KEYS["message"]);
        }
    }
    
    /* Decrypt data
     *
     * @param string $ct
     *
     * @return string
     */
    public function decrypt($ct, $encoding="") {
        if($ct == NULL){
            throw new ArgumentNullException("Input cipher string base64ct is NULL.");
        }
        
        $ct = base64_decode($ct);
        // validate token
        $this->validateToken();
        // check cipher and handle exception
        $cipherResult = $this->getCipherResult($ct);

        // MQ handle for different cases
        if($cipherResult->status === ResultType::UnDecryptable){
            $min_len = $cipherResult->isStrong ? min(strlen($ct), Constants::STRONG_HDR_LEN) : min(strlen($ct), Constants::WEAK_HDR_LEN);
            $header  = base64_encode(substr($ct, 0, $min_len));
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_HAS_NO_CORRESPONDING_DEC_KEYS["code"],
                TDEStatus::$SDK_HAS_NO_CORRESPONDING_DEC_KEYS["message"].$header,
                EMPTYSTR, 
                MSG_LEVEL::SEVER);
            $this->reporter->insertStatReport(StatisticType::DECERRCNT);
            throw new NoValidKeyException(TDEStatus::$SDK_HAS_NO_CORRESPONDING_DEC_KEYS["message"].$header);
        } elseif($cipherResult->status === ResultType::Feasible){
            $this->log->info("Feasible case: KMS client needs to fetch keys from KMS.");
            $this->reporter->insertEventReport(TDEStatus::$SDK_TRIGGER_ROTATED_KEY_FETCH["code"], TDEStatus::$SDK_TRIGGER_ROTATED_KEY_FETCH["message"]);
            // fetch keys from KMS
            // blocking call!!
            $this->kmc->fetchMKeys(0);
            self::getClientCache()->set($this->josBaseInfo->getAccessToken(), $this);
            if($this->cache_ks->hasFutureKeyID($cipherResult->keyid)){
                $min_len = $cipherResult->isStrong ? min(strlen($ct), Constants::STRONG_HDR_LEN) : min(strlen($ct), Constants::WEAK_HDR_LEN);
                $header  = base64_encode(substr($ct, 0, $min_len));
                $this->reporter->insertErrReport(
                    TDEStatus::$SDK_FAILS_TO_FETCH_UPDATED_KEYS["code"],
                    TDEStatus::$SDK_FAILS_TO_FETCH_UPDATED_KEYS["message"].$header,
                    EMPTYSTR, 
                    MSG_LEVEL::SEVER);
                $this->reporter->insertStatReport(StatisticType::DECERRCNT);
                throw new NoValidKeyException(TDEStatus::$SDK_FAILS_TO_FETCH_UPDATED_KEYS["message"].$header);
            }
        } elseif ($cipherResult->status === ResultType::Malformed){
            $this->reporter->insertStatReport(StatisticType::DECERRCNT);
            // fetch available ciphertext
            $corrpted_cipher = "(NULL)";
            if($ct !== NULL){
                $min_len = min(strlen($ct), Constants::WEAK_HDR_LEN);
                $corrpted_cipher = $min_len === 0 ? "(EMPTY)" : base64_encode(substr($ct, 0, $min_len));
            }
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_HAS_CORRUPTED_CIPHER["code"],
                TDEStatus::$SDK_HAS_CORRUPTED_CIPHER["message"].$corrpted_cipher,
                EMPTYSTR, 
                MSG_LEVEL::SEVER);
            throw new MalformedException(TDEStatus::$SDK_HAS_CORRUPTED_CIPHER["message"].$corrpted_cipher);
        }
        // already double check by getCipherResult()
        $k = $this->cache_ks->searchDeckey($cipherResult->keyid);
        $this->log->info("Decrypt with key version:".$k->getVersion());
        // check key status
        $this->check_key_status_forDecryption($k);
        $pt = NULL;
        try{
            $pt = $cipherResult->isStrong ? $k->strong_decrypt($ct):$k->decrypt($ct);
            $this->reporter->insertStatReport(StatisticType::DECCNT);
        } catch (\Throwable $e){
            $this->reporter->insertStatReport(StatisticType::DECERRCNT);
            throw $e;
        }
        
        if($encoding != NULL){
            $pt = mb_convert_encoding($pt, $encoding);
        }
        
        return $pt;
    }
    
    private function check_key_status_forDecryption($key, $isDecryption=TRUE){
        // check if it's revoked
        if($key->getKeyStatus() === KEY_STATUS::REVOKED){
            // due to key rotation, decryption can use both active/suspend key but not for revoked one
            $this->log->critical(TDEStatus::$SDK_OPERATE_WITH_INACTIVE_KEYS["message"]);
            $this->reporter.insertErrReport(
                TDEStatus::$SDK_OPERATE_WITH_INACTIVE_KEYS["code"],
                TDEStatus::$SDK_OPERATE_WITH_INACTIVE_KEYS["message"],
                EMPTYSTR,
                MSG_LEVEL::SEVER);
            if($isDecryption){
                $this->reporter->insertStatReport(StatisticType::DECERRCNT);
            } else {
                $this->reporter->insertStatReport(StatisticType::VERIFYERRCNT);
            }
            throw new InvalidKeyException(TDEStatus::$SDK_OPERATE_WITH_INACTIVE_KEYS["message"]);
        }
        
        if($key->getKeyUsage() === KEY_USAGE::N || $key->getKeyUsage() === KEY_USAGE::E){
            if($isDecryption){
                $this->reporter->insertStatReport(StatisticType::DECERRCNT);
            }else {
                $this->reporter->insertStatReport(StatisticType::VERIFYERRCNT);
            }
            throw new InvalidKeyPermission("Key Permission Invalid.");
        }
        
        // check key timestamp
        $now = date_timestamp_get(new \DateTime()) * 1000;
        if($key->getExpiredTime()<$now){
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_OPERATE_WITH_EXPIRED_KEYS["code"],
                TDEStatus::$SDK_OPERATE_WITH_EXPIRED_KEYS["message"],
                EMPTYSTR,
                MSG_LEVEL::WARN);
            $this->log->info(TDEStatus::$SDK_OPERATE_WITH_EXPIRED_KEYS["message"]);
        }
    }
    
    public function isEncryptionBytesData($ct)
    {
        try {
            $ctype_ = unpack("C", substr($ct, 0, Constants::CIPHER_TYPE_LEN));
            $ctype = $ctype_[1];
            $isStrong = false;
            if($ctype == Constants::CIPHER_TYPE_LARGE ||
                $ctype == Constants::CIPHER_TYPE_REGULAR){
                $isStrong = TRUE;
            } elseif ($ctype !== Constants::CIPHER_TYPE_WEAK) {
                return false;
            }
            $mkIdx = $this->extractKeyId($ct, $isStrong);
            if (isset($mkIdx)) {
                return true;
            }
        } catch (\Exception $e) {
            //do nothing
        }
        return false;
    }

    public function isEncryptionStringData($ct)
    {
        try {
            $ct = base64_decode($ct);
            return $this->isEncryptionBytesData($ct);
        } catch (\Exception $e) {
            //do nothing
        }
        return false;
    }
    /* Indicate whether the cipher can be decrypted or not, and return CIPHER_ST_XXX
     *
     * @param string $ct
     *
     * @return constant
     */
    public function isDecryptable($ct) {
        try{
            $ct = base64_decode($ct);
            $ctype_ = unpack("C", substr($ct, 0, Constants::CIPHER_TYPE_LEN));
            $ctype = $ctype_[1];
            $isStrong = FALSE;
            if($ctype == Constants::CIPHER_TYPE_LARGE ||
                $ctype == Constants::CIPHER_TYPE_REGULAR){
                $isStrong = TRUE;
            }
            $mkIdx = $this->extractKeyId($ct, $isStrong);
            if($mkIdx === NULL){
                return ResultType::Malformed;
            }
            if($this->cache_ks->searchDeckey($mkIdx) !== NULL){
                return ResultType::Decryptable;
            }elseif ($this->cache_ks->hasFutureKeyID($mkIdx)){
                return ResultType::Feasible;
            }else{
                return ResultType::UnDecryptable;
            }
        } catch (\Exception $e){
            return ResultType::Malformed;
        }
    }
    
    /* Get given cipher information
     *
     * @param string ct
     *
     * @return CipherResult
     */
    public function getCipherResult($ct) {
        try{
            $ctype_ = unpack("C", substr($ct, 0, Constants::ALGO_TYPE_LEN));
            $ctype = $ctype_[1];
            // for weak version
            $flag = FALSE;
            
            // MalformedException will be thrown if ctype not matched any of cipher type
            if(CipherType::fromValue($ctype) === CipherType::LARGE||
                CipherType::fromValue($ctype) === CipherType::REGULAR){
                $flag = TRUE;
            }
            
            $mkIdx = $this->extractKeyId($ct, $flag);
            
            if($mkIdx === NULL){
                return new CipherResult(ResultType::Malformed, NULL, FALSE);
            }
            if($this->cache_ks->searchDeckey($mkIdx) !== NULL){
                return new CipherResult(ResultType::Decryptable, $mkIdx, $flag);
            }elseif ($this->cache_ks->hasFutureKeyID($mkIdx)){
                return new CipherResult(ResultType::Feasible, $mkIdx, $flag);
            }else{
                return new CipherResult(ResultType::UnDecryptable, $mkIdx, $flag);
            }
        } catch (\Exception $e){
            // format error or other error
            return new CipherResult(ResultType::Malformed, NULL, FALSE);
        }
    }
    
    private function extractKeyId($ct, $isStrong){
        $offset = 0;
        $eid = NULL;
        // skip ciphertext type
        $offset += 1;
        if($isStrong){
            $eidLen_ = unpack("n", substr($ct, $offset, 2));
            $eidLen = $eidLen_[1];
            $offset += 2;
            // length checking, not enough space  
            if(strlen($ct)-3 < $eidLen){
                return NULL;
            }
            $eid = substr($ct, $offset, $eidLen);
        }else{
            // skip algorithm
            $offset += 1;
            // length checking, not enough space
            if(strlen($ct)-2 < Constants::DEFAULT_KEYID_LEN){
                return NULL;
            }
            $eid = substr($ct, $offset, Constants::DEFAULT_KEYID_LEN);
        }
        return $eid;
    }
    
    /** sign data 
     * @param string $input
     * 
     * @return string singed data
     */
    public function sign($input){
        // validate token first
        $this->validateToken();
        
        $k = $this->cache_ks->getEncKeyByVersion($this->kmc->getMajorKeyVersion());
        
        $this->check_key_status_forEncryption($k, FALSE);
        $this->log->info("Signing with key version:".$k->getVersion());
        
        $sigData = null;
        try{
            $sigData = $k->sign($input);
            $this->reporter->insertStatReport(StatisticType::SIGNCNT);
        } catch (\Throwable $e){
            $this->reporter->insertStatReport(StatisticType::SIGNERRCNT);
            throw $e;
        }
        
        return $sigData;
    }
    
    public function verify($input, $sig){
        $sig_decoded = base64_decode($sig);
        
        if(strlen($sig_decoded) <= Constants::DEFAULT_KEYID_LEN + Constants::DEFAULT_SEED_LEN){
            $this->reporter->insertStatReport(StatisticType::VERIFYERRCNT);
            throw new MalformedException("Corrupted signature with illegal length.");
        }
        
        $keyid = substr($sig_decoded, 0, Constants::DEFAULT_KEYID_LEN);
        if($this->cache_ks->hasFutureKeyID($keyid)){
            $this->log->info("Feasible case: KMS client needs to fetch keys from KMS.");
            $this->reporter->insertEventReport(TDEStatus::$SDK_TRIGGER_ROTATED_KEY_FETCH["code"], TDEStatus::$SDK_TRIGGER_ROTATED_KEY_FETCH["message"]);
            // fetch keys from KMS
            // blocking call!!
            $this->kmc->fetchMKeys(0);
            if($this->cache_ks->hasFutureKeyID($keyid)){
                $this->reporter->insertErrReport(
                    TDEStatus::$SDK_FAILS_TO_FETCH_UPDATED_KEYS["code"],
                    TDEStatus::$SDK_FAILS_TO_FETCH_UPDATED_KEYS["message"].base64_encode($keyid),
                    EMPTYSTR,
                    MSG_LEVEL::SEVER);
                $this->reporter->insertStatReport(StatisticType::VERIFYERRCNT);
                throw new NoValidKeyException(TDEStatus::$SDK_FAILS_TO_FETCH_UPDATED_KEYS["message"].base64_encode($keyid));
            }
        }
        
        $k = $this->cache_ks->searchDeckey($keyid);
        
        if($k == null){
            $errMsg = TDEStatus::$SDK_HAS_NO_CORRESPONDING_VERIFY_KEYS["message"].base64_encode($keyid);
            $this->log->critical($errMsg);
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_HAS_NO_CORRESPONDING_VERIFY_KEYS["code"],
                $errMsg, 
                EMPTYSTR, 
                MSG_LEVEL::SEVER);
            $this->reporter->insertStatReport(StatisticType::VERIFYERRCNT);
            throw new NoValidKeyException($errMsg);
        }
        
        $this->check_key_status_forDecryption($k, FALSE);
        
        $this->log->info("Verifying with key version:".$k->getVersion());
        
        $ret = FALSE;
        
        try{
            $ret = $k->verify($input, $sig);
            $this->reporter->insertStatReport(StatisticType::VERIFYCNT);
        } catch (\Throwable $e){
            $this->reporter->insertStatReport(StatisticType::VERIFYERRCNT);
            throw $e;
        }
        
        return $ret;
    }
    
    public function obtainWildCardKeyWordIndex($spt){
        $spt = IndexCalculationHelper::formatPlaintext($spt);
        
        $k0 = $this->cache_ks->getEncKeyByVersion(0);
        if($k0 == null){
            $this->log->critical(TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"]);
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["code"],
                TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"],
                EMPTYSTR, 
                MSG_LEVEL::SEVER);
            throw new NoValidKeyException(TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"]);
        }
        $key = KeyEncryption::wrap($k0, self::keyWordSalt);
        $nonce = substr($key, 0, 24);
        
        // prepare to encrypt with salsa
        $m = FieldElement::fromString($spt);
        $k = FieldElement::fromString($key);
        $n = FieldElement::fromString($nonce);
        
        $ret = Salsa20::instance()->crypto_stream_xor($m,count($m), $n, $k);
        
        return $ret->toHex();
    }
    
    public function calculateWildCardKeyWord($queryW, $asciiCharPrefixNumber = 0, $nonAsciiCharPrefixNumber = 0){
        $queryW = IndexCalculationHelper::generateWildcardKeyword($queryW, $asciiCharPrefixNumber, $nonAsciiCharPrefixNumber);
        $queryW = IndexCalculationHelper::formatQueryKeyword($queryW);
        
        $k0 = $this->cache_ks->getEncKeyByVersion(0);
        if($k0 == null){
            $this->log->critical(TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"]);
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["code"],
                TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"],
                EMPTYSTR,
                MSG_LEVEL::SEVER);
            throw new NoValidKeyException(TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"]);
        }
        $key = KeyEncryption::wrap($k0, self::keyWordSalt);
        $nonce = substr($key, 0, 24);
        
        // prepare to encrypt with salsa
        $m = FieldElement::fromString($queryW);
        $k = FieldElement::fromString($key);
        $n = FieldElement::fromString($nonce);
        
        $ret = Salsa20::instance()->crypto_stream_xor($m,count($m), $n, $k);
        
        $skip = 0;
        for($i = 0; $i < mb_strlen($queryW); $i ++){
            if(mb_substr($queryW, $i, 1) == IndexCalculationHelper::ASCII_PLACEHOLDER){
                $skip ++;
            } else{
                break;
            }
        }
        
        if($skip == mb_strlen($queryW)){
            throw new \Exception("keyword format does not match!");
        }
        
        return substr($ret->toHex(), $skip * 2);
    }
    
    public function obtainKeyWordIndex($spt){
        $k0 = $this->cache_ks->getEncKeyByVersion(0);
        if($k0 == null){
            $this->log->critical(TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"]);
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["code"],
                TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"],
                EMPTYSTR,
                MSG_LEVEL::SEVER);
            throw new NoValidKeyException(TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"]);
        }
        $key = KeyEncryption::wrap($k0, self::keyWordSalt);
        $nonce = substr($key, 0, 24);
        $k = FieldElement::fromString($key);
        $n = FieldElement::fromString($nonce);
        
        $ret = "";
        $mblength = mb_strlen($spt);
        for($i = 0; $i < $mblength; $i ++){
            $subW = mb_substr($spt, $i, 1);
            $len = strlen($subW);
            if($len < 4){
                $padOffset_ = unpack("c*", $subW);
                $padOffset = abs($padOffset_[1]) % (strlen($key)-8);
                $padSize = 4 - $len;
                $subW .= substr($key, $padOffset+4, $padSize);
            }
            $m = FieldElement::fromString($subW);
            
            $ct = Salsa20::instance()->crypto_stream_xor($m, count($m), $n, $k);
            $ret .= str_replace("==", "", $ct->toBase64());
        }
        
        return $ret;
    }
    
    public function calculateKeyWord($queryW) {
        $k0 = $this->cache_ks->getEncKeyByVersion(0);
        if($k0 == null){
            $this->log->critical(TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"]);
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["code"],
                TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"],
                EMPTYSTR,
                MSG_LEVEL::SEVER);
            throw new NoValidKeyException(TDEStatus::$SDK_HAS_NO_AVAILABLE_KEYS["message"]);
        }
        $key = KeyEncryption::wrap($k0, self::keyWordSalt);
        $nonce = substr($key, 0, 24);
        $k = FieldElement::fromString($key);
        $n = FieldElement::fromString($nonce);
        
        $ret = "";
        $mblength = mb_strlen($queryW);
        for($i = 0; $i < $mblength; $i ++){
            $subW = mb_substr($queryW, $i, 1);
            $len = strlen($subW);
            if($len < 4){
                $padOffset_ = unpack("c*", $subW);
                $padOffset = abs($padOffset_[1]) % (strlen($key)-8);
                $padSize = 4 - $len;
                $subW .= substr($key, $padOffset+4, $padSize);
            }
            $m = FieldElement::fromString($subW);
            
            $ct = Salsa20::instance()->crypto_stream_xor($m, count($m), $n, $k);
            $ret .= str_replace("==", "", $ct->toBase64());
        }
        
        return $ret;
    }
    
    /* Get current service identifier
     *
     * @return string
     */
    public function getServiceIdentifier() {
        return $this->token == null ? "Unknown Service" : $this->token->get_service_name();
    }
    
    /* Get current stat results
     *
     * @return array
     */
    public function getStatistics($reset=false) {
        if ($this->statistic) {
            $stat = $this->statistic;
            if ($reset) {
                for ($i = 0; $i < array_sum($stat); $i++) {
                    $this->statistic[$i] = 0;
                }
            }
            return $stat;
        }
    }

    public function stat($statType)
    {
        ++ $this->statistic[StatisticType::type($statType)];
    }
    
    /* Get sdk version
     *
     * @return string
     */
    public static function getSdkVer() {
        return self::version;
    }
    
    /* Get current token identifier
     *
     * @return string
     */
    public function getTokenIdentifier() {
        return $this->token == null ? "Unknown TID" : $this->token->get_id();
    }
    
    /* Get token origin TOKEN_ORIGIN_XXX
     *
     * @return constant
     */
    public function getTokenOrigin() {
        return $this->token == null ? "Unknown OriginType" : $this->token->getOriginType();
    }

    /* Indicate whether encryption/decryption keys are ready in memory
     *
     * @return bool
     */
    public function isKeyChainReady() {
        return $this->kmc->isKeyChainReady();
    }
    

    /* For internal test only!
     * Clear key cache
     *
     * @return void
     */
    public function manuallyDeletesKeys() {
        $this->cache_ks->removeAllMKeys();
        $this->kmc->resetKeyChainFlag();
    }
    
    private function validateToken(){
        if(!$this->token->check_effective()){
            $this->log->critical("Please use this token after: ".$this->token->getEffectiveDate());
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_USE_INEFFECTIVE_TOKEN["code"],
                TDEStatus::$SDK_USE_INEFFECTIVE_TOKEN["message"],
                EMPTYSTR, 
                MSG_LEVEL::SEVER);
            throw new InvalidTokenException(TDEStatus::$SDK_USE_INEFFECTIVE_TOKEN["message"]);
        }
        $state = $this->token->check_expired(Constants::TOKEN_EXP_DELTA);
        if($state === STATE::EXPIRED){
            $this->log->critical("Please apply for a new token online. The current token is already expired for more than 30 days.");
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_USE_HARD_EXPIRED_TOKEN["code"],
                TDEStatus::$SDK_USE_HARD_EXPIRED_TOKEN["message"],
                EMPTYSTR, 
                MSG_LEVEL::SEVER);
            throw new InvalidTokenException(TDEStatus::$SDK_USE_HARD_EXPIRED_TOKEN["message"]);
        } elseif ($state === STATE::EXPIREWARNING){
            $this->log->warning("Token is already expired but less than 30 days. We still allow it to be operated. Token expired date: ".$this->token->getExpiredDate());
            $this->reporter->insertErrReport(
                TDEStatus::$SDK_USE_SOFT_EXPIRED_TOKEN["code"],
                TDEStatus::$SDK_USE_SOFT_EXPIRED_TOKEN["message"],
                EMPTYSTR, 
                MSG_LEVEL::WARN);
        }
    }

    public static function generateCustomerToken($customerUserId, $appKey)
    {
        return '_' . $customerUserId . '_' . $appKey;
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        return json_encode(get_object_vars($this));
    }
    public function checkNullParam($report=false)
    {
        $objectVars = get_object_vars($this);
        foreach ($objectVars as $key => $value) {
            if (!isset($value)) {
                if ($report) {
                    $msg = $key . " is not set in tdeClient, tdeClient to json=" . $this->toJson();
                    if (isset($this->log)) {
                        $this->log->error($msg);
                    }
                    if (isset($this->reporter)) {
                        $this->reporter->insertErrReport(
                            TDEStatus::$SDK_HAS_PROPERTY_NOT_SET["code"],
                            TDEStatus::$SDK_HAS_PROPERTY_NOT_SET["message"] . $msg,
                            EMPTYSTR,
                            MSG_LEVEL::ERROR);
                    }
                }
                return $key;
            }
        }
        return null;
    }
}

// Parsed cipher information
class CipherResult {
    public $keyid;
    public $status;
    public $isStrong;
    
    public function __construct($resultType, $keyID, $isStrong){
        $this->keyid = $keyID;
        $this->status = $resultType;
        $this->isStrong = $isStrong;
    }
}

abstract class ResultType {
    const Decryptable = 0;
    const Malformed = 1;
    const Feasible =2;
    const UnDecryptable = 3;
}

abstract class CipherType{
    const WEAK = 0;
    const REGULAR = 1;
    const LARGE = 2;
    
    public static function fromValue($code){
        switch($code) {
            case 0:
                return CipherType::WEAK;
            case 1:
                return CipherType::REGULAR;
            case 2:
                return CipherType::LARGE;
            default:
                throw new MalformedException("unknown cipher type.");
        }
    }
}

abstract class StatisticType{
    const ENCCNT = 0;
    const DECCNT = 1;
    const ENCERRCNT =2;
    const DECERRCNT =3;
    const SIGNCNT = 4;
    const VERIFYCNT = 5;
    const SIGNERRCNT = 6;
    const VERIFYERRCNT = 7;

    /**
     * @param int $type
     * @return int
     * @throws \Exception
     */
    public static function type($type)
    {
        if ($type < 0 || $type > 7) {
            throw new \Exception('wrong statistic type, expected type between 0 and 7, but actual type is '.$type);
        }
        return $type;
    }

}
