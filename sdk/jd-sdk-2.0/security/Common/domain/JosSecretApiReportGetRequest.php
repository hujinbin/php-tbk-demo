<?php


namespace ACES\Common\domain;


class JosSecretApiReportGetRequest
{
    private $serverUrl;
    private $businessId;
    private $text;
    private $attribute;

    public function to360buyParamJson()
    {
        return json_encode(get_object_vars($this));
    }
    public function getJosMethod()
    {
        return 'jingdong.jos.secret.api.report.get';
    }
}