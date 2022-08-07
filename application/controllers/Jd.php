<?php
defined('BASEPATH') or exit('No direct script access allowed');

include "sdk/jd-sdk-2.0/jd/JdClient.php"; //请求文件
include "sdk/jd-sdk-2.0/jd/request/UnionOpenGoodsJingfenQueryRequest.php"; //京粉精选商品查询接口
include "sdk/jd-sdk-2.0/jd/request/UnionOpenGoodsQueryRequest.php"; //关键词商品查询接口
include "sdk/jd-sdk-2.0/jd/request/UnionOpenGoodsMaterialQueryRequest.php"; //猜你喜欢商品推荐
include "sdk/jd-sdk-2.0/jd/request/UnionOpenGoodsPromotiongoodsinfoQueryRequest.php"; //根据skuid查询商品信息接口

date_default_timezone_set('Asia/Shanghai');
$c = new JdClient();
$c->appKey = jdAppKey;
$c->appSecret = jdAppSecret;
$c->serverUrl = jd_SERVER_URL;

class Jd extends CI_Controller
{
    function __construct()
    {
        header("Content-Type: text/html;charset=utf-8");
        parent::__construct();
    }

    // 检验登录
    public function checkLogin()
    {
        $jd_accessToken = "";
        if (empty($jd_accessToken)) {
            ajax_json('jdFail');
        }
        return $jd_accessToken;
    }
    // 前往登录
    public function goLogin()
    {
        jump_url("https://oauth.jd.com/oauth/authorize?response_type=code&client_id=7180937EB102F194D859D76CA2496DDC&redirect_uri=http://coupon.leheavengame.com/jd/login");
    }
    // -------------------------  【推广物料】-------------------------------

    //  京粉精选商品查询接口
    //京东联盟精选优质商品，每日更新，可通过频道ID查询各个频道下的精选商品。用获取的优惠券链接调用转链接口时，
    // 需传入搜索接口link字段返回的原始优惠券链接，切勿对链接进行任何encode、decode操作，
    // 否则将导致转链二合一推广链接时校验失败
    public function goodsJingfenQuery()
    {
        if (IS_POST) {
            $eliteId = $this->input->post('eliteId');  // 频道id
            if(empty($eliteId)){
                $eliteId =1;
            }
            $page = $this->input->post('page');
            $page = $page ? $page : 1;
            global $c;
            $accessToken = $this->checkLogin();
            $c->accessToken = $accessToken;
            $req = new UnionOpenGoodsJingfenQueryRequest();
            $goodsReq = array(
                'eliteId'=> $eliteId,
                'pageIndex'=> $page,
            );
            $req->setGoodsReq($goodsReq);
            $req->setVersion("1.0");
            $resp = $c->execute($req, $c->accessToken);
            ajax_json(1, $resp);
        }
    }

    // 关键词商品查询接口
    // 查询商品及优惠券信息，返回的结果可调用转链接口生成单品或二合一推广链接。支持按SKUID、关键词、优惠券基本属性、
    // 是否拼购、是否爆款等条件查询，建议不要同时传入SKUID和其他字段，以获得较多的结果。支持按价格、佣金比例、佣金、
    // 引单量等维度排序。用优惠券链接调用转链接口时，需传入搜索接口link字段返回的原始优惠券链接，切勿对链接进行任何encode、
    // decode操作，否则将导致转链二合一推广链接时校验失败。
    public function goodQuery()  // 无访问权限
    {
        if (IS_POST) {
            global $c;
            $accessToken = $this->checkLogin();
            $c->accessToken = $accessToken;
            $req = new UnionOpenGoodsQueryRequest();
            $goodsReqDTO = array();
            $goodsReqDTO['isCoupon'] = 1; // isCouponNumber 否1 是否是优惠券商品，1：有优惠券
            $req->setGoodsReqDTO($goodsReqDTO);
            $req->setVersion("1.0");
            $resp = $c->execute($req, $c->accessToken);
            ajax_json(1, $resp);
        }
    }

    // 猜你喜欢商品推荐
    public function goodMaterialQuery()
    {
        if (IS_POST) {
            global $c;
            $accessToken = $this->checkLogin();
            $c->accessToken = $accessToken;
            $req = new UnionOpenGoodsMaterialQueryRequest();
            $goodsReq = array();
            $goodsReq["eliteId"] = 1; // 频道ID：1.猜你喜欢、2.实时热销、3.大额券、4.9.9包邮、1001.选品库
            $req->setGoodsReq($goodsReq);
            $req->setVersion("1.0");
            $resp = $c->execute($req, $c->accessToken);
            ajax_json(1, $resp);
        }
    }

    // 根据skuid查询商品信息接口
    public function goodPromotiongoodsinfoQuery()
    {
        if (IS_POST) {
            $skuId = $this->input->post('skuId');  // 商品id
            if(empty($skuId)){
                ajax_json(-1, '', '参数错误');
            }
            global $c;
            $accessToken = $this->checkLogin();
            $c->accessToken = $accessToken;
            $req = new UnionOpenGoodsPromotiongoodsinfoQueryRequest();
            $req->setSkuIds($skuId);
            $req->setVersion("1.0");
            $resp = $c->execute($req, $c->accessToken);
            ajax_json(1, $resp);
        }
    }

    // 商品类目查询接口
    public function categorGoodsGet()
    {
        include "sdk/jd-sdk-2.0/jd/request/UnionOpenCategoryGoodsGetRequest.php";
        if (IS_POST) {
            global $c;
            $accessToken = $this->checkLogin();
            $c->accessToken = $accessToken;
            $req = new UnionOpenCategoryGoodsGetRequest();
            $reqArray = array();
            $reqArray['parentId'] =0;
            $reqArray['grade'] = 0;
            $req->setReq($reqArray);
            $req->setVersion("1.0");
            $resp = $c->execute($req, $c->accessToken);
            ajax_json(1, $resp);
        }
    }

    // 商品详情查询接口
    public function goodsBigfieldQuery()
    {
        include "sdk/jd-sdk-2.0/jd/request/UnionOpenGoodsBigfieldQueryRequest.php";
        if (IS_POST) {
            global $c;
            $accessToken = $this->checkLogin();
            $c->accessToken = $accessToken;
            $req = new UnionOpenGoodsBigfieldQueryRequest();
            $goodsReq = array();
            $req->setGoodsReq($goodsReq);
            $req->setVersion("1.0");
            $resp = $c->execute($req, $c->accessToken);
            ajax_json(1, $resp);
        }
    }

    // 活动查询接口
    public function activityQuery()
    {
        include "sdk/jd-sdk-2.0/jd/request/UnionOpenActivityQueryRequest.php";
        if (IS_POST) {
            global $c;
            $accessToken = $this->checkLogin();
            $c->accessToken = $accessToken;
            $req = new UnionOpenActivityQueryRequest();
            $activityReq = array();
            $req->setActivityReq($activityReq);
            $req->setVersion("1.0");
            $resp = $c->execute($req, $c->accessToken);
            ajax_json(1, $resp);
        }
    }
    //  ----------------------------【转链能力】  ----------------------------
    
    // 网站/APP来获取的推广链接
    public function promotioCommonGet()  // 申请权限
    {
        include "sdk/jd-sdk-2.0/jd/request/UnionOpenPromotionCommonGetRequest.php";
        if (IS_POST) {
            global $c;
            $accessToken = $this->checkLogin();
            $c->accessToken = $accessToken;
            $req = new UnionOpenPromotionCommonGetRequest();
            $promotionCodeReq = array();
            $req->setPromotionCodeReq($promotionCodeReq);
            $req->setVersion("1.0");
            $resp = $c->execute($req, $c->accessToken);
            ajax_json(1,$resp);
        }
    }

    //  ----------------------------【推广效果】  ----------------------------

    // 订单行查询接口
    // 查询推广订单及佣金信息，可查询最近90天内下单的订单，会随着订单状态变化同步更新数据。支持按下单时间、完成时间或更新时间查询。
    // 建议按更新时间每分钟调用一次，查询最近一分钟的订单更新数据。支持查询subunionid、推广位、PID参数，支持普通推客及工具商推客订单查询
    public function orderRowQuery() 
    {
        include "sdk/jd-sdk-2.0/jd/request/UnionOpenOrderRowQueryRequest.php";
        if (IS_POST) {
            global $c;
            $accessToken = $this->checkLogin();
            $c->accessToken = $accessToken;
            $req = new UnionOpenOrderRowQueryRequest();
            $orderReq = array(
                'pageIndex'=>1,
                'pageSize'=> 100,
                'type'=>3,
                'startTime'=> '2022-02-08 20:23:00',
                'endTime' => '2022-02-08 21:23:00',
            );
            $req->setOrderReq($orderReq);
            $req->setVersion("1.0");
            $resp = $c->execute($req, $c->accessToken);
            ajax_json(1, $resp);
        }
    }
}
