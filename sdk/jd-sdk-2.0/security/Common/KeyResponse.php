<?php
namespace ACES\Common;


class KeyResponse
{
    private $status_code;
//    private $status_message;
    private $errorMsg;
    private $tid;
    private $ts;
    private $enc_service;
    private $service_key_list;
    private $key_cache_disabled;
    private $key_backup_disabled;
    
    /**
     * 
     * @param integer|null $statusCode
     */
    public function setStatusCode($statusCode) {
        $this->status_code = $statusCode;
    }
    
    /**
     * 
     * @param string|null $message
     */
//    public function setStatusMessage($message) {
//        $this->status_message = $message;
//    }
    /**
     * @param string|null $errMsg
     */
    public function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;
    }
    /**
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }
    /**
     * 
     * @param string|null $tid
     */
    public function setTid($tid) {
        $this->tid = $tid;
    }
    
    /**
     * 
     * @param integer|null $ts
     */
    public function setTs($ts) {
        $this->ts = $ts;
    }
    
    /**
     * 
     * @param string|null $encService
     */
    public function setEncService($encService){
        $this->enc_service = $encService;
    }
    
    /**
     * 
     * @param ServiceKeyInfo[]|null $serviceKeyList
     */
    public function setServiceKeyList($serviceKeyList) {
        $this->service_key_list = $serviceKeyList;
    }
    
    /**
     * 
     * @param integer|null $keyCacheDisabled
     */
    public function setKeyCacheDisabled($keyCacheDisabled) {
        $this->key_cache_disabled = $keyCacheDisabled;
    }
    
    /**
     * 
     * @param integer|null $keyBackupDisabled
     */
    public function setKeyBackupDisabled($keyBackupDisabled) {
        $this->key_backup_disabled = $keyBackupDisabled;
    }
    
    /**
     * @return integer
     */
    public function getStatus_code()
    {
        return $this->status_code;
    }

    /**
     * @return string
     */
//    public function getStatus_message()
//    {
//        return $this->status_message;
//    }

    /**
     * @return string
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * @return integer
     */
    public function getTs()
    {
        return $this->ts;
    }

    /**
     * @return string
     */
    public function getEnc_service()
    {
        return $this->enc_service;
    }

    /**
     * @return ServiceKeyInfo[]
     */
    public function getService_key_list()
    {
        return $this->service_key_list;
    }

    /**
     * @return integer
     */
    public function getKey_cache_disabled()
    {
        return $this->key_cache_disabled;
    }

    /**
     * @return integer
     */
    public function getKey_backup_disabled()
    {
        return $this->key_backup_disabled;
    }

}

