<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddRefundReturngoodsAgreeRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(\Com\Pdd\Pop\Sdk\Api\Request\PddRefundReturngoodsAgreeRequest_Request, "request")
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
		return "pdd.refund.returngoods.agree";
	}

	public function setRequest($request)
	{
		$this->request = $request;
	}

}

class PddRefundReturngoodsAgreeRequest_Request extends PopBaseJsonEntity
{

	public function __construct()
	{

	}

	/**
	* @JsonProperty(Long, "after_sales_id")
	*/
	private $afterSalesId;

	/**
	* @JsonProperty(String, "operate_desc")
	*/
	private $operateDesc;

	/**
	* @JsonProperty(String, "order_sn")
	*/
	private $orderSn;

	/**
	* @JsonProperty(String, "return_address_id")
	*/
	private $returnAddressId;

	public function setAfterSalesId($afterSalesId)
	{
		$this->afterSalesId = $afterSalesId;
	}

	public function setOperateDesc($operateDesc)
	{
		$this->operateDesc = $operateDesc;
	}

	public function setOrderSn($orderSn)
	{
		$this->orderSn = $orderSn;
	}

	public function setReturnAddressId($returnAddressId)
	{
		$this->returnAddressId = $returnAddressId;
	}

}
