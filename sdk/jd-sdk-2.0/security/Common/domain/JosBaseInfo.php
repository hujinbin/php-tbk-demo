<?php


namespace ACES\Common\domain;


class JosBaseInfo
{
    private $appKey;
    private $appSecret;
    private $accessToken;
    private $timestamp;
    private $v = "2.0";
    private $serverUrl;
    /**
     * JosBaseInfo constructor.
     * @param $method
     * @param $app_key
     * @param $access_token
     */
    public function __construct($appKey, $appSecret, $access_token, $serverUrl)
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->accessToken = $access_token;
        $this->serverUrl = $serverUrl;
    }


    /**
     * @return string
     */
    public function getAppKey()
    {
        return $this->appKey;
    }

    /**
     * @return mixed
     */
    public function getAppSecret()
    {
        return $this->appSecret;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getV()
    {
        return $this->v;
    }

    /**
     * @return string
     */
    public function getServerUrl()
    {
        return $this->serverUrl;
    }

    /**
     * @param bool $init
     * @return string
     */
    public function getTimestamp($init = true)
    {
        if ($init || !isset($this->timestamp)) {
            //TODO delete date_default_timezone_set
            date_default_timezone_set("prc");
            $this->timestamp = date('Y-m-d H:i:s');
        }
        return $this->timestamp;
    }

    /**
     * @param \ACES\Common\ProduceRequest $josReuqest
     * @return array
     */
    public function getFormParams($josReuqest)
    {
        $request = array();
        $request['360buy_param_json'] = $josReuqest->to360buyParamJson();
        $request['app_key'] = $this->getAppKey();
        $request['access_token'] = $this->getAccessToken();
        $request['timestamp'] = $this->getTimestamp();
        $request['v'] = $this->getV();
        $request['method'] = $josReuqest->getJosMethod();
        $request['sign'] = $this->generateSign($request);
        return $request;
    }

    private function generateSign($params)
    {
        ksort($params);
        $stringToBeSigned = $this->appSecret;
        foreach ($params as $k => $v)
        {
            $stringToBeSigned .= $k . $v;
        }
        $stringToBeSigned .= $this->appSecret;
        return strtoupper(md5($stringToBeSigned));
    }
}