<?php

/**
 * 权益信息
 * @author auto create
 */
class PromotionList
{
	
	/** 
	 * 权益起用门槛，满X元可用，券场景为满元，精确到分，如满100元可用
	 **/
	public $entry_condition;
	
	/** 
	 * 权益面额，券场景为减钱，精确到分
	 **/
	public $entry_discount;
	
	/** 
	 * 权益结束时间，精确到毫秒时间戳
	 **/
	public $entry_used_end_time;
	
	/** 
	 * 权益开始时间，精确到毫秒时间戳
	 **/
	public $entry_used_start_time;	
}
?>