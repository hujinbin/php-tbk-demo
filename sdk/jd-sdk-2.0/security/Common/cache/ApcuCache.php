<?php


namespace ACES\Common\cache;


use ACES\TDEClient;

class ApcuCache extends iCache
{
    private $isEnable = false;
    function __construct()
    {
        if (extension_loaded("apcu") && ini_get('apc.enabled')==1){
            $this->isEnable = true;
        }
    }

    public function get($key)
    {
        if($this->isEnable){
            $key = md5($key);
            return apcu_fetch(self::$CACHE_PREFIX . $key);
        }
    }

    /**
     * @param $key
     * @param $var TDEClient
     */
    public function set($key,$var)
    {
        if($this->isEnable){
            if ($var && !$var->checkNullParam()) {
                $key = md5($key);
                apcu_store(self::$CACHE_PREFIX . $key, $var, self::$CACHE_TTL);
            }
        }
    }

    public function delete($key)
    {
        if($this->isEnable){
            $key = md5($key);
            apcu_delete(self::$CACHE_PREFIX . $key);
        }
    }

    public function cacheable()
    {
        return $this->isEnable;
    }

}