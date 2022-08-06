<?php
namespace ACES\Common;

class MKData
{
    private $id;
    private $key_string;
    private $key_type;
    private $key_exp;
    private $key_effective;
    private $version;
    private $key_status;
    private $key_digest;
    
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKey_string()
    {
        return $this->key_string;
    }

    /**
     * @return string
     */
    public function getKey_type()
    {
        return $this->key_type;
    }

    /**
     * @return integer
     */
    public function getKey_exp()
    {
        return $this->key_exp;
    }

    /**
     * @return integer
     */
    public function getKey_effective()
    {
        return $this->key_effective;
    }

    /**
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return integer
     */
    public function getKey_status()
    {
        return $this->key_status;
    }

    /**
     * @return string
     */
    public function getKey_digest()
    {
        return $this->key_digest;
    }

    /**
     * 
     * @param string $id
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * 
     * @param string $keyString
     */
    public function setKeyString($keyString) {
        $this->key_string = $keyString;
    }
    
    /**
     * 
     * @param string $keyType
     */
    public function setKeyType($keyType) {
        $this->key_type = $keyType;
    }
    
    /**
     * 
     * @param integer $keyExp
     */
    public function setKeyExp($keyExp) {
        $this->key_exp =$keyExp;
    }
    
    /**
     * 
     * @param integer $keyEffective
     */
    public function setKeyEffective($keyEffective) {
        $this->key_effective = $keyEffective;
    }
    
    /**
     * 
     * @param integer $version
     */
    public function setVersion($version) {
        $this->version = $version;
    }
    
    /**
     * 
     * @param integer $keyStatus
     */
    public function setKeyStatus($keyStatus) {
        $this->key_status = $keyStatus;
    }
    
    /**
     * 
     * @param string $keyDigest
     */
    public function setKeyDigest($keyDigest){
        $this->key_digest = $keyDigest;
    }
    
    
}

