<?php

/**
 * model
 * @author auto create
 */
class RightsInstanceCreateResult
{
	
	/** 
	 * 创建完成后资金账户可用资金，单位元，保留2位小数
	 **/
	public $available_fee;
	
	/** 
	 * 媒体针对此商品今日剩余可领取淘礼金数量
	 **/
	public $item_today_num_left;
	
	/** 
	 * 淘礼金Id
	 **/
	public $rights_id;
	
	/** 
	 * 淘礼金领取Url
	 **/
	public $send_url;
	
	/** 
	 * 投放code【百川商品详情页业务专用】
	 **/
	public $vegas_code;	
}
?>