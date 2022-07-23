<?php
defined('BASEPATH') or exit('No direct script access allowed');

include "sdk/taobao-sdk-PHP/TopSdk.php";
date_default_timezone_set('Asia/Shanghai');
$c = new TopClient;
$c->appkey = '';
$c->secretKey = '';
$platform = '1';   //链接形式：1：PC，2：无线，默认：１

class Taobao extends CI_Controller
{
    // ( 好券清单API【导购】 )
    public function index()
    {
        if (IS_POST) {
            global $c;
            $req = new TbkDgItemCouponGetRequest;
            $req->setAdzoneId("123");
            $req->setPlatform("1");
            $req->setCat("16,18");
            $req->setPageSize("1");
            $req->setQ("女装");
            $req->setPageNo("1");
            $resp = $c->execute($req);
            ajax_json('1', $resp);
        }
    }
    //( 淘宝客商品查询 )
    public function getItem()
    {
        if (IS_POST) {
            // 接收参数
            $q = $this->input->post('q');  //搜索名称
            if (empty($q)) {
                ajax_json('-1', '', '请先输入关键词');
            }
            global $c;
            $req = new TbkItemGetRequest;
            $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick,coupon_share_url,coupon_amount,coupon_click_url");
            $req->setQ($q);
            $req->setSort("tk_rate_des");
            $req->setStartPrice("100");
            $req->setEndPrice("1");
            $resp = $c->execute($req);
            ajax_json('1', $resp);
        }
    }
    // ( 淘宝客商品详情（简版） )    现用
    public function getItemInfo()
    {
        if (IS_POST) {
            $ids = $this->input->post('id');  //搜索名称
            if (empty($ids)) {
                ajax_json('-1', '', '参数错误');
            }
            $platform = $this->input->post('platform');
            if (empty($platform)) {
                $platform = 2;
            }
            global $c;
            $req = new TbkItemInfoGetRequest;
            $req->setNumIids($ids);
            $req->setPlatform($platform);
            $req->setIp($_SERVER['REMOTE_ADDR']);
            $resp = $c->execute($req);
            ajax_json('1', $resp);
        }
    }
    // ( 淘宝客店铺查询 )
    public function getShop()
    {
        global $c;
        $req = new TbkShopGetRequest;
        $req->setFields("user_id,shop_title,shop_type,seller_nick,pict_url,shop_url");
        $req->setQ("女装");
        $req->setSort("commission_rate_des");
        $req->setIsTmall("false");
        $req->setStartCredit("1");
        $req->setEndCredit("20");
        $req->setStartCommissionRate("2000");
        $req->setEndCommissionRate("123");
        $req->setStartTotalAction("1");
        $req->setEndTotalAction("100");
        $req->setStartAuctionCount("123");
        $req->setEndAuctionCount("200");
        $req->setPlatform("1");
        $req->setPageNo("1");
        $req->setPageSize("20");
        $resp = $c->execute($req);
        echo json_encode($resp);
    }
    // ( 淘宝客店铺关联推荐查询 )
    public function getShopRecommend()
    {
        global $c;
        $req = new TbkShopRecommendGetRequest;
        $req->setFields("user_id,shop_title,shop_type,seller_nick,pict_url,shop_url");
        $req->setUserId("123");
        $req->setCount("20");
        $req->setPlatform("1");
        $resp = $c->execute($req);
        echo json_encode($resp);
    }
    // ( 获取淘宝联盟选品库的宝贝信息 )
    public function getFavoritesItem()
    {
        global $c;
        $req = new TbkUatmFavoritesItemGetRequest;
        $req->setPlatform("1");
        $req->setPageSize("20");
        $req->setAdzoneId("34567");
        $req->setUnid("3456");
        $req->setFavoritesId("10010");
        $req->setPageNo("2");
        $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick,shop_title,zk_final_price_wap,event_start_time,event_end_time,tk_rate,status,type");
        $resp = $c->execute($req);
        echo json_encode($resp);
    }
    // 获取淘宝联盟选品库列表
    public function getFavorites()
    {
        global $c;
        $req = new TbkUatmFavoritesGetRequest;
        $req->setPageNo("1");
        $req->setPageSize("20");
        $req->setFields("favorites_title,favorites_id,type");
        $req->setType("1");
        $resp = $c->execute($req);
        echo json_encode($resp);
    }
    // ( 淘抢购api )
    public function getTqg()
    {
        global $c;
        $req = new TbkJuTqgGetRequest;
        $req->setAdzoneId("1");
        $req->setFields("click_url,pic_url,reserve_price,zk_final_price,total_amount,sold_num,title,category_name,start_time,end_time");
        $req->setStartTime("2016-08-09 09:00:00");
        $req->setEndTime("2016-08-09 16:00:00");
        $req->setPageNo("1");
        $req->setPageSize("40");
        $resp = $c->execute($req);
        echo json_encode($resp);
    }
    // ( 链接解析api )
    public function extractClick()
    {
        if (IS_POST) {
            global $c;
            $url = $this->input->post('url');
            if (empty($url)) {
                ajax_json('-1', '', '参数错误');
            }
            $req = new TbkItemClickExtractRequest;
            $req->setClickUrl($url);
            $resp = $c->execute($req);
            ajax_json('1', $resp);
        }
    }
    // 自己获取商品id   //  现用
    public function extractGetGoodId()
    {
        if (IS_POST) {
            global $c;
            $url = $this->input->post('url');
            if (empty($url)) {
                ajax_json('-1', '', '参数错误');
            }
            $id = $this->get_redirect_url($url);
            global $c;
            // 淘宝客-公用-淘宝客商品详情查询(简版)
            $req = new TbkItemInfoGetRequest;
            $req->setNumIids($id);
            $resp = $c->execute($req);
            $resp['goodId'] = $id;
            ajax_json('1', $resp);
        }
    }
    // 打开网页获取商品id
    function get_redirect_url($url)
    {
        $weather = curl_init();
        curl_setopt($weather, CURLOPT_URL, $url);
        curl_setopt($weather, CURLOPT_SSL_VERIFYPEER, false); //如果接口URL是https的,我们将其设为不验证,如果不是https的接口,这句可以不用加
        curl_setopt($weather, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($weather);
        curl_close($weather);
        $start = 'var url';
        $end = '.htm?';
        $str = substr($data, strlen($start) + strpos($data, $start), (strlen($data) - strpos($data, $end)) * (-1));
        $id = $this->findNum($str);
        return $id;
    }
    // 提取字符串中的数字
    function findNum($str = '')
    {
        $str = trim($str);
        if (empty($str)) {
            return '';
        }
        $result = '';
        for ($i = 0; $i < strlen($str); $i++) {
            if (is_numeric($str[$i])) {
                $result .= $str[$i];
            }
        }
        return $result;
    }

    // ( 淘宝客商品猜你喜欢 )
    public function likeGuess()
    {
        if (IS_POST) {
            $AdzoneId = $this->input->post('AdzoneId');  //广告位id
            global $c;
            $req = new TbkItemGuessLikeRequest;
            $req->setAdzoneId("123");
            $req->setUserNick("abc");
            $req->setUserId("123456");
            $req->setOs("ios");
            $req->setIdfa("65A509BA-227C-49AC-91EC-DE6817E63B10");
            $req->setImei("641221321098757");
            $req->setImeiMd5("115d1f360c48b490c3f02fc3e7111111");
            $req->setIp("106.11.34.15");
            $req->setUa("Mozilla/5.0");
            $req->setApnm("com.xxx");
            $req->setNet("wifi");
            $req->setMn("iPhone7%2C2");
            $req->setPageNo("1");
            $req->setPageSize("20");
            $resp = $c->execute($req);
            ajax_json('1', $resp, $AdzoneId);
        }
    }
    // ( 淘宝客淘口令 )
    public function createTpwd()
    {
        if (IS_POST) {
            $text = $this->input->post('text'); //口令弹框内容
            $url = $this->input->post('url');  //口令跳转目标页
            $logo = $this->input->post('logo'); //口令弹框logoURL
            if (empty($text) or empty($url) or empty($logo)) {
                ajax_json('-1', '', '参数错误');
            }
            global $c;
            $req = new TbkTpwdCreateRequest;
            $req->setText($text);
            $req->setUrl($url);
            $req->setLogo($logo);
            $resp = $c->execute($req);
            ajax_json('1', $resp);
        }
    }

    // 淘宝客-公用-长链转短链 
    public function getSpread()
    {
        if (IS_POST) {
            $url = $this->input->post('url'); // 转化链接
            if (empty($url)) {
                ajax_json('-1', '', '请输入转化链接');
            }
            global $c;
            $req = new TbkSpreadGetRequest;
            $requests = new TbkSpreadRequest;
            $requests->url = $url;
            $req->setRequests(json_encode($requests));
            $resp = $c->execute($req);
            echo json_encode($resp);
        }
    }
    // ( 淘宝客-推广者-所有订单查询 )   现用
    public function getOrderDetails()
    {
        global $c;

        $end_time = time();
        $start_time = $end_time - (2 * 60); //近2小时订单
        $start_time = date("Y-m-d H:i:s", $start_time);
        $end_time = date("Y-m-d H:i:s", $end_time);
        $req = new TbkOrderDetailsGetRequest;
        $req->setQueryType("4"); //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询，4:按照订单更新时间；
        $req->setPositionIndex("2222_334666");
        $req->setPageSize("20");
        $req->setMemberType("2"); // 推广者角色类型,2:二方，3:三方，不传，表示所有角色
        // $req->setTkStatus("12"); //淘客订单状态，11-拍下未付款，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
        $req->setEndTime($end_time);
        $req->setStartTime($start_time);
        $req->setJumpType("1"); //跳转类型，当向前或者向后翻页必须提供,-1: 向前翻页,1：向后翻页
        $req->setPageNo("1");
        $req->setOrderScene("1"); // 场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单，默认为1
        $resp = $c->execute($req);
        echo json_encode($resp);
    }
    // ( 淘宝客新用户订单API--导购 )
    public function getnNewuserOrdeDb()
    {
        global $c;
        $req = new TbkDgNewuserOrderGetRequest;
        $req->setPageSize("20");
        $req->setAdzoneId(AdzoneId);
        $req->setPageNo("1");
        $req->setStartTime("2020-01-24 00:34:05");
        $req->setEndTime("2020-06-28 18:34:05");
        $req->setActivityId("119013_2");
        $resp = $c->execute($req);
        echo json_encode($resp);
    }
    // ( 淘宝客新用户订单API--社交 )
    public function getnNewuserOrdeSc()
    {
        global $c;
        $req = new TbkDgNewuserOrderGetRequest;
        $req->setPageSize("20");
        $req->setAdzoneId("123");
        $req->setPageNo("1");
        $req->setStartTime("2018-01-24 00:34:05");
        $req->setEndTime("2018-01-24 00:34:05");
        $req->setActivityId("119013_2");
        $resp = $c->execute($req);
        echo json_encode($resp);
    }
    // ( 淘宝客 - 推广者-物料精选 )   // 现用
    public function dgOptimusMaterial()
    {
        if (IS_POST) {
            // 接收参数
            $cart = $this->input->post('cart');  //商品ID，用于相似商品推荐
            $page = $this->input->post('page');  //商品ID，用于相似商品推荐
            if (empty($cart)) {
                $cart = '9660'; // 默认商品id
            }
            if (empty($page)) {
                $page = 1;
            }
            global $c;
            $req = new TbkDgOptimusMaterialRequest;
            $req->setAdzoneId(AdzoneId);
            $req->setMaterialId($cart);
            $req->setPageNo($page);
            // $req->setItemId(intval($id));
            $resp = $c->execute($req);
            ajax_json('1', $resp);
        }
    }
    // ( 通用物料搜索API（导购） )    现用
    public function dgMaterialOptional()
    {
        if (IS_POST) {
            // 搜索
            $q = $this->input->post('q');  //搜索名称
            $platform = $this->input->post('platform');  //搜索名称
            if (empty($q) or empty($platform)) {
                ajax_json('-1', '', '参数错误');
            }
            // 加载更多
            $page = $this->input->post('page');
            if (empty($page)) {
                $page = 1;
            }
            // 排序
            $sort = $this->input->post('sort');
            if (empty($sort)) {
                $sort = 'tk_rate_des';
            }
            global $c;
            $req = new TbkDgMaterialOptionalRequest;
            $req->setPlatform(intval($platform));  //链接形式：1：PC，2：无线，默认：１
            $req->setSort($sort);
            $req->setPageNo($page);
            $req->setHasCoupon("true");
            $req->setQ($q);
            $req->setAdzoneId(AdzoneId);
            $req->setMaterialId('9660');
            $req->setEndKaTkRate("9999");
            $req->setStartKaTkRate("1000");
            $resp = $c->execute($req);
            ajax_json('1', $resp);
        }
    }
    // ( 拉新活动汇总API--导购 )
    public function dgNewuserOrderSum()
    {
        global $c;
        $req = new TbkDgNewuserOrderSumRequest;
        $req->setPageSize("20");
        $req->setAdzoneId("123");
        $req->setPageNo("1");
        $req->setSiteId("123");
        $req->setActivityId("sxxx");
        $req->setSettleMonth("201807");
        $resp = $c->execute($req);
        echo json_encode($resp);
    }
    // ( 拉新活动汇总API--社交 )
    public function scNewuserOrderSum()
    {
        global $c;
        $sessionKey = 'f8288060d47277a9966d5d9eb621dd35';
        $req = new TbkScNewuserOrderSumRequest;
        $req->setAdzoneId(AdzoneId);
        $req->setSiteId(SiteId);
        $resp = $c->execute($req, $sessionKey);
        echo json_encode($resp);
    }
    // ( 淘宝客擎天柱通用物料API - 社交 )
    public function scOptimusMaterial()
    {
        if (IS_POST) {
            global $c;
            $sessionKey = 'f8288060d47277a9966d5d9eb621dd35';
            $req = new TbkScOptimusMaterialRequest;
            $req->setAdzoneId("123");
            $req->setSiteId("111");
            $req->setDeviceType("IMEI");
            $req->setDeviceEncrypt("MD5");
            $req->setDeviceValue("xxx");
            $req->setContentId("323");
            $req->setContentSource("xxx");
            $req->setItemId("33243");
            $resp = $c->execute($req, $sessionKey);
            ajax_json('1', $resp);
        }
    }
    // ( 淘宝联盟官方活动推广API-媒体 )
    public function activitylinkGet()
    {
        global $c;
        $req = new TbkActivitylinkGetRequest;
        $req->setPlatform("1");
        $req->setUnionId("demo");
        $req->setAdzoneId("123");
        $req->setPromotionSceneId("12345678");
        $req->setSubPid("mm_123_123_123");
        $req->setRelationId("23");
        $resp = $c->execute($req);
        echo json_encode($resp);
    }

    // sdk不存在
    // ( 淘宝联盟官方活动推广API-工具 )
    public function scActivitylinkToolget()
    {
        // 生活服务分会场活动ID：1583739244162
        // 饿了么聚合页CPS推广活动ID：20150318019998877，
        // 饿了么微信推广：20150318020002192   (2020双12)
        // 先用后付会场id：20150318020003621
        if (IS_POST) {
            $code = $this->input->post('code');  //广告位id
            if (empty($code) || $code > 4) {
                $code = '1';
            }
            $Enum = array(
                '1' => '1571715733668',  // 饿了么活动ID：1571715733668
                '2' => '1579491209717',  // 饿了么餐饮页面活动ID：1579491209717
                '3' => '1583739244161',  // 口碑主会场活动ID
                '4' => '1585018034441',  // 饿了么新零售页面活动ID
                '5' => '20150318019998877',  // 饿了么聚合页CPS推广活动ID
                '6' => '20150318020002192',  // 饿了么微信推广   (2020双12)
            );
            $activityMaterialId = $Enum[$code];
            global $c;
            $req = new TbkActivityInfoGetRequest;
            $req->setAdzoneId(AdzoneId);
            $req->setSubPid("");
            $req->setRelationId("006");
            $req->setActivityMaterialId($activityMaterialId);
            $req->setUnionId("");
            $resp = $c->execute($req);
            ajax_json('1', $resp);
        }
    }
    // ( 处罚订单查询 -导购-私域用户管理专用 )
    public function dgPunishOrderGet()
    {
        global $c;
        $req = new TbkDgPunishOrderGetRequest;
        $af_order_option = new TopApiAfOrderOption;
        $af_order_option->span = "1200";
        $af_order_option->relation_id = "2222";
        $af_order_option->tb_trade_id = "258897956183171983";
        $af_order_option->tb_trade_parent_id = "258897956183171983";
        $af_order_option->page_size = "1";
        $af_order_option->page_no = "10";
        $af_order_option->start_time = "2018-11-11 00:01:01";
        $af_order_option->special_id = "23132";
        $af_order_option->violation_type = "1";
        $af_order_option->punish_status = "1";
        $req->setAfOrderOption(json_encode($af_order_option));
        $resp = $c->execute($req);
        ajax_json('1', $resp);
    }
    // sdk不存在
}
