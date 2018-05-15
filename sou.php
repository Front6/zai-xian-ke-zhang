<!DOCTYPE html>
<!-- 作者：mark_wang  QQ:275881702 -->
<html><head>
    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.2.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/demo.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />
    <title>在线刻章填表</title>
</head>
<link rel="stylesheet" type="text/css" href="css/style.css">
<!-- 从官方下载的文件放在项目的 jquery-mobile 目录中 -->
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
<script src="js/classie.js"></script>
<script>
    (function() {
        // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
        if (!String.prototype.trim) {
            (function() {
                // Make sure we trim BOM and NBSP
                var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
                String.prototype.trim = function() {
                    return this.replace(rtrim, '');
                };
            })();
        }

        [].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
            // in case the input is already filled..
            if( inputEl.value.trim() !== '' ) {
                classie.add( inputEl.parentNode, 'input--filled' );
            }

            // events:
            inputEl.addEventListener( 'focus', onInputFocus );
            inputEl.addEventListener( 'blur', onInputBlur );
        } );

        function onInputFocus( ev ) {
            classie.add( ev.target.parentNode, 'input--filled' );
        }

        function onInputBlur( ev ) {
            if( ev.target.value.trim() === '' ) {
                classie.remove( ev.target.parentNode, 'input--filled' );
            }
        }
    })();
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
<body>
<div id="container" style="color: #9900CC">
    <h2 align="center">订单查询</h2>
    <form method="get" action="sou.php">
				<span class="input input--kaede">
					<input class="input__field input__field--kaede" type="text" name="dhc" id="input-35" />
					<label class="input__label input__label--kaede" for="input-35">
						<span class="input__label-content input__label-content--kaede">请输入手机号码进行查询<button class="address_sub2">查询</button> </span>
				</span>
    </form>
</div>
<div id="php" align="center">
<?php
if(isset($_GET['dhc'])){
    $con = mysqli_connect('127.0.0.1','root','nbzx0769','zxkz','3306')or die("<script>alert('您的网络似乎出了点问题3');window.location.href='http://www.nbzxapi.com/zxkz/sou.php';</script>");
    mysqli_query($con,'set names UTF8')or die("<script>alert('您的网络似乎出了点问题1');window.location.href='http://www.nbzxapi.com/zxkz/sou.php';</script>");
    $cx=$_GET['dhc'];
    $sql = "select sjr,kzt,cz,kd from skh where dh=".$cx;
    $cxjg = mysqli_query($con,$sql)or die("<h2 align=\"center\">没查询到您的订单,请检查手机号码是否输入正确</h2>");
        while ($row = mysqli_fetch_all($cxjg)) {
            foreach($row as $key => $value) {
                echo '<span style="font-weight: bold;float: left;">查询结果'.($key+1).'</span><br><br>';
                echo '收件人：'.$value[0].'<br>';
                echo '刻章类型：'.$value[1].'<br>';
                echo '刻章材质：'.$value[2].'<br>';
                $d = 1;
                if($value[3]){
                    echo '已发货,快递单号'.$value[3];
                }else{
                    echo '暂未发货,我们会尽快安排发货的';
                }
                echo '<br><br>';
            }
        }
        if(!isset($d)){
            echo "<h2 align=\"center\">没查询到您的订单,请检查手机号码是否输入正确</h2>";
        }
    }
    mysqli_close($con);

?>
</div>
</body>

<!-- <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script> -->
<SCRIPT src="js/jquery-1.7.1.min.js" type="text/javascript"></SCRIPT>
<script language="javascript" src="js/jquery.gcjs.js"></script>
</html>