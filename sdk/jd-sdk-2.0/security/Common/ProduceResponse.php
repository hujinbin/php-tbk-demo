<?php
namespace ACES\Common;

use ACES\Common\domain\JosBaseResponse;

class ProduceResponse extends JosBaseResponse
{
    private $response;

    /**
     * @return \ACES\Common\domain\JosSecretApiReportGetResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \ACES\Common\domain\JosSecretApiReportGetResponse|null $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }


}

class Status {
    private $code;
    private $msg;
    
    public function getCode(){
        return $this->code;
    }
    public function getMsg(){
        return $this->msg;
    }
    
    /**
     * 
     * @param int|null $code
     */
    public function setCode($code){
        $this->code = $code;
    }
    
    /**
     * 
     * @param string|null $msg
     */
    public function setMsg($msg){
        $this->msg = $msg;
    }
}
