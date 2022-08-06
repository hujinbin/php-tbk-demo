<?php


namespace ACES\Common\domain;


abstract class JosBaseResponse
{
    private $code;
    private $msg;
    private $enDesc;
    private $zhDesc;

    /**
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param integer|null $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @param string|null $msg
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;
    }

    /**
     * @return string
     */
    public function getEnDesc()
    {
        return $this->enDesc;
    }

    /**
     * @param string|null $enDesc
     */
    public function setEnDesc($enDesc)
    {
        $this->enDesc = $enDesc;
    }

    /**
     * @return string
     */
    public function getZhDesc()
    {
        return $this->zhDesc;
    }

    /**
     * @param string|null $zhDesc
     */
    public function setZhDesc($zhDesc)
    {
        $this->zhDesc = $zhDesc;
    }

    /**
     * @param $josJsonResponse
     * @param JosBaseResponse $targetResponse
     * @return JosVoucherInfoGetResponse|JosMasterKeyGetResponse|JosSecretApiReportGetResponse
     * @throws \JsonMapper_Exception
     */
    public static function parse($josJsonResponse, JosBaseResponse $targetResponse)
    {
        $josResponse = json_decode($josJsonResponse);
        foreach ($josResponse as $k => $response) {
            //TODO thread safe？
            $jsonMapper = new \JsonMapper();
            $jsonMapper->map($response, $targetResponse);
            return $targetResponse;
        }
    }
}

//$json = '{"jingdong_jos_master_key_get_responce":{"code":"0","response":{"status_code":310,"key_cache_disabled":0,"key_backup_disabled":0,"ts":1566810198390,"errorMsg":"The voucher is null."}}}';
//
//JosBaseResponse::parse($json, new JosBaseResponse());