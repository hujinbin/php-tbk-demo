<?php
namespace ACES\Common;

use ACES\Common\domain\JosBaseInfo;

class KeyRequest
{
    public $data;
    public $sig;

    public function __construct($token, $major_sdk_ver) {
        $this->data = new KeyRequestData($token->get_id(), $major_sdk_ver);
        $json = json_encode($this->data);
//        $json = json_encode($this->data->jsonSerialize());
        $this->sig = base64_encode($token->do_sign($json));
    }

    public static function createNewKeyRequest($token, $major_sdk_ver) {
        $keyRequest = new KeyRequest($token, $major_sdk_ver);
        return json_encode($keyRequest);
    }

    /**
     * @param JosBaseInfo $josBaseInfo
     * @return array
     */
    public function toFormParams($josBaseInfo)
    {
        return $josBaseInfo->getFormParams($this);
    }

//    public function jsonSerialize()
//    {
//        $vars = get_object_vars($this);
//
//        return $vars;
//    }

    public function to360buyParamJson()
    {
        $josKeyRequest = array();
        $josKeyRequest['sig'] = $this->sig;
        $josKeyRequest['tid'] = $this->data->getTid();
        $josKeyRequest['ts'] = $this->data->getTs();
        $josKeyRequest['sdk_ver'] = $this->data->getSdkVer();
        return json_encode($josKeyRequest);
    }

    public function getJosMethod()
    {
        return 'jingdong.jos.master.key.get';
    }

}

