<?php
namespace ACES\Common;

class KeyRequestData
{
    public $sdk_ver;
    public $ts;
    public $tid;
    
    public function __construct($tid, $major_sdk_ver){
        $this->sdk_ver = $major_sdk_ver;
        $this->ts = date_timestamp_get(new \DateTime());
        $this->tid = $tid;
    }

    public function getSdkVer()
    {
        return $this->sdk_ver;
    }

    public function getTid() {
        return $this->tid;
    }
    
    public function getTs() {
        return $this->ts;
    }

    public function setTs($ts){
        $this->ts = $ts;
    }
    
//    public function jsonSerialize()
//    {
//        $vars = get_object_vars($this);
//
//        return $vars;
//    }
    
}

