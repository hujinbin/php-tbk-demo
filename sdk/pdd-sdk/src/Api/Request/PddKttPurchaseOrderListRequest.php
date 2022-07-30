<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddKttPurchaseOrderListRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(Integer, "cancel_status")
	*/
	private $cancelStatus;

	/**
	* @JsonProperty(Long, "end_updated_time")
	*/
	private $endUpdatedTime;

	/**
	* @JsonProperty(Integer, "page_no")
	*/
	private $pageNo;

	/**
	* @JsonProperty(Integer, "page_size")
	*/
	private $pageSize;

	/**
	* @JsonProperty(Integer, "shipping_status")
	*/
	private $shippingStatus;

	/**
	* @JsonProperty(Long, "start_update_time")
	*/
	private $startUpdateTime;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "cancel_status", $this->cancelStatus);
		$this->setUserParam($params, "end_updated_time", $this->endUpdatedTime);
		$this->setUserParam($params, "page_no", $this->pageNo);
		$this->setUserParam($params, "page_size", $this->pageSize);
		$this->setUserParam($params, "shipping_status", $this->shippingStatus);
		$this->setUserParam($params, "start_update_time", $this->startUpdateTime);

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
		return "pdd.ktt.purchase.order.list";
	}

	public function setCancelStatus($cancelStatus)
	{
		$this->cancelStatus = $cancelStatus;
	}

	public function setEndUpdatedTime($endUpdatedTime)
	{
		$this->endUpdatedTime = $endUpdatedTime;
	}

	public function setPageNo($pageNo)
	{
		$this->pageNo = $pageNo;
	}

	public function setPageSize($pageSize)
	{
		$this->pageSize = $pageSize;
	}

	public function setShippingStatus($shippingStatus)
	{
		$this->shippingStatus = $shippingStatus;
	}

	public function setStartUpdateTime($startUpdateTime)
	{
		$this->startUpdateTime = $startUpdateTime;
	}

}
