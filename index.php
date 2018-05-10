<!DOCTYPE html>
<!-- 作者：mark_wang  QQ:275881702 -->
<html><head>
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <link href="css/font-awesome.min.css" rel="stylesheet">
</head>
<?php
header('Content-type:text/html; Charset=utf-8');
$mchid = '1503271911';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
$appid = 'wx1fa1b3cfbe4644b1';  //微信支付申请对应的公众号的APPID
$appKey = '1a94549fe97b7e01edee5e6a6828f4cc';   //微信支付申请对应的公众号的APP Key
$apiKey = 'zxkz963852741zxkz741852963fjf666';   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
//①、获取用户openid
$wxPay = new WxpayService($mchid,$appid,$appKey,$apiKey);
$openId = $wxPay->GetOpenid();      //获取openid
if(!$openId) exit('获取openid失败');
//②、统一下单
$outTradeNo = uniqid();     //你自己的商品订单号
$payAmount = 0.01;          //付款金额，单位:元
$orderName = 'zaixiankezhang';    //订单标题
$notifyUrl = 'http://www.nbzxapi.com/zxkz/phpapi/example/notify.php';     //付款成功后的回调地址(不要有问号)
$payTime = time();      //付款时间
$jsApiParameters = $wxPay->createJsBizPackage($openId,$payAmount,$outTradeNo,$orderName,$notifyUrl,$payTime);
$jsApiParameters = json_encode($jsApiParameters);
?>
<?php
class WxpayService
{
    protected $mchid;
    protected $appid;
    protected $appKey;
    protected $apiKey;
    public $data = null;
    public function __construct($mchid, $appid, $appKey,$key)
    {
        $this->mchid = $mchid; //https://pay.weixin.qq.com 产品中心-开发配置-商户号
        $this->appid = $appid; //微信支付申请对应的公众号的APPID
        $this->appKey = $appKey; //微信支付申请对应的公众号的APP Key
        $this->apiKey = $key;   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
    }
    /**
     * 通过跳转获取用户的openid，跳转流程如下：
     * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
     * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
     * @return 用户的openid
     */
    public function GetOpenid()
    {
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $scheme = $_SERVER['HTTPS']=='on' ? 'https://' : 'http://';
            $baseUrl = urlencode($scheme.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING']);
            $url = $this->__CreateOauthUrlForCode($baseUrl);
            Header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $openid = $this->getOpenidFromMp($code);
            return $openid;
        }
    }
    /**
     * 通过code从工作平台获取openid机器access_token
     * @param string $code 微信跳转回来带上的code
     * @return openid
     */
    public function GetOpenidFromMp($code)
    {
        $url = $this->__CreateOauthUrlForOpenid($code);
        $res = self::curlGet($url);
        //取出openid
        $data = json_decode($res,true);
        $this->data = $data;
        $openid = $data['openid'];
        return $openid;
    }
    /**
     * 构造获取open和access_toke的url地址
     * @param string $code，微信跳转带回的code
     * @return 请求的url
     */
    private function __CreateOauthUrlForOpenid($code)
    {
        $urlObj["appid"] = $this->appid;
        $urlObj["secret"] = $this->appKey;
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }
    /**
     * 构造获取code的url连接
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     * @return 返回构造好的url
     */
    private function __CreateOauthUrlForCode($redirectUrl)
    {
        $urlObj["appid"] = $this->appid;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE"."#wechat_redirect";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }
    /**
     * 拼接签名字符串
     * @param array $urlObj
     * @return 返回已经拼接好的字符串
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign") $buff .= $k . "=" . $v . "&";
        }
        $buff = trim($buff, "&");
        return $buff;
    }
    /**
     * 统一下单
     * @param string $openid 调用【网页授权获取用户信息】接口获取到用户在该公众号下的Openid
     * @param float $totalFee 收款总费用 单位元
     * @param string $outTradeNo 唯一的订单号
     * @param string $orderName 订单名称
     * @param string $notifyUrl 支付结果通知url 不要有问号
     * @param string $timestamp 支付时间
     * @return string
     */
    public function createJsBizPackage($openid, $totalFee, $outTradeNo, $orderName, $notifyUrl, $timestamp)
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        $orderName = iconv('GBK','UTF-8',$orderName);
        $unified = array(
            'appid' => $config['appid'],
            'attach' => 'pay',             //商家数据包，原样返回，如果填写中文，请注意转换为utf-8
            'body' => $orderName,
            'mch_id' => $config['mch_id'],
            'nonce_str' => self::createNonceStr(),
            'notify_url' => $notifyUrl,
            'openid' => $openid,            //rade_type=JSAPI，此参数必传
            'out_trade_no' => $outTradeNo,
            'spbill_create_ip' => '127.0.0.1',
            'total_fee' => intval($totalFee * 100),       //单位 转为分
            'trade_type' => 'JSAPI',
        );
        $unified['sign'] = self::getSign($unified, $config['key']);
        $responseXml = self::curlPost('https://api.mch.weixin.qq.com/pay/unifiedorder', self::arrayToXml($unified));
        $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($unifiedOrder === false) {
            die('parse xml error');
        }
        if ($unifiedOrder->return_code != 'SUCCESS') {
            die($unifiedOrder->return_msg);
        }
        if ($unifiedOrder->result_code != 'SUCCESS') {
            die($unifiedOrder->err_code);
        }
        $arr = array(
            "appId" => $config['appid'],
            "timeStamp" => "$timestamp",        //这里是字符串的时间戳，不是int，所以需加引号
            "nonceStr" => self::createNonceStr(),
            "package" => "prepay_id=" . $unifiedOrder->prepay_id,
            "signType" => 'MD5',
        );
        $arr['paySign'] = self::getSign($arr, $config['key']);
        return $arr;
    }
    public static function curlGet($url = '', $options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public static function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public static function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }
    public static function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }
    protected static function formatQueryParaMap($paraMap, $urlEncode = false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v) {
                if ($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
}
?>
<title>在线刻章填表</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<!-- 从官方下载的文件放在项目的 jquery-mobile 目录中 -->
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
<script>
    function jc() {
        if (form1.fs.value=="yj"){
            if(form1.kzt.value=="") {
                alert("请选择刻章类型！");form1.kzt.focus();return false;
            }
            if(form1.fs.value=="") {
                alert("请选择收件方式！");form1.fs.focus();return false;
            }
            if(form1.realname.value==""||form1.realname.value==" "){
                alert("收件人不能为空！");form1.realname.focus();return false;
            }
            if(form1.mobile.value==""){
                alert("联系电话不能为空！");form1.mobile.focus();return false;
            }
            if(form1.s_province.value=="省份"){
                alert("请选择省份！");return false;
            }
            if(form1.s_city.value=="地级市"){
                alert("请选择地级市！");return false;
            }
            if(form1.s_county.value=="市、县级市"){
                alert("请选择市、县级市！");return false;
            }
            if(form1.address.value=="" || form1.address.value==" "){
                alert("详细地址不能为空");form1.address.focus();return false;
            }
        }
        else{
            if(form1.kzt.value=="") {
                alert("请选择刻章类型！");form1.kzt.focus();return false;
            }
            if(form1.fs.value=="") {
                alert("请选择收件方式！");form1.fs.focus();return false;
            }
            if(form1.realname.value==""||form1.realname.value==" "){
                alert("收件人不能为空！");form1.realname.focus();return false;
            }
            if(form1.mobile.value==""){
                alert("联系电话不能为空！");form1.mobile.focus();return false;
            }
        }
    }
    function sd() {
        if(form1.fs.value=="yj") {
            document.getElementById("dz").style.display = "";
        }
        else{document.getElementById("dz").style.display = "none"};
    }
</script>
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
<style type="text/css">
    body {margin:0px; background:#efefef; font-family:'微软雅黑'; -moz-appearance:none;}
    a {text-decoration:none;}

    .address_addnav {height:44px; width:94%; padding:0 3%; border-bottom:1px solid #f0f0f0; border-top:1px solid #f0f0f0; margin-top:14px; line-height:42px; color:#666; background:#fff;}
    .address_list {height:50px; padding:0 10px;  border-bottom:1px solid #f0f0f0; border-top:1px solid #f0f0f0; margin-top:14px; background:#fff;}
    .address_list .ico {height:50px; width:30px;   float:left; color:#999;margin-right:-30px; z-index:2}
    .address_list .ico i { font-size:24px;margin-top:15px;margin-left:10px;z-index:2;position: relative;}
    .address_list .info {height:50px; width:100%; float:left;position: relative;}
    .address_list .info .inner { margin-left:40px;margin-right:50px;}
    .address_list .info .inner .addr {height:20px; width:100%; color:#999; line-height:26px; font-size:14px; overflow:hidden;}
    .address_list .info .inner .user {height:30px; width:100%; color:#666; line-height:30px; font-size:16px; overflow:hidden;}
    .address_list .info .inner .user span {color:#444; font-size:16px;}
    .address_list .btn { width:45px; float:right;margin-left:-45px;z-index:2;position: relative;}
    .address_list .btn .edit,.address_list .btn .remove {height:50px; float:right; color:#999; font-size:18px;margin-top:5px;}
    .address_list .btn .edit { margin-right:10px;}

    .address_addnav {height:40px;  border-bottom:1px solid #f0f0f0; border-top:1px solid #f0f0f0; margin-top:14px; line-height:40px; color:#666; }
    .address_main {height:auto;width:94%; padding:0px 3%; border-bottom:1px solid #f0f0f0; border-top:1px solid #f0f0f0; margin-top:14px; background:#fff;}
    .address_main .line {height:44px; width:100%; border-bottom:1px solid #f0f0f0; line-height:44px;}

    .address_main .line input {float:left; height:44px; width:100%; padding:0px; margin:0px; border:0px; outline:none; font-size:16px; color:#666;padding-left:5px;}
    .address_main .line select  { border:none;height:25px;width:100%;color:#666;font-size:16px;}
    .address_sub1 {height:44px; margin:14px 10px; background:#ff4f4f; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#fff;}
    .address_sub2 {height:44px; margin:14px 10px; background:#ddd; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#666; border:1px solid #d4d4d4;}

    #BgDiv1{background-color:#000; position:absolute; z-index:9999;  display:none;left:0px; top:0px; width:100%; height:100%;opacity: 0.6; filter: alpha(opacity=60);}
    .DialogDiv{position:absolute;z-index:99999;}/*配送公告*/
    .U-user-login-btn{ display:block; border:none; background:url(images/bg_mb_btn1_1.png) repeat-x; font-size:1em; color:#efefef; line-height:49px; cursor:pointer; height:53px; font-weight:bold;
        border-radius:3px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        width:100%; box-shadow: 0 1px 4px #cbcacf, 0 0 40px #cbcacf ;}
    .U-user-login-btn:hover, .U-user-login-btn:active{ display:block; border:none; background:url(images/bg_mb_btn1_1_h.png) repeat-x; font-size:1em; color:#efefef; line-height:49px; cursor:pointer; height:53px; font-weight:bold;
        border-radius:3px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        width:100%; box-shadow: 0 1px 4px #cbcacf, 0 0 40px #cbcacf ;}
    .U-user-login-btn2{ display:block; border:none;background:url(images/bg_mb_btn1_1_h.png) repeat-x;   font-size:1em; color:#efefef; line-height:49px; cursor:pointer; font-weight:bold;
        border-radius:3px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        width:100%; box-shadow: 0 1px 4px #cbcacf, 0 0 40px #cbcacf ;height:53px;}
    .U-guodu-box { padding:5px 15px;  background:#3c3c3f; filter:alpha(opacity=90); -moz-opacity:0.9; -khtml-opacity: 0.9; opacity: 0.9;  min-heigh:200px; border-radius:10px;}
    .U-guodu-box div{ color:#fff; line-height:20px; font-size:12px; margin:0px auto; height:100%; padding-top:10%; padding-bottom:10%;}
</style>
<div id="container"><div class="page_topbar">
        <div class="title">在线刻章填表</div>
    </div>
    <form name="form1" action="http://www.nbzxapi.com/zxkz/phpapi/example/jsapi.php">
        <div id="container">
            <div class="address_main">
                <input type="hidden" id="addressid" value="">
                <input type="hidden" id="opid" name="opid" value="<?php echo $openId; ?>">
                <div class="line">
                    <select name="kzt" style="background-color: white">
                        <option value="">请选择刻章类型</option>
                        <option value="公章">公章</option>
                        <option value="财务章">财务章</option>
                        <option value="合同章">合同章</option>
						<option value="发票章">发票章</option>
						<option value="法人章">法人章</option>
                    </select>
                </div>
				<div class="line">
					<select name="cz" style="background-color:white" id="cz">
						<option value="">请选择刻章材质</option>
						<option value="有盖铜" style="display:none;">有盖铜</option>
						<option value="无盖铜" style="display:none;">无盖铜</option>
						<option value="回墨印" style="display:none;">回墨印</option>
						<option value="回墨铜" style="display:none;">回墨铜</option>
						<option value="牛角" style="display:none;">牛角</option>
						<option value="光敏" style="display:none;">光敏</option>
						<option value="玉" style="display:none;">玉</option>
						<option value="白牛角" style="display:none;">白牛角</option>
						<option value="仿牛角" style="display:none;">仿牛角</option>
						<option value="精致雕刻木" style="display:none;">精致雕刻木</option>
						<option value="红木" style="display:none;">红木</option>
						<option value="精致雕刻石" style="display:none;">精致雕刻石</option>
					</select>
				</div>
                <div class="line">
                    <select name="fs" onchange="sd()" style="background-color: white">
                        <option value="">请选择收件方式</option>
                        <option value="yj">邮寄</option>
                        <option value="zq">自取</option>
                    </select>
                </div>
                <input type="hidden" id="addressid" value="">
                <input type="hidden" id="spuid" value="3074">
                <div class="line"><input type="text" placeholder="收件人" name="sjr" id="realname"></div>
                <div class="line"><input type="text" placeholder="联系电话,只能为数字" pattern="[0-9]*" name="mobile" id="mobile"></div>
                <div name="dz" id="dz" style="display: none">
                    <div class="line">
                        <!-- sel-provance -->
                        <select id="s_province" name="s_province" style="background-color: white"></select><br>
                    </div>
                    <div class="line">
                        <select id="s_city" name="s_city" style="background-color: white" ></select><br><!-- sel -->
                    </div>
                    <div class="line">
                        <!-- sel-area -->
                        <select id="s_county" name="s_county" style="background-color: white"></select><br>
                    </div>
                    <script type="text/javascript" src="js/area.js"></script>
                    <script type="text/javascript">_init_area();</script>
                    <div class="line"><input type="text" placeholder="详细地址" id="address" name="address"></div>
                </div>
                <center>
                    <input type="submit" class="address_sub2" style="width: 95%;background-color:red;color:white;font-weight:bold" onclick="return jc()" value="确定">
                </center>
                <div id="toastId2" class="toasttj2" style="display: none; opacity: 0;"></div>
                <div id="BgDiv1"></div>
            </div>
        </div>
    </form>
</div>
</body>

<!-- <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script> -->
<SCRIPT src="js/jquery-1.7.1.min.js" type="text/javascript"></SCRIPT>
<script language="javascript" src="js/jquery.gcjs.js"></script>
</html>
