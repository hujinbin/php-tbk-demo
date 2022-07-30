<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddServiceMailServiceTimeQueryRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(\Com\Pdd\Pop\Sdk\Api\Request\PddServiceMailServiceTimeQueryRequest_Request, "request")
	*/
	private $request;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "request", $this->request);

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
		return "pdd.service.mail.service.time.query";
	}

	public function setRequest($request)
	{
		$this->request = $request;
	}

}

class PddServiceMailServiceTimeQueryRequest_Request extends PopBaseJsonEntity
{

	public function __construct()
	{

	}

	/**
	* @JsonProperty(String, "provName")
	*/
	private $provName;

	/**
	* @JsonProperty(String, "cityName")
	*/
	private $cityName;

	/**
	* @JsonProperty(String, "districtName")
	*/
	private $districtName;

	/**
	* @JsonProperty(String, "streetName")
	*/
	private $streetName;

	/**
	* @JsonProperty(String, "postType")
	*/
	private $postType;

	/**
	* @JsonProperty(String, "receiveProvName")
	*/
	private $receiveProvName;

	/**
	* @JsonProperty(String, "receiveCityName")
	*/
	private $receiveCityName;

	/**
	* @JsonProperty(String, "receiveDistrictName")
	*/
	private $receiveDistrictName;

	/**
	* @JsonProperty(String, "receiveStreetName")
	*/
	private $receiveStreetName;

	/**
	* @JsonProperty(String, "receiveAddrDetail")
	*/
	private $receiveAddrDetail;

	/**
	* @JsonProperty(String, "attributes")
	*/
	private $attributes;

	public function setProvName($provName)
	{
		$this->provName = $provName;
	}

	public function setCityName($cityName)
	{
		$this->cityName = $cityName;
	}

	public function setDistrictName($districtName)
	{
		$this->districtName = $districtName;
	}

	public function setStreetName($streetName)
	{
		$this->streetName = $streetName;
	}

	public function setPostType($postType)
	{
		$this->postType = $postType;
	}

	public function setReceiveProvName($receiveProvName)
	{
		$this->receiveProvName = $receiveProvName;
	}

	public function setReceiveCityName($receiveCityName)
	{
		$this->receiveCityName = $receiveCityName;
	}

	public function setReceiveDistrictName($receiveDistrictName)
	{
		$this->receiveDistrictName = $receiveDistrictName;
	}

	public function setReceiveStreetName($receiveStreetName)
	{
		$this->receiveStreetName = $receiveStreetName;
	}

	public function setReceiveAddrDetail($receiveAddrDetail)
	{
		$this->receiveAddrDetail = $receiveAddrDetail;
	}

	public function setAttributes($attributes)
	{
		$this->attributes = $attributes;
	}

}
