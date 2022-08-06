<?php


namespace ACES\Common\cache;


use ACES\TDEClient;

abstract class iCache
{
    protected static $CACHE_PREFIX = 'jd_jos_aces_';
    protected static $CACHE_TTL = 600;

    /**
     * @param $key
     * @return TDEClient
     */
    public abstract function get($key);

    public abstract function set($key,$var);

    public abstract function delete($key);

    public function cacheable(){
        return false;
    }
}