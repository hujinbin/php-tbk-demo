<?php
namespace ACES\Common;


class ServiceKeyInfo
{
    private $service;
    private $current_key_version;
    private $grant_usage;
    private $keys;
    
    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return integer
     */
    public function getCurrent_key_version()
    {
        return $this->current_key_version;
    }

    /**
     * @return string
     */
    public function getGrant_usage()
    {
        return $this->grant_usage;
    }

    /**
     * @return MKData[]
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * 
     * @param string $service
     */
    public function setService($service){
        $this->service = $service;
    }
    
    /**
     * 
     * @param integer $keyVersion
     */
    public function setCurrentKeyVersion($keyVersion) {
        $this->current_key_version = $keyVersion;
    }
    
    /**
     * 
     * @param string $grantUsage
     */
    public function setGrantUsage($grantUsage) {
       $this->grant_usage = $grantUsage;
    }
    
    /**
     * 
     * @param MKData[] $keys
     */
    public function setKeys($keys) {
        $this->keys = $keys;
    }
    
    
}

