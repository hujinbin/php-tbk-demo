<?php
/**
 * Copyright 2019 JD.COM
 * 
 * Data Encryption Utility Class
 *
 * <P> Data Chunk Encryption Implementation.
 *
 * @version 1.0
 */
namespace ACES\Common;

class DataEncryption
{
    private $iv;
    private $key;
    
    function __construct($key=NULL) 
    {
        $this->iv = Crypto::secureRandom(Crypto::CIPHER_IV_SIZE);
        
        if ($key == NULL)
            $this->key = Crypto::secureRandom(Crypto::CIPHER_KEY_SIZE);
        else
            $this->key =$key;
    }
    
    function __destruct() {}
    
    public function exportKey()
    {
        return $this->key;
    }
    
    public function exportIv(){
        return $this->iv;
    }
    
    public function encrypt($pt)
    {
        $ct_data = openssl_encrypt($pt, Crypto::CIPHER_METHOD_AES_128_CBC, $this->key, OPENSSL_RAW_DATA, $this->iv);
        $ct = $this->iv . $ct_data;
        
        return $ct;
    }

    public function decrypt($ct)
    {
        $this->iv = substr($ct, 0, Crypto::CIPHER_IV_SIZE);
        $ct_data = substr($ct, Crypto::CIPHER_IV_SIZE, strlen($ct) - Crypto::CIPHER_IV_SIZE);
        $pt = openssl_decrypt($ct_data, Crypto::CIPHER_METHOD_AES_128_CBC, $this->key, OPENSSL_RAW_DATA, $this->iv);
        if($pt === FALSE)
            echo "\ndecrypt fail.";
        return $pt;
    }
}

