<?php


namespace ACES\Common\domain;


class GetVoucherInfoRes
{
    private $errorCode;
    private $errorMsg;
    private $data;

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param string|null $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * @param string|null $errorMsg
     */
    public function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;
    }

    /**
     * @return GetVoucherInfoResVo
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param GetVoucherInfoResVo|null $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }


}