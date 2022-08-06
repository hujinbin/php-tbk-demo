<?php
namespace ACES\Common;

abstract class KStoreType {
    const ENC_STORE = 0;
    const DEC_STORE = 1;
}

final class CacheKeyStore {
    private $encKeyStore;
    private $decKeyStore;
    private $futureKeyIds;
    
    public function __construct() {
        $this->encKeyStore = array();
        $this->decKeyStore = array();
        $this->futureKeyIds = array();
    }

    /**
     * Search master key by index
     * @param $mkIndex
     * @return Mkey|null
     */
    public function searchDeckey($mkIndex){
        if(array_key_exists(base64_encode($mkIndex), $this->decKeyStore)){
            return $this->decKeyStore[base64_encode($mkIndex)];
        }else{
            return NULL;
        }
    }
    
    /* Return number of specified key store type
     * @param KeyStoreType $kstoreType
     *
     * @return int
     */
    public function numOfKeys($kstoreType) {
        if($kstoreType == KStoreType::ENC_STORE){
            return sizeof($this->encKeyStore);
        }else{
            return sizeof($this->decKeyStore);
        }
    }
    
    /* Get encrypt keys by version
     * @param int $keyVersion
     *
     * @return Mkey
     */
    public function getEncKeyByVersion($keyVersion) {
        foreach ($this->encKeyStore as $key => $value ){
            if($value->getVersion() == $keyVersion)
                return $value;
        }
        return null;
    }
    
    /* Update keys in keystore cache
     * @param string $b64Index
     * @param Mkey $mkey
     * @KStoreType $kstoreType
     *
     * @return void
     */
    public function updateKey($b64Index, $mkey, $kstoreType) {
        if($kstoreType == KStoreType::ENC_STORE){
            // update it when key is new to cache or status has been changed
            if(!array_key_exists($b64Index, $this->encKeyStore)){
                $this->encKeyStore[$b64Index] = $mkey;
            }else{
                if($this->encKeyStore[$b64Index]->getKeyStatus() != $mkey->getKeyStatus()){
                    $this->encKeyStore[$b64Index] = $mkey;
                }
            }
        }else{
            if(!array_key_exists($b64Index, $this->decKeyStore)){
                $this->decKeyStore[$b64Index] = $mkey;
            }else{
                if($this->decKeyStore[$b64Index]->getKeyStatus() != $mkey->getKeyStatus()){
                    $this->decKeyStore[$b64Index] = $mkey;
                }
            }
        }
    }
    
    /* Remove all keys in keystore cache
     *
     * @return void
     */
    public function removeAllMKeys() {
        $this->encKeyStore = array();
        $this->decKeyStore = array();
    }
    
    /* Remove keys via list
     * @param array $target
     * @param KStoreType $kstoreType
     *
     * @return void
     */
    public function removeKeysViaList($target, $kstoreType) {
        foreach ($target as $t){
            if($kstoreType == KStoreType::ENC_STORE){
                unset($this->encKeyStore[$t]);
            }else{
                unset($this->decKeyStore[$t]);
            }
        }
    }
    
    /* Get key id list by key store type
     * @param KStoreType $kstoreType
     *
     * @return array
     */
    public function getKeyIDList($kstoreType) {
        if($kstoreType == KStoreType::ENC_STORE){
            return array_keys($this->encKeyStore);
        }else{
            return array_keys($this->decKeyStore);
        }
    }
    
    /* Clear futurekeyids
     *
     *  @return void
     */
    public function resetFutureKeyIDs() {
        $this->futureKeyIds = array();
    }
    
    /* Update local futurekeyids cache
     * @param string $service
     * @param int maxVer
     *
     * @return void
     */
    public function updateFutureKeyIDs($service, $maxVer) {
        ;
    }
    
    /* If local futurekeyids cache contains specified keyid
     * @param byte[] $keyid
     *
     * @return bool
     */
    public function hasFutureKeyID($keyid) {
        return array_key_exists(base64_encode($keyid), $this->futureKeyIds);
    }
}
