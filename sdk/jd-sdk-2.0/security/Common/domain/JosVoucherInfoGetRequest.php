<?php


namespace ACES\Common\domain;


class JosVoucherInfoGetRequest
{
    private $customerUserId;
    private $accessToken;

    /**
     * JosVoucherInfoGetRequest constructor.
     * @param $customerUserId
     */
    public function __construct($accessToken)
    {
        // 区分accessToken和customerUserId
        if (isset($accessToken)) {
            if (stripos($accessToken, '_') === 0) {
                $split = explode('_', $accessToken);
                $this->customerUserId = $split[1];
            }else{
                $this->accessToken = $accessToken;
            }
        }
    }

    /**
     * @param JosBaseInfo $josBaseInfo
     * @return array
     */
    public function toFormParams($josBaseInfo)
    {
        return $josBaseInfo->getFormParams($this);
    }

    public function to360buyParamJson()
    {
        $paramJson = array();
        if (isset($this->customerUserId)) {
            $paramJson['customer_user_id'] = $this->customerUserId;
        }
        if (isset($this->accessToken)) {
            $paramJson['access_token'] = $this->accessToken;
        }
        return json_encode($paramJson);
    }
    public function getJosMethod()
    {
        return 'jingdong.jos.voucher.info.get';
    }
}