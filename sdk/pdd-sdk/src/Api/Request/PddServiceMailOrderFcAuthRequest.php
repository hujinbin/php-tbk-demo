<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddServiceMailOrderFcAuthRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(\Com\Pdd\Pop\Sdk\Api\Request\PddServiceMailOrderFcAuthRequest_UrlParams, "urlParams")
	*/
	private $urlParams;

	/**
	* @JsonProperty(String, "httpMethod")
	*/
	private $httpMethod;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "urlParams", $this->urlParams);
		$this->setUserParam($params, "httpMethod", $this->httpMethod);

	}

	public function getVersion()
	{
		return "V1";
	}

	public function getDataType()
	{
		return "JSON";
	}

	public function getType()
	{
		return "pdd.service.mail.order.fc.auth";
	}

	public function setUrlParams($urlParams)
	{
		$this->urlParams = $urlParams;
	}

	public function setHttpMethod($httpMethod)
	{
		$this->httpMethod = $httpMethod;
	}

}

class PddServiceMailOrderFcAuthRequest_UrlParams extends PopBaseJsonEntity
{

	public function __construct()
	{

	}

	/**
	* @JsonProperty(String, "app_key")
	*/
	private $appKey;

	/**
	* @JsonProperty(String, "app_secret")
	*/
	private $appSecret;

	public function setAppKey($appKey)
	{
		$this->appKey = $appKey;
	}

	public function setAppSecret($appSecret)
	{
		$this->appSecret = $appSecret;
	}

}
