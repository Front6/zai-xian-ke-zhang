<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>删除</title>
</head>
<body>
<?php
("Content-type: text/html;charset=utf-8");
$conn=mysqli_connect('127.0.0.1','root','nbzx0769','zxkz') or die("<script> alert('网络错误,请稍后重试');history.back();</script>");
$sc=$_GET['sno'];
mysqli_query($conn,"delete from skh where id='$sc'") or die ("<script> alert('网络错误,请稍后重试');history.back();</script>");
echo "<script>alert('删除成功!');window.location.href='jb.php';</script>";
?>

</body>
</html>