<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddDdkCashgiftCreateRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(Long, "acquire_end_time")
	*/
	private $acquireEndTime;

	/**
	* @JsonProperty(Long, "acquire_start_time")
	*/
	private $acquireStartTime;

	/**
	* @JsonProperty(Boolean, "auto_take")
	*/
	private $autoTake;

	/**
	* @JsonProperty(Integer, "coupon_amount")
	*/
	private $couponAmount;

	/**
	* @JsonProperty(Integer, "duration")
	*/
	private $duration;

	/**
	* @JsonProperty(Integer, "except_amount")
	*/
	private $exceptAmount;

	/**
	* @JsonProperty(Boolean, "fetch_risk_check")
	*/
	private $fetchRiskCheck;

	/**
	* @JsonProperty(Integer, "freeze_condition")
	*/
	private $freezeCondition;

	/**
	* @JsonProperty(Integer, "freeze_watch_type")
	*/
	private $freezeWatchType;

	/**
	* @JsonProperty(Boolean, "generate_flexible_coupon")
	*/
	private $generateFlexibleCoupon;

	/**
	* @JsonProperty(Boolean, "generate_global")
	*/
	private $generateGlobal;

	/**
	* @JsonProperty(List<String>, "goods_sign_list")
	*/
	private $goodsSignList;

	/**
	* @JsonProperty(Long, "link_acquire_limit")
	*/
	private $linkAcquireLimit;

	/**
	* @JsonProperty(String, "name")
	*/
	private $name;

	/**
	* @JsonProperty(List<String>, "p_id_list")
	*/
	private $pIdList;

	/**
	* @JsonProperty(Long, "quantity")
	*/
	private $quantity;

	/**
	* @JsonProperty(Boolean, "rate_decrease_monitor")
	*/
	private $rateDecreaseMonitor;

	/**
	* @JsonProperty(Integer, "relative_time_type")
	*/
	private $relativeTimeType;

	/**
	* @JsonProperty(Long, "total_amount")
	*/
	private $totalAmount;

	/**
	* @JsonProperty(Integer, "user_limit")
	*/
	private $userLimit;

	/**
	* @JsonProperty(Long, "validity_end_time")
	*/
	private $validityEndTime;

	/**
	* @JsonProperty(Long, "validity_start_time")
	*/
	private $validityStartTime;

	/**
	* @JsonProperty(Integer, "validity_time_type")
	*/
	private $validityTimeType;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "acquire_end_time", $this->acquireEndTime);
		$this->setUserParam($params, "acquire_start_time", $this->acquireStartTime);
		$this->setUserParam($params, "auto_take", $this->autoTake);
		$this->setUserParam($params, "coupon_amount", $this->couponAmount);
		$this->setUserParam($params, "duration", $this->duration);
		$this->setUserParam($params, "except_amount", $this->exceptAmount);
		$this->setUserParam($params, "fetch_risk_check", $this->fetchRiskCheck);
		$this->setUserParam($params, "freeze_condition", $this->freezeCondition);
		$this->setUserParam($params, "freeze_watch_type", $this->freezeWatchType);
		$this->setUserParam($params, "generate_flexible_coupon", $this->generateFlexibleCoupon);
		$this->setUserParam($params, "generate_global", $this->generateGlobal);
		$this->setUserParam($params, "goods_sign_list", $this->goodsSignList);
		$this->setUserParam($params, "link_acquire_limit", $this->linkAcquireLimit);
		$this->setUserParam($params, "name", $this->name);
		$this->setUserParam($params, "p_id_list", $this->pIdList);
		$this->setUserParam($params, "quantity", $this->quantity);
		$this->setUserParam($params, "rate_decrease_monitor", $this->rateDecreaseMonitor);
		$this->setUserParam($params, "relative_time_type", $this->relativeTimeType);
		$this->setUserParam($params, "total_amount", $this->totalAmount);
		$this->setUserParam($params, "user_limit", $this->userLimit);
		$this->setUserParam($params, "validity_end_time", $this->validityEndTime);
		$this->setUserParam($params, "validity_start_time", $this->validityStartTime);
		$this->setUserParam($params, "validity_time_type", $this->validityTimeType);

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
		return "pdd.ddk.cashgift.create";
	}

	public function setAcquireEndTime($acquireEndTime)
	{
		$this->acquireEndTime = $acquireEndTime;
	}

	public function setAcquireStartTime($acquireStartTime)
	{
		$this->acquireStartTime = $acquireStartTime;
	}

	public function setAutoTake($autoTake)
	{
		$this->autoTake = $autoTake;
	}

	public function setCouponAmount($couponAmount)
	{
		$this->couponAmount = $couponAmount;
	}

	public function setDuration($duration)
	{
		$this->duration = $duration;
	}

	public function setExceptAmount($exceptAmount)
	{
		$this->exceptAmount = $exceptAmount;
	}

	public function setFetchRiskCheck($fetchRiskCheck)
	{
		$this->fetchRiskCheck = $fetchRiskCheck;
	}

	public function setFreezeCondition($freezeCondition)
	{
		$this->freezeCondition = $freezeCondition;
	}

	public function setFreezeWatchType($freezeWatchType)
	{
		$this->freezeWatchType = $freezeWatchType;
	}

	public function setGenerateFlexibleCoupon($generateFlexibleCoupon)
	{
		$this->generateFlexibleCoupon = $generateFlexibleCoupon;
	}

	public function setGenerateGlobal($generateGlobal)
	{
		$this->generateGlobal = $generateGlobal;
	}

	public function setGoodsSignList($goodsSignList)
	{
		$this->goodsSignList = $goodsSignList;
	}

	public function setLinkAcquireLimit($linkAcquireLimit)
	{
		$this->linkAcquireLimit = $linkAcquireLimit;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setPIdList($pIdList)
	{
		$this->pIdList = $pIdList;
	}

	public function setQuantity($quantity)
	{
		$this->quantity = $quantity;
	}

	public function setRateDecreaseMonitor($rateDecreaseMonitor)
	{
		$this->rateDecreaseMonitor = $rateDecreaseMonitor;
	}

	public function setRelativeTimeType($relativeTimeType)
	{
		$this->relativeTimeType = $relativeTimeType;
	}

	public function setTotalAmount($totalAmount)
	{
		$this->totalAmount = $totalAmount;
	}

	public function setUserLimit($userLimit)
	{
		$this->userLimit = $userLimit;
	}

	public function setValidityEndTime($validityEndTime)
	{
		$this->validityEndTime = $validityEndTime;
	}

	public function setValidityStartTime($validityStartTime)
	{
		$this->validityStartTime = $validityStartTime;
	}

	public function setValidityTimeType($validityTimeType)
	{
		$this->validityTimeType = $validityTimeType;
	}

}
