<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0, user-scalable=no"/>
<title>在线刻章后台管理系统</title>

<link rel="stylesheet" type="text/css" href="css/demo.css">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/basictable.css" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="alternate icon" type="image/png" href="resources/assets/i/favicon.png">
  <link rel="stylesheet" href="resources/assets/css/amazeui.min.css"/>
    <script type="text/javascript">
        function wta(){
            if (confirm("确认删除吗?")){
                return true;
            }
            else{
                return false;
            }
        }
            function fh(id) {
                var str = prompt("请输入快递单号,最好在前面加上快递公司名", "");
                if (str) {
                    window.location.href="http://www.nbzxapi.com/zxkz/ht/dan.php?id="+id+"&kd="+str+"";
                }
            }
    </script>
</head>
<body>
<div class="container">
	<div id="page">
    <h1>在线刻章-后台管理</h1>
    <p>兼容PC和手机双平台</p>
<?php
("Content-type: text/html;charset=utf-8");
if ($_POST['yid']=='zxkz'&&$_POST['pwd']=='linzunrunliuye888'||$_SESSION['yid']=='zxkz'&&$_SESSION['pwd']=='linzunrunliuye888'){
    if(!isset($_SESSION['yid'])) {
        $_SESSION['yid'] = $_POST['yid'];
        if(!isset($_SESSION['pwd'])) {
            $_SESSION['pwd'] = $_POST['pwd'];
        }
    }
	$connID = mysqli_connect('127.0.0.1','root','nbzx0769')or die("网络错误");
	mysqli_query($connID,"set names utf8");
	mysqli_select_db($connID,'zxkz')or die("网络错误");
	$sql = "select * from skh";
	$cxjg = mysqli_query($connID,$sql);
    echo '<form action="dan.php" id="kdd" name="kdd"> 
        <table id="table">
			<thead>
			<tr>
			<th>编号</th>
			<th>姓名</th>
			<th>电话</th>
			<th>刻章类型</th>
			<th>刻章材质</th>
			<th>收件方式</th>
			<th>省份</th>
			<th>地级市</th>
			<th>市、县级市</th>
			<th>详细地址</th>
			<th>时间</th>
			<th>快递单号</th>
			<th>公司名称</th>
			<th>法人名称</th>
            <th>操作</th>
			</tr>
			</thead>
			<tbody>';
	while($row=mysqli_fetch_array($cxjg)){
            echo '
				<tr>
				<td> '.$row[0].'</td>
				<td> '.$row[1].'</td>
				<td> '.$row[2].'</td>
				<td> '.$row[3].'</td>
				<td> '.$row[4].'</td>
				<td> '.$row[10].'</td>
				<td> '.$row[5].'</td>
				<td> '.$row[6].'</td>
				<td> '.$row[7].'</td>
				<td> '.$row[8].'</td>
				<td> '.$row[9].'</td>
				<td>'.$row[11].'</td>
				<td>'.$row[12].'</td>
				<td>'.$row[13].'</td>
               <td><a href="sc.php?sno='.$row[0].'" onclick="return wta()">删除</a><a onclick="fh('.$row[0].')" style="cursor: pointer;">发货</a></td>
				</tr>
				';//发货加了一个CSS,代表着鼠标到那里就变成手型,因为发货没有设置href.
        }
    echo'
			</tbody>
			</table>
			</form>
			';

}
else echo "<script>alert('账号密码错误或登录超时,请重新登录');</script>
<meta http-equiv=\"refresh\" content=\"0;url=sht.html\">";
?>
</div>
</div>
<script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.basictable.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
	  $('#table').basictable();

	  $('#table-breakpoint').basictable({
		breakpoint: 768
	  });

	  $('#table-swap-axis').basictable({
		swapAxis: true
	  });

	  $('#table-force-off').basictable({
		forceResponsive: true
	  });

	  $('#table-no-resize').basictable({
		noResize: true
	  });

	  $('#table-two-axis').basictable();

	  $('#table-max-height').basictable({
		tableWrapper: true
	  });
	});
  </script>
</body>
</html>