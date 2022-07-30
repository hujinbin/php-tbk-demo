<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddEinvoiceInfoQueryRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(Long, "end_time")
	*/
	private $endTime;

	/**
	* @JsonProperty(Integer, "invoice_type")
	*/
	private $invoiceType;

	/**
	* @JsonProperty(Integer, "page")
	*/
	private $page;

	/**
	* @JsonProperty(Integer, "page_size")
	*/
	private $pageSize;

	/**
	* @JsonProperty(Long, "start_time")
	*/
	private $startTime;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "end_time", $this->endTime);
		$this->setUserParam($params, "invoice_type", $this->invoiceType);
		$this->setUserParam($params, "page", $this->page);
		$this->setUserParam($params, "page_size", $this->pageSize);
		$this->setUserParam($params, "start_time", $this->startTime);

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
		return "pdd.einvoice.info.query";
	}

	public function setEndTime($endTime)
	{
		$this->endTime = $endTime;
	}

	public function setInvoiceType($invoiceType)
	{
		$this->invoiceType = $invoiceType;
	}

	public function setPage($page)
	{
		$this->page = $page;
	}

	public function setPageSize($pageSize)
	{
		$this->pageSize = $pageSize;
	}

	public function setStartTime($startTime)
	{
		$this->startTime = $startTime;
	}

}
