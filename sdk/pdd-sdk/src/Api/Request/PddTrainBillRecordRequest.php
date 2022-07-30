<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddTrainBillRecordRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(Integer, "book_type")
	*/
	private $bookType;

	/**
	* @JsonProperty(String, "changed_time")
	*/
	private $changedTime;

	/**
	* @JsonProperty(Integer, "change_type")
	*/
	private $changeType;

	/**
	* @JsonProperty(String, "confirmed_time")
	*/
	private $confirmedTime;

	/**
	* @JsonProperty(Integer, "crh_account")
	*/
	private $crhAccount;

	/**
	* @JsonProperty(String, "crh_order")
	*/
	private $crhOrder;

	/**
	* @JsonProperty(String, "sub_crh_order")
	*/
	private $subCrhOrder;

	/**
	* @JsonProperty(String, "date")
	*/
	private $date;

	/**
	* @JsonProperty(String, "order_id")
	*/
	private $orderId;

	/**
	* @JsonProperty(String, "sub_order_id")
	*/
	private $subOrderId;

	/**
	* @JsonProperty(String, "pdd_order_id")
	*/
	private $pddOrderId;

	/**
	* @JsonProperty(String, "sub_pdd_order_id")
	*/
	private $subPddOrderId;

	/**
	* @JsonProperty(String, "returned_time")
	*/
	private $returnedTime;

	/**
	* @JsonProperty(Long, "service_amount")
	*/
	private $serviceAmount;

	/**
	* @JsonProperty(Long, "service_price")
	*/
	private $servicePrice;

	/**
	* @JsonProperty(Integer, "ticket_status")
	*/
	private $ticketStatus;

	/**
	* @JsonProperty(Long, "version")
	*/
	private $version;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "book_type", $this->bookType);
		$this->setUserParam($params, "changed_time", $this->changedTime);
		$this->setUserParam($params, "change_type", $this->changeType);
		$this->setUserParam($params, "confirmed_time", $this->confirmedTime);
		$this->setUserParam($params, "crh_account", $this->crhAccount);
		$this->setUserParam($params, "crh_order", $this->crhOrder);
		$this->setUserParam($params, "sub_crh_order", $this->subCrhOrder);
		$this->setUserParam($params, "date", $this->date);
		$this->setUserParam($params, "order_id", $this->orderId);
		$this->setUserParam($params, "sub_order_id", $this->subOrderId);
		$this->setUserParam($params, "pdd_order_id", $this->pddOrderId);
		$this->setUserParam($params, "sub_pdd_order_id", $this->subPddOrderId);
		$this->setUserParam($params, "returned_time", $this->returnedTime);
		$this->setUserParam($params, "service_amount", $this->serviceAmount);
		$this->setUserParam($params, "service_price", $this->servicePrice);
		$this->setUserParam($params, "ticket_status", $this->ticketStatus);
		$this->setUserParam($params, "version", $this->version);

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
		return "pdd.train.bill.record";
	}

	public function setBookType($bookType)
	{
		$this->bookType = $bookType;
	}

	public function setChangedTime($changedTime)
	{
		$this->changedTime = $changedTime;
	}

	public function setChangeType($changeType)
	{
		$this->changeType = $changeType;
	}

	public function setConfirmedTime($confirmedTime)
	{
		$this->confirmedTime = $confirmedTime;
	}

	public function setCrhAccount($crhAccount)
	{
		$this->crhAccount = $crhAccount;
	}

	public function setCrhOrder($crhOrder)
	{
		$this->crhOrder = $crhOrder;
	}

	public function setSubCrhOrder($subCrhOrder)
	{
		$this->subCrhOrder = $subCrhOrder;
	}

	public function setDate($date)
	{
		$this->date = $date;
	}

	public function setOrderId($orderId)
	{
		$this->orderId = $orderId;
	}

	public function setSubOrderId($subOrderId)
	{
		$this->subOrderId = $subOrderId;
	}

	public function setPddOrderId($pddOrderId)
	{
		$this->pddOrderId = $pddOrderId;
	}

	public function setSubPddOrderId($subPddOrderId)
	{
		$this->subPddOrderId = $subPddOrderId;
	}

	public function setReturnedTime($returnedTime)
	{
		$this->returnedTime = $returnedTime;
	}

	public function setServiceAmount($serviceAmount)
	{
		$this->serviceAmount = $serviceAmount;
	}

	public function setServicePrice($servicePrice)
	{
		$this->servicePrice = $servicePrice;
	}

	public function setTicketStatus($ticketStatus)
	{
		$this->ticketStatus = $ticketStatus;
	}

	public function setVersion($version)
	{
		$this->version = $version;
	}

}
