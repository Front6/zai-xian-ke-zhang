<?php
$con = mysqli_connect('127.0.0.1','root','nbzx0769','zxkz')or die("<script>alert('您的网络似乎有点问题1');history.back();</script>");
mysqli_query($con,'set names UTF8')or die("<script>alert('您的网络似乎有点问题2');history.back();</script>");
$sql = "update skh set kd='".$_GET['kd']."' where id=".$_GET['id'];
mysqli_query($con,$sql)or die("<script>alert('您的网络似乎有点问题3');history.back();</script>");
echo "<script>alert('发货成功！');window.location.href='http://www.nbzxapi.com/zxkz/ht/jb.php'</script>"
?>