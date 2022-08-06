<?php


namespace ACES\Common\domain;


use ACES\Common\KeyResponse;

class JosMasterKeyGetResponse extends JosBaseResponse
{
    private $response;

    /**
     * @return \ACES\Common\KeyResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \ACES\Common\KeyResponse|null $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

}