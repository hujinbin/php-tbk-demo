<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddOverseaCustomsClearanceGetSignRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(String, "ceb_name")
	*/
	private $cebName;

	/**
	* @JsonProperty(String, "company_customs_code")
	*/
	private $companyCustomsCode;

	/**
	* @JsonProperty(String, "order_sn")
	*/
	private $orderSn;

	/**
	* @JsonProperty(String, "original_data_to_sign")
	*/
	private $originalDataToSign;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "ceb_name", $this->cebName);
		$this->setUserParam($params, "company_customs_code", $this->companyCustomsCode);
		$this->setUserParam($params, "order_sn", $this->orderSn);
		$this->setUserParam($params, "original_data_to_sign", $this->originalDataToSign);

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
		return "pdd.oversea.customs.clearance.get.sign";
	}

	public function setCebName($cebName)
	{
		$this->cebName = $cebName;
	}

	public function setCompanyCustomsCode($companyCustomsCode)
	{
		$this->companyCustomsCode = $companyCustomsCode;
	}

	public function setOrderSn($orderSn)
	{
		$this->orderSn = $orderSn;
	}

	public function setOriginalDataToSign($originalDataToSign)
	{
		$this->originalDataToSign = $originalDataToSign;
	}

}
