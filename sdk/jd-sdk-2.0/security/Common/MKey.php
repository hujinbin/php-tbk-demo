<?php
namespace ACES\Common;

use ACES\Common\Exception as Ex;
use ACES\Common\Exception\MalformedException;
use Common\Exception\SignException;

class MKey
{
    const IV_SIZE = 16;
    const RANDOM_SIZE = 4;
    const KEY_ID_LEN = 16;
    //1024 * 1024
    const MEGABYTE = 1048576;
    
    private $id;
    private $key;
    private $ver;
    private $service;
    private $keyUsage;
    private $keyType;
    private $skey;
    
    private $svkey;     // for sign & verify
    
    private $isValid;
    private $effective;
    private $expired;
    private $key_digest;
    private $keyStatus;
    
    public function __construct(
        $service, 
        $kid, 
        $key, 
        $kdigest, 
        $kver, 
        $effectiveTs, 
        $expTs, 
        $ktype, 
        $kusage, 
        $kstatus)
    {
        if(empty($kid) || empty($service))
            throw new MalformedException("ID and App fields cannot be null.");
        
        $this->service = $service;
        $this->id = $kid;
        $this->key = $key;
        
        if($kver < -1){
            throw new MalformedException("Invalid key version.");
        }
        $this->ver = $kver;
        
        $this->keyUsage = KEY_USAGE::fromValue($kusage);
        $this->keyStatus = KEY_STATUS::fromValue($kstatus);
        $this->keyType = KEY_TYPE::fromValue($ktype);
        
        $this->isValid = false;
        if(!empty($key)){
            $this->key = $key;
            $this->expired = $expTs;
            $this->effective = $effectiveTs;
            // TODO: due to cryptographic policy control, JDK only allow AES 128 bit
            $this->skey = substr($this->key, 0, 16);
            $this->svkey = $this->key;
        }
        
        $this->key_digest = $kdigest;
        $digest = base64_encode(hash(Constants::DEFAULT_CERTDIGEST_ALGO, $this->key, TRUE));
        
        if(strcmp($this->key_digest, $digest)==0){
            $this->isValid = TRUE;
        }
    }

    function __destruct()
    {

        // TODO - Insert your code here
    }
    
    public function encrypt($pt) 
    {
        $ct = KeyEncryption::encrypt($this, $pt);
        
        $ct = pack("C",Constants::CIPHER_TYPE_WEAK) .pack("C",Constants::ALGO_TYPE_AES_CBC_128) . $this->id . $ct;
        
        return $ct;
    }
    
    public function strong_encrypt($pt){
        $de = new DataEncryption();
        
        $data_cipher = $de->encrypt($pt);
        $key_cipher = KeyEncryption::wrap($this, $de->exportKey());
        
        $ct = pack("C", Constants::CIPHER_TYPE_REGULAR) . pack("n", strlen($this->id)) .
                $this->id . pack("C", Constants::ALGO_TYPE_AES_CBC_128) . pack("n", strlen($key_cipher)) . 
                $key_cipher . pack("C", Constants::ALGO_TYPE_AES_CBC_128) . pack("N", strlen($data_cipher)) . $data_cipher;
        
        return $ct;
    }
    
    public function strong_decrypt($ct){
        $offset = 0;
        $ctype_ = unpack("C", $ct[$offset]);
        $ctype = $ctype_[1];
        $offset += 1;
        if($ctype != Constants::CIPHER_TYPE_REGULAR){
            throw new MalformedException("Unmatched CipherText Type.");
        }
        $eidLen_ = unpack("n", substr($ct, $offset, 2));
        $eidLen = $eidLen_[1];
        $offset += 2;
        if($eidLen !== Constants::DEFAULT_KEYID_LEN){
            throw new MalformedException("Corrupted ciphertext header with illegal key id length.");
        }
        
        $eid = substr($ct, $offset, $eidLen);
        $offset += $eidLen;
        if ($this->id != $eid){
            throw new MalformedException("Unmatched MKey ID.");
        }
        $atype_ = unpack("C", substr($ct, $offset, 1));
        $atype = $atype_[1];
        $offset += 1;
        if($atype != Constants::ALGO_TYPE_AES_CBC_128){
            throw new MalformedException("Unmatched Key Encryption Algorithm Type:$atype");
        }
        $kcipherLen_ = unpack("n", substr($ct, $offset, 2));
        $kcipherLen = $kcipherLen_[1];
        $offset += 2;
        if($kcipherLen < Constants::DEFAULT_CIPHERBLK_LEN || $kcipherLen > strlen(substr($ct, $offset))){
            throw new MalformedException("Corrupted ciphertext header with illegal key cipher length.");
        }

        $kcipher = substr($ct, $offset, $kcipherLen);
        $offset += $kcipherLen;
        $dkey = KeyEncryption::unwrap($this, $kcipher);
        $dtype_ = unpack("C", substr($ct, $offset, 1));
        $dtype = $dtype_[1];
        $offset += 1;
        if($dtype != Constants::ALGO_TYPE_AES_CBC_128){
            throw new MalformedException("Unmatched Data Encryption Algorithm Type:$dtype");
        }
        $dcipherLen_ = unpack("N", substr($ct, $offset, 4));
        $dcipherLen = $dcipherLen_[1];
        $offset += 4;

        if($dcipherLen != strlen(substr($ct, $offset))){
            throw new MalformedException("Corrupted ciphertext header with illegal data cipher length.");
        }
        
        $dcipher = substr($ct, $offset);
        
        $de = new DataEncryption($dkey);
        $pt = $de->decrypt($dcipher);
        
        return $pt;
        
    }
    
    public function decrypt($ct) 
    {
        $offset = 0;
        
        // Get and validate cipher type
        $ctype_ = unpack("C", substr($ct, $offset, Constants::CIPHER_TYPE_LEN));
        $ctype = $ctype_[1];
        $offset += Constants::CIPHER_TYPE_LEN;
        if($ctype != Constants::CIPHER_TYPE_WEAK)
            throw new Ex\MalformedException("Unmatch Encryption Algorithm Type: $ctype");
            
        // Get and validate algo type
        $atype_ = unpack("C", substr($ct, $offset, Constants::ALGO_TYPE_LEN));
        $atype = $atype_[1];
        $offset += Constants::ALGO_TYPE_LEN;
        if($atype != Constants::ALGO_TYPE_AES_CBC_128)
            throw new Ex\MalformedException("Unmatch Encryption Algorithm Type: $atype");
            
        $eid = substr($ct, $offset, Constants::DEFAULT_KEYID_LEN);
        $offset += Constants::DEFAULT_KEYID_LEN;
        if($eid != $this->id)
            throw new MalformedException("Unmatch MKey ID.");
        
        $ct_data = substr($ct, $offset);
        
        $pt = KeyEncryption::decrypt($this, $ct_data);
        
        return $pt;
    }
    
    public function sign($input){
        if($input === NULL){
            throw new MalformedException("Illegal input.");
        }
        
        if ($this->id == NULL || strlen($this->id) != Constants::DEFAULT_KEYID_LEN) {
            throw new MalformedException("Illegal Signing Key.");
        }
        
        $rsec = Crypto::secureRandom(Constants::DEFAULT_SEED_LEN);
        $data = $input.$rsec;
        $signedData = hash_hmac(Constants::DEFAULT_TOKEN_SIGN_ALGO, $data, $this->svkey, TRUE);
        
        if($signedData === FALSE)
            throw new SignException("Sign fail, algo:".Constants::DEFAULT_TOKEN_SIGN_ALGO);
        
        $ret = $this->id.$rsec.$signedData;
        
        return base64_encode($ret);
    }
    
    public function verify($input, $sig){
        if($input === NULL){
            throw new MalformedException("Illegal input.");
        }
        
        if($sig === NULL || strlen($sig) < Constants::DEFAULT_KEYID_LEN + Constants::DEFAULT_SEED_LEN){
            throw new MalformedException("Illegal Signature.");
        }
        // skip key id
        $offset = Constants::DEFAULT_KEYID_LEN;
        
        $sig = base64_decode($sig);
        
        $rsec = substr($sig, $offset, Constants::DEFAULT_SEED_LEN);
        $offset += Constants::DEFAULT_SEED_LEN;
        
        $carriedSig = substr($sig, $offset);
        
        $data = $input.$rsec;
        
        $signedData = hash_hmac(Constants::DEFAULT_TOKEN_SIGN_ALGO, $data, $this->svkey, TRUE);
        
        return $carriedSig == $signedData;
    }
    
    public function getKey() { return $this->skey; }
    
    public function getRawKey(){
        return $this->key;
    }
    
    public function isValid() {
        return $this->isValid;
    }
    
    public function getID() {
        return $this->id;
    }
    
    public function getEffectiveTime(){
        return $this->effective;
    }
    
    public function getKeyStatus(){
        return $this->keyStatus;
    }
    
    public function getVersion(){
        return $this->ver;
    }
    
    public function getKeyUsage(){
        return $this->keyUsage;
    }
    
    public function getExpiredTime(){
        return $this->expired;
    }
}

