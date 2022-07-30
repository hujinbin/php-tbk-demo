<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddServiceMailBizactionNotifyRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(\Com\Pdd\Pop\Sdk\Api\Request\PddServiceMailBizactionNotifyRequest_Request, "request")
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
		return "pdd.service.mail.bizaction.notify";
	}

	public function setRequest($request)
	{
		$this->request = $request;
	}

}

class PddServiceMailBizactionNotifyRequest_Request extends PopBaseJsonEntity
{

	public function __construct()
	{

	}

	/**
	* @JsonProperty(String, "attributes")
	*/
	private $attributes;

	/**
	* @JsonProperty(String, "bizAction")
	*/
	private $bizAction;

	/**
	* @JsonProperty(String, "bizActionDesc")
	*/
	private $bizActionDesc;

	/**
	* @JsonProperty(String, "cancelOrRejectReason")
	*/
	private $cancelOrRejectReason;

	/**
	* @JsonProperty(Integer, "checkPrice")
	*/
	private $checkPrice;

	/**
	* @JsonProperty(Integer, "checkWeight")
	*/
	private $checkWeight;

	/**
	* @JsonProperty(String, "endTime")
	*/
	private $endTime;

	/**
	* @JsonProperty(String, "executeTime")
	*/
	private $executeTime;

	/**
	* @JsonProperty(String, "expressBoxCode")
	*/
	private $expressBoxCode;

	/**
	* @JsonProperty(String, "expressCode")
	*/
	private $expressCode;

	/**
	* @JsonProperty(Integer, "freightPrice")
	*/
	private $freightPrice;

	/**
	* @JsonProperty(Integer, "insurancePrice")
	*/
	private $insurancePrice;

	/**
	* @JsonProperty(Integer, "insuranceValue")
	*/
	private $insuranceValue;

	/**
	* @JsonProperty(String, "mailNo")
	*/
	private $mailNo;

	/**
	* @JsonProperty(String, "mailOrderSn")
	*/
	private $mailOrderSn;

	/**
	* @JsonProperty(Integer, "otherPrice")
	*/
	private $otherPrice;

	/**
	* @JsonProperty(Integer, "packagePrice")
	*/
	private $packagePrice;

	/**
	* @JsonProperty(Integer, "payPrice")
	*/
	private $payPrice;

	/**
	* @JsonProperty(String, "pickCode")
	*/
	private $pickCode;

	/**
	* @JsonProperty(String, "postmanCode")
	*/
	private $postmanCode;

	/**
	* @JsonProperty(String, "postmanName")
	*/
	private $postmanName;

	/**
	* @JsonProperty(String, "postmanPhone")
	*/
	private $postmanPhone;

	/**
	* @JsonProperty(String, "postType")
	*/
	private $postType;

	/**
	* @JsonProperty(String, "reasonCode")
	*/
	private $reasonCode;

	/**
	* @JsonProperty(String, "startTime")
	*/
	private $startTime;

	/**
	* @JsonProperty(String, "retentionReason")
	*/
	private $retentionReason;

	/**
	* @JsonProperty(\Com\Pdd\Pop\Sdk\Api\Request\PddServiceMailBizactionNotifyRequest_RequestModifyReceiverInfo, "modifyReceiverInfo")
	*/
	private $modifyReceiverInfo;

	public function setAttributes($attributes)
	{
		$this->attributes = $attributes;
	}

	public function setBizAction($bizAction)
	{
		$this->bizAction = $bizAction;
	}

	public function setBizActionDesc($bizActionDesc)
	{
		$this->bizActionDesc = $bizActionDesc;
	}

	public function setCancelOrRejectReason($cancelOrRejectReason)
	{
		$this->cancelOrRejectReason = $cancelOrRejectReason;
	}

	public function setCheckPrice($checkPrice)
	{
		$this->checkPrice = $checkPrice;
	}

	public function setCheckWeight($checkWeight)
	{
		$this->checkWeight = $checkWeight;
	}

	public function setEndTime($endTime)
	{
		$this->endTime = $endTime;
	}

	public function setExecuteTime($executeTime)
	{
		$this->executeTime = $executeTime;
	}

	public function setExpressBoxCode($expressBoxCode)
	{
		$this->expressBoxCode = $expressBoxCode;
	}

	public function setExpressCode($expressCode)
	{
		$this->expressCode = $expressCode;
	}

	public function setFreightPrice($freightPrice)
	{
		$this->freightPrice = $freightPrice;
	}

	public function setInsurancePrice($insurancePrice)
	{
		$this->insurancePrice = $insurancePrice;
	}

	public function setInsuranceValue($insuranceValue)
	{
		$this->insuranceValue = $insuranceValue;
	}

	public function setMailNo($mailNo)
	{
		$this->mailNo = $mailNo;
	}

	public function setMailOrderSn($mailOrderSn)
	{
		$this->mailOrderSn = $mailOrderSn;
	}

	public function setOtherPrice($otherPrice)
	{
		$this->otherPrice = $otherPrice;
	}

	public function setPackagePrice($packagePrice)
	{
		$this->packagePrice = $packagePrice;
	}

	public function setPayPrice($payPrice)
	{
		$this->payPrice = $payPrice;
	}

	public function setPickCode($pickCode)
	{
		$this->pickCode = $pickCode;
	}

	public function setPostmanCode($postmanCode)
	{
		$this->postmanCode = $postmanCode;
	}

	public function setPostmanName($postmanName)
	{
		$this->postmanName = $postmanName;
	}

	public function setPostmanPhone($postmanPhone)
	{
		$this->postmanPhone = $postmanPhone;
	}

	public function setPostType($postType)
	{
		$this->postType = $postType;
	}

	public function setReasonCode($reasonCode)
	{
		$this->reasonCode = $reasonCode;
	}

	public function setStartTime($startTime)
	{
		$this->startTime = $startTime;
	}

	public function setRetentionReason($retentionReason)
	{
		$this->retentionReason = $retentionReason;
	}

	public function setModifyReceiverInfo($modifyReceiverInfo)
	{
		$this->modifyReceiverInfo = $modifyReceiverInfo;
	}

}

class PddServiceMailBizactionNotifyRequest_RequestModifyReceiverInfo extends PopBaseJsonEntity
{

	public function __construct()
	{

	}

	/**
	* @JsonProperty(String, "addrDetail")
	*/
	private $addrDetail;

	/**
	* @JsonProperty(String, "areaName")
	*/
	private $areaName;

	/**
	* @JsonProperty(String, "cityName")
	*/
	private $cityName;

	/**
	* @JsonProperty(String, "contactName")
	*/
	private $contactName;

	/**
	* @JsonProperty(String, "mobile")
	*/
	private $mobile;

	/**
	* @JsonProperty(String, "provName")
	*/
	private $provName;

	/**
	* @JsonProperty(String, "streetName")
	*/
	private $streetName;

	/**
	* @JsonProperty(String, "telephone")
	*/
	private $telephone;

	public function setAddrDetail($addrDetail)
	{
		$this->addrDetail = $addrDetail;
	}

	public function setAreaName($areaName)
	{
		$this->areaName = $areaName;
	}

	public function setCityName($cityName)
	{
		$this->cityName = $cityName;
	}

	public function setContactName($contactName)
	{
		$this->contactName = $contactName;
	}

	public function setMobile($mobile)
	{
		$this->mobile = $mobile;
	}

	public function setProvName($provName)
	{
		$this->provName = $provName;
	}

	public function setStreetName($streetName)
	{
		$this->streetName = $streetName;
	}

	public function setTelephone($telephone)
	{
		$this->telephone = $telephone;
	}

}
