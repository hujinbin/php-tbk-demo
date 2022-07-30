<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddKttPurchaseOrderDeliveryRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(Integer, "logistics_id")
	*/
	private $logisticsId;

	/**
	* @JsonProperty(String, "logistics_name")
	*/
	private $logisticsName;

	/**
	* @JsonProperty(String, "order_sn")
	*/
	private $orderSn;

	/**
	* @JsonProperty(String, "waybill_no")
	*/
	private $waybillNo;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "logistics_id", $this->logisticsId);
		$this->setUserParam($params, "logistics_name", $this->logisticsName);
		$this->setUserParam($params, "order_sn", $this->orderSn);
		$this->setUserParam($params, "waybill_no", $this->waybillNo);

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
		return "pdd.ktt.purchase.order.delivery";
	}

	public function setLogisticsId($logisticsId)
	{
		$this->logisticsId = $logisticsId;
	}

	public function setLogisticsName($logisticsName)
	{
		$this->logisticsName = $logisticsName;
	}

	public function setOrderSn($orderSn)
	{
		$this->orderSn = $orderSn;
	}

	public function setWaybillNo($waybillNo)
	{
		$this->waybillNo = $waybillNo;
	}

}
