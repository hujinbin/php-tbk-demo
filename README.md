# php-tbk-demo
淘宝客商城demo，PHP CodeIgniter 框架集成 淘宝的sdk，在线体验：https://coupon.leheavengame.com


修改 application\config\constants.php 配置 淘宝，京东和拼多多的 推广位信息


淘宝配置：
application\controllers\Taobao.php 配置淘宝 appkey 和 secretKey


拼多多配置:
拼多多sdk 
sdk\pdd-sdk\Config.php 

```
static public $clientId = "";
static public $clientSecret = "";
```