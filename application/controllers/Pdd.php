<?php
defined('BASEPATH') or exit('No direct script access allowed');

$pid = '';

require_once("sdk/pdd-sdk/Config.php");
require_once("sdk/pdd-sdk/vendor/autoload.php");

use Com\Pdd\Pop\Sdk\PopHttpClient;
use Com\Pdd\Pop\Sdk\PopAccessTokenClient;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkMemberAuthorityQueryRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkRpPromUrlGenerateRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkRpPromUrlGenerateRequest_DiyOneYuanParam;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkRpPromUrlGenerateRequest_DiyRedPacketParam;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkRpPromUrlGenerateRequest_DiyRedPacketParamRangeItemsItem;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsSearchRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsSearchRequest_RangeListItem;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsRecommendGetRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsDetailRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsPromotionUrlGenerateRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkCmsPromUrlGenerateRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkResourceUrlGenRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsZsUnitUrlGenRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkOrderListIncrementGetRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkOrderListRangeGetRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkOrderDetailGetRequest;


class Pdd extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set("PRC");
		// $this->auto_update_orders();
	}

	// 用户授权
	// 生成AccessToken
	public function generateToken()
	{
		if (IS_POST) {
			$accessTokenClient = new PopAccessTokenClient(Config::$clientId, Config::$clientSecret);
			// 生成AccessToken
			$result = $accessTokenClient->generate(Config::$code);
			$result = json_encode($result->getContent(), JSON_UNESCAPED_UNICODE);
			echo $result;
		}
	}

	// 用户授权
	// 刷新AccessToken
	public function refreshToken()
	{
		if (IS_POST) {
			$accessTokenClient = new PopAccessTokenClient(Config::$clientId, Config::$clientSecret);
			// 刷新AccessToken
			$result = $accessTokenClient->refresh(Config::$refreshToken);
			$result = json_encode($result->getContent(), JSON_UNESCAPED_UNICODE);
			echo $result;
		}
	}

	// 设置自定义参数，用于用户标识
	function setCustom()
	{
		$custom_parameters = 'hshop'; // 标记用户信息的参数
		return json_encode($custom_parameters);
	}

	// 查询是否绑定备案 pdd.ddk.member.authority.query
	// 本接口用于通过pid和自定义参数来查询是否已经绑定备案
	public function authorityQuery()
	{
		if (IS_POST) {
			global $pid;
			$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);

			$request = new PddDdkMemberAuthorityQueryRequest();
			$custom_parameters =$this->setCustom();
			$request->setCustomParameters($custom_parameters);
			$request->setPid($pid);
			// bind	INTEGER		1-已绑定；0-未绑定
			try {
				$response = $client->syncInvoke($request, Config::$accessToken);
			} catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
				echo $e->getMessage();
				exit;
			}
			$content = $response->getContent();
			if (isset($content['error_response'])) {
				echo "异常返回";
			}
			// echo json_encode($content, JSON_UNESCAPED_UNICODE);
			ajax_json(1, $content);
		}
	}

	// 生成备案链接 pdd.ddk.rp.prom.url.generate
	// 入参channel_type传10，生成授权备案链接
	public function urlGenerate()
	{
		if (IS_POST) {
			global $pid;
			$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);
			$request = new PddDdkRpPromUrlGenerateRequest();
			$request->setAmount(300);
			$request->setChannelType(10);
			$custom_parameters = $this->setCustom();
			$request->setCustomParameters($custom_parameters);
			// $diyOneYuanParam = new PddDdkRpPromUrlGenerateRequest_DiyOneYuanParam();
			// $diyOneYuanParam->setGoodsSign('');
			// $request->setDiyOneYuanParam($diyOneYuanParam);
			// $diyRedPacketParam = new PddDdkRpPromUrlGenerateRequest_DiyRedPacketParam();
			// $amountProbability = array();
			// $amountProbability[] = '';
			// $diyRedPacketParam->setAmountProbability($amountProbability);
			// $diyRedPacketParam->setDisText(false);
			// $diyRedPacketParam->setNotShowBackground(false);
			// $diyRedPacketParam->setOptId(0);
			// $rangeItems = array();
			// $item = new PddDdkRpPromUrlGenerateRequest_DiyRedPacketParamRangeItemsItem();
			// $item->setRangeFrom(0);
			// $item->setRangeId(0);
			// $item->setRangeTo(0);
			// $rangeItems[] = $item;
			// $diyRedPacketParam->setRangeItems($rangeItems);
			// $request->setDiyRedPacketParam($diyRedPacketParam);
			// $request->setGenerateQqApp(false);
			// $request->setGenerateSchemaUrl(false);
			// $request->setGenerateShortUrl(false);
			// $request->setGenerateWeApp(false);
			$pIdList = array();
			$pIdList[] = $pid;
			$request->setPIdList($pIdList);
			// $request->setScratchCardAmount(0);
			try {
				$response = $client->syncInvoke($request, Config::$accessToken);
			} catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
				echo $e->getMessage();
				exit;
			}
			$content = $response->getContent();
			if (isset($content['error_response'])) {
				echo "异常返回";
			}
			// echo json_encode($content, JSON_UNESCAPED_UNICODE);
			ajax_json(1, $content);
		}
	}

	// 商品搜索 pdd.ddk.goods.search
	// 支持用标签、分类、商品价格、佣金等筛选条件（由入参range_list字段控制）过滤出满足条件的商品，
	// 也可根据排序条件对检索出来的商品进行排序（由入参sort_type进行排序）。
	public function goodsSearch()
	{
		if (IS_POST) {
			$q = $this->input->post('q');  //搜索名称
			$opt_id = $this->input->post('opt_id');  //分类搜索
			if (empty($opt_id)) {
				$opt_id = 0;
			}
			// 加载更多分页
			$page = $this->input->post('page');
			if (empty($page)) {
				$page = 1;
			}
			global $pid;
			$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);
			$request = new PddDdkGoodsSearchRequest();
			// $activityTags = array();
			// $activityTags[] = 0;
			// $request->setActivityTags($activityTags);
			// $blockCatPackages = array();
			// $blockCatPackages[] = 0;
			// $request->setBlockCatPackages($blockCatPackages);
			// $blockCats = array();
			// $blockCats[] = 0;
			// $request->setBlockCats($blockCats);
			// $request->setCatId(0);
			$custom_parameters = $this->setCustom();
			$request->setCustomParameters($custom_parameters); // 自定义参数
			// $request->setGoodsImgType(0);
			// $goodsSignList = array();
			// $goodsSignList[] = 'str';
			// $request->setGoodsSignList($goodsSignList);
			// $request->setIsBrandGoods(false);
			$request->setKeyword($q);
			// $request->setListId('str');
			// $request->setMerchantType(0);
			// $merchantTypeList = array();
			// $merchantTypeList[] = 0;
			// $request->setMerchantTypeList($merchantTypeList);
			$request->setOptId($opt_id);
			$request->setPage($page);
			$request->setPageSize(20);
			$request->setPid($pid);
			// $rangeList = array();
			// $item = new PddDdkGoodsSearchRequest_RangeListItem();
			// $item->setRangeFrom(0);
			// $item->setRangeId(0);
			// $item->setRangeTo(0);
			// $rangeList[] = $item;
			// $request->setRangeList($rangeList);
			// $request->setSortType(0);
			// $request->setUseCustomized(false);
			$request->setWithCoupon(false); // 是否只返回优惠券的商品，false返回所有商品，true只返回有优惠券的商品
			try {
				$response = $client->syncInvoke($request, Config::$accessToken);
			} catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
				echo $e->getMessage();
				exit;
			}
			$content = $response->getContent();
			if (isset($content['error_response'])) {
				echo "异常返回";
			}
			echo json_encode($content, JSON_UNESCAPED_UNICODE);
			// ajax_json(1, $content);
		}
	}

	// 商品推荐 pdd.ddk.goods.recommend.get
	// 本接口用于查询进宝各推荐频道的商品（如实时榜单）（入参channel_type：5-实时热销榜,6-实时收益榜,7-今日畅销榜，默认5
	public function goodsRecommendGet()
	{
		if (IS_POST) {
			$goodSign = $this->input->post('goodSign');
			if (empty($goodSign)) {
				$goodSign = '';
			}
			$page = $this->input->post('page');
			if (empty($page)) {
				$page = 1;
			}
			$channelType = $this->input->post('channelType');
			if (empty($channelType)) {
				$channelType = 1;
			}
			$Offset = $page -1;
			global $pid;
			$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);
			$request = new PddDdkGoodsRecommendGetRequest();
			// $activityTags = array();
			// $activityTags[] = 0;
			// $request->setActivityTags($activityTags);
			// $request->setCatId(0);
			$request->setChannelType($channelType); // 1-今日销量榜,3-相似商品推荐,4-猜你喜欢(和进宝网站精选一致),5-实时热销榜,6-实时收益榜。默认值5
			$custom_parameters = $this->setCustom();
			$request->setCustomParameters($custom_parameters); // 自定义参数
			// $request->setGoodsImgType();  
			$goodsSignList = array();
			$goodsSignList[] = $goodSign;
			$request->setGoodsSignList($goodsSignList); // 商品goodsSign列表，相似商品推荐场景时必传
			$request->setLimit(20); // 一页请求数量；默认值 ： 20
			// $request->setListId('str');
			$request->setOffset($Offset);
			$request->setPid($pid);
			try {
				$response = $client->syncInvoke($request, Config::$accessToken);
			} catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
				echo $e->getMessage();
				exit;
			}
			$content = $response->getContent();
			if (isset($content['error_response'])) {
				echo "异常返回";
			}
			echo json_encode($content, JSON_UNESCAPED_UNICODE);
		}
	}

	// 商品详情：pdd.ddk.goods.detail
	// 本接口用于查询商品详情信息（商品标题、描述、金额等字段）。
	public function goodsDetail()
	{
		if (IS_POST) {
			$goodSign = $this->input->post('goodSign');
			if (empty($goodSign)) {
				ajax_json('-1', '', '参数错误');
			}
			$searchId = $this->input->post('searchId');
			if(empty($searchId)){
				$searchId = '';
			}
			global $pid;
			$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);
			$request = new PddDdkGoodsDetailRequest();
			$custom_parameters = $this->setCustom();
			$request->setCustomParameters($custom_parameters); // 自定义参数，为链接打上自定义标签
			$request->setGoodsImgType(0);
			$request->setGoodsSign($goodSign);
			$request->setNeedSkuInfo(false);
			$request->setPid($pid);
			$request->setSearchId($searchId);
			$request->setZsDuoId(0);
			try {
				$response = $client->syncInvoke($request, Config::$accessToken);
			} catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
				echo $e->getMessage();
				exit;
			}
			$content = $response->getContent();
			if (isset($content['error_response'])) {
				echo "异常返回";
			}
			ajax_json(1, $content);
		}
	}

	// 生成普通商品推广链接：pdd.ddk.goods.promotion.url.generate 
	// 本接口用于生成商品推广，即带优惠券、cps结算能力的链接，用户通过您的链接下单且无售后情况，订单佣金将会返回给您。
	public function promotionUrlGenerate()
	{
		if (IS_POST) {
			$goodSign = $this->input->post('goodSign');
			if (empty($goodSign)) {
				ajax_json('-1', '', '参数错误');
			}
			$material_id = $this->input->post('materialId'); // 商品素材id 
			if (empty($material_id)) {
				$material_id = '';
			}
			$searchId = $this->input->post('searchId');
			if (empty($searchId)) {
				$searchId = '';
			}
			global $pid;
			$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);
			$request = new PddDdkGoodsPromotionUrlGenerateRequest();
			// $request->setCashGiftId(0);
			// $request->setCashGiftName('str');
			$custom_parameters = $this->setCustom();
			$request->setCustomParameters($custom_parameters);
			// $request->setGenerateAuthorityUrl(false);
			$request->setGenerateMallCollectCoupon(false); // 是否生成店铺收藏券推广链接
			$request->setGenerateQqApp(false); 
			$request->setGenerateSchemaUrl(false); // 是否返回 schema URL
			$request->setGenerateShortUrl(true); // 是否生成短链接，true-是，false-否
			$request->setGenerateWeApp(false); // 是否生成拼多多福利券微信小程序推广信息
			$goodsSignList = array();
			$goodsSignList[] = $goodSign;
			$request->setGoodsSignList($goodsSignList);
			$request->setMaterialId($material_id);
			$request->setMultiGroup(false);
			$request->setPId($pid);
			$request->setSearchId($searchId);
			// $request->setZsDuoId(0);
			try {
				$response = $client->syncInvoke($request, Config::$accessToken);
			} catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
				echo $e->getMessage();
				exit;
			}
			$content = $response->getContent();
			if (isset($content['error_response'])) {
				echo "异常返回";
			}
			ajax_json(1, $content);
		}
	}

	// 生成营销工具推广链接：pdd.ddk.rp.prom.url.generate
	// 本接口用于生成多多进宝营销工具的推广链接。（入参channel_type：-1-活动列表；0-默认红包；2–新人红包；3-刮刮卡 ；5-员工内购；6-购物车；7-大促会场；10-生成绑定备案链接）
	public function rpPromUrlGenerate()
	{
		if (IS_POST) {
			$channelType = $this->input->post('ChannelType');
			if (empty($channelType)) {
				$channelType = -1;
			}
			global $pid;
			$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);
			$request = new PddDdkRpPromUrlGenerateRequest();
			// $request->setAmount(200);
			$request->setChannelType($channelType); // 必填：-1-活动列表，0-红包(需申请推广权限)，2–新人红包，3-刮刮卡，5-员工内购，10-生成绑定备案链接，12-砸金蛋，13-一元购C端页面，14-千万补贴B端页面，15-充值中心B端页面，16-千万补贴C端页面，17-千万补贴投票页面，18-一元购B端页面，19-多多品牌星选B端页面，20-多多品牌星选C端页面，23-超级红包，24-礼金全场N折活动B端页面，25-品牌优选B端页面，26-品牌优选C端页面
			$custom_parameters = $this->setCustom();
			$request->setCustomParameters($custom_parameters);
			$diyOneYuanParam = new PddDdkRpPromUrlGenerateRequest_DiyOneYuanParam();
			// $diyOneYuanParam->setGoodsSign('str');
			$request->setDiyOneYuanParam($diyOneYuanParam);
			// $diyRedPacketParam = new PddDdkRpPromUrlGenerateRequest_DiyRedPacketParam();
			// $amountProbability = array();
			// $amountProbability[] = 0;
			// $diyRedPacketParam->setAmountProbability($amountProbability);
			// $diyRedPacketParam->setDisText(false);
			// $diyRedPacketParam->setNotShowBackground(false);
			// $diyRedPacketParam->setOptId(0);
			// $rangeItems = array();
			// $item = new PddDdkRpPromUrlGenerateRequest_DiyRedPacketParamRangeItemsItem();
			// $item->setRangeFrom(0);
			// $item->setRangeId(0);
			// $item->setRangeTo(0);
			// $rangeItems[] = $item;
			// $diyRedPacketParam->setRangeItems($rangeItems);
			// $request->setDiyRedPacketParam($diyRedPacketParam);
			$request->setGenerateQqApp(false);
			$request->setGenerateSchemaUrl(false);
			$request->setGenerateShortUrl(false);
			$request->setGenerateWeApp(false);
			$pIdList = array();
			$pIdList[] = $pid;
			$request->setPIdList($pIdList);
			// $request->setScratchCardAmount(0);
			try {
				$response = $client->syncInvoke($request, Config::$accessToken);
			} catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
				echo $e->getMessage();
				exit;
			}
			$content = $response->getContent();
			if (isset($content['error_response'])) {
				echo "异常返回";
			}
			echo json_encode($content, JSON_UNESCAPED_UNICODE);
		}
	}

	// 生成商城推广链接：pdd.ddk.cms.prom.url.generate
	//本接口用于生成商城推广链接
	public function cmsPromUrlGenerate()
	{
		if (IS_POST) {
			$channelType = $this->input->post('ChannelType');
			if (empty($channelType)) {
				$channelType = '';
			}
			global $pid;
			$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);
			$request = new PddDdkCmsPromUrlGenerateRequest();
			$request->setChannelType($channelType); // 0, "1.9包邮"；1, "今日爆款"； 2, "品牌清仓"； 4,"PC端专属商城"；不传值为默认商城
			$custom_parameters = $this->setCustom();
			$request->setCustomParameters($custom_parameters);
			$request->setGenerateMobile(false);
			$request->setGenerateSchemaUrl(false);
			$request->setGenerateShortUrl(false);
			$request->setGenerateWeApp(false);
			$request->setKeyword('str');
			$request->setMultiGroup(false);
			$pIdList = array();
			$pIdList[] = $pid;
			$request->setPIdList($pIdList);
			try {
				$response = $client->syncInvoke($request, Config::$accessToken);
			} catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
				echo $e->getMessage();
				exit;
			}
			$content = $response->getContent();
			if (isset($content['error_response'])) {
				echo "异常返回";
			}
			echo json_encode($content, JSON_UNESCAPED_UNICODE);
		}
	}

	// 生成拼多多主站频道推广链接 ：pdd.ddk.resource.url.gen
	// 本接口用于进行平台大促活动（如618、双十一活动）、平台优惠频道转链（电器城、限时秒杀等）（入参resource_type：4-限时秒杀,39997-充值中心, 39998-活动转链，39999-电器城，39996-百亿补贴，40000-领券中心）
	public function resourceUrlGen()
	{
		if (IS_POST) {
			$channelType = $this->input->post('ChannelType');
			if (empty($channelType)) {
				$channelType = 4;
			}
			global $pid;
			$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);
			$request = new PddDdkResourceUrlGenRequest();
			$custom_parameters = $this->setCustom();
			$request->setCustomParameters($custom_parameters);
			$request->setGenerateWeApp(false);
			$request->setPid($pid);
			$request->setResourceType($channelType); //4-限时秒杀,39997-充值中心, 39998-活动转链，39996-百亿补贴，39999-电器城，40000-领券中心，50005-火车票
			// $request->setUrl('str');
			try {
				$response = $client->syncInvoke($request, Config::$accessToken);
			} catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
				echo $e->getMessage();
				exit;
			}
			$content = $response->getContent();
			if (isset($content['error_response'])) {
				echo "异常返回";
			}
			echo json_encode($content, JSON_UNESCAPED_UNICODE);
		}
	}

	//单品推广转链：pdd.ddk.goods.zs.unit.url.gen
	// 本接口用于将其他推广者的单品推广链接直接转换为自己的，如果您的推广场景为采集群，可直接使用此接口
	public function goodsZsUnitUrlGen()
	{
		if (IS_POST) {
			$url = $this->input->post('url');
			if (empty($url)) {
				ajax_json('-1', '', '请输入链接');
			}
			global $pid;
			$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);
			$request = new PddDdkGoodsZsUnitUrlGenRequest();
			$custom_parameters = $this->setCustom();
			$request->setCustomParameters($custom_parameters);
			$request->setPid($pid);
			$request->setSourceUrl($url);
			try {
				$response = $client->syncInvoke($request, Config::$accessToken);
			} catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
				echo $e->getMessage();
				exit;
			}
			$content = $response->getContent();
			if (isset($content['error_response'])) {
				echo "异常返回";
			}
			ajax_json(1, $content);
		}
	}

	// 按支付时间段查询订单：pdd.ddk.order.list.range.get
	// 本接口用于按订单支付时间查询订单，一般情况下，用pdd.ddk.order.list.increment.get接口同步即可，在每月月结等有大量订单发生更新的情况，如使用pdd.ddk.order.list.increment.get接口同步压力较大，可更换为此接口同步。
	public function orderListRangeGet()
	{
		if (IS_POST) {
			$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);

			$request = new PddDdkOrderListRangeGetRequest();

			$request->setCashGiftOrder(false);
			$request->setEndTime('str');
			$request->setLastOrderId('str');
			$request->setPageSize(0);
			$request->setQueryOrderType(0);
			$request->setStartTime('str');
			try {
				$response = $client->syncInvoke($request, Config::$accessToken);
			} catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
				echo $e->getMessage();
				exit;
			}
			$content = $response->getContent();
			if (isset($content['error_response'])) {
				echo "异常返回";
			}
			echo json_encode($content, JSON_UNESCAPED_UNICODE);
		}
	}

	//查询订单详情：pdd.ddk.order.detail.get
	//本接口用于查询单笔订单详情，接口场景：当您出现疑似丢单情况，即用户产生的订单在您的订单库或者接口里没有捞取到，此时，您可用这个接口进行验证，传入该笔订单号，若返回的所有字段皆不为空，则该笔订单归属为你，您可再次通过订单接口捞取确认；若返回部分字段为空，则该笔订单不归属于您
	public function orderDetailGet()
	{
		if (IS_POST) {
			$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);

			$request = new PddDdkOrderDetailGetRequest();

			$request->setOrderSn('str');
			$request->setQueryOrderType(0);
			try {
				$response = $client->syncInvoke($request, Config::$accessToken);
			} catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
				echo $e->getMessage();
				exit;
			}
			$content = $response->getContent();
			if (isset($content['error_response'])) {
				echo "异常返回";
			}
			echo json_encode($content, JSON_UNESCAPED_UNICODE);
		}
	}
}
