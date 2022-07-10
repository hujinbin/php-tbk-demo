<?php

/**
 * extra
 * @author auto create
 */
class Extra
{
	
	/** 
	 * 引导付款金额，同一个红包，若因消费者付款使用后取消订单或退货退款，产生二次红包使用行为，引导付款笔数也会记录两单
	 **/
	public $alipay_amt;
	
	/** 
	 * 引导付款笔数，同一个红包，若因消费者付款使用后取消订单或退货退款，产生二次红包使用行为，引导付款笔数也会记录两单
	 **/
	public $alipay_num;
	
	/** 
	 * 结算佣金，确认收货，产生二次红包使用行为，会记录2次
	 **/
	public $cm_settle_amt;
	
	/** 
	 * 领取率，领取淘礼金个数/创建淘礼金个数
	 **/
	public $get_rate;
	
	/** 
	 * 付款佣金，下单付款，产生二次红包使用行为，会记录2次
	 **/
	public $pre_pub_share_fee_for_disp;
	
	/** 
	 * 退款淘礼金个数，红包使用后，由于订单取消，退货退款等行为带来的淘礼金红包退回数量，退款红包数量单日内不重复计算，跨天重复计算
	 **/
	public $refund_num;
	
	/** 
	 * 退款淘礼金金额，红包使用后，由于订单取消，退货退款等行为行为带来的淘礼金红包退回数量 （退款红包若产生多次使用，退款红包金额会被多次计算，退款红包数量单日内不重复计算，跨天重复计算）
	 **/
	public $refund_sum_amt;
	
	/** 
	 * 未领取金额，过了领取有效期或者暂停后没有被领取的红包金额
	 **/
	public $remaining_amt;
	
	/** 
	 * 未领取淘礼金个数，过了领取有效期或者暂停后没有被领取的红包个数
	 **/
	public $remaining_num;
	
	/** 
	 * 使用淘礼金个数，同一个红包，若因消费者付款使用后取消订单或退货退款，产生二次红包使用行为，使用淘礼金个数一天内会去重，所以相当于不会重记
	 **/
	public $use_num;
	
	/** 
	 * 使用率，使用淘礼金个数/领取淘礼金个数
	 **/
	public $use_rate;
	
	/** 
	 * 使用淘礼金金额，若红包被重复使用（1)淘礼金红包被拆分，并且产生部分退款，会保留部分退款的订单淘礼金金额；若全部退款，会保留订单全部淘礼金金额），因此，已使用金额可能大于消费者实际使用金额（使用红包后，若产生红包退回后再次使用，已使用金额会被二次计算，已使用数量不会）
	 **/
	public $use_sum_amt;
	
	/** 
	 * 领取淘礼金个数
	 **/
	public $win_pv;
	
	/** 
	 * 领取淘礼金金额
	 **/
	public $win_sum_amt;	
}
?>