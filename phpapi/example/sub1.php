<?php
session_start();
date_default_timezone_set('PRC');
if ($_SESSION['cod']==504) {
    $con = mysqli_connect("127.0.0.1", "root", "nbzx0769", "zxkz", "3306") or die("<script>alert('网络好像出了点问题');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>");
    mysqli_query($con, 'set names utf8') or die("<script>alert('网络好像出了点问题');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>");
    $sjr = $_GET['sjr'];
    $dh = $_GET['mobile'];
    $kzt = $_GET['kzt'];
    $cz = $_GET['cz'];
    $sj = date("Y-m-d H:i:s");
    if ($_GET['fs'] == "自取") {
		if($_GET['kzt']=="公章"){
		$gs = $_GET['gs'];
        $sql = "insert into skh(sjr,dh,kzt,cz,sj,fs,gs) values('" . $sjr . "','" . $dh . "','" . $kzt . "','" . $cz . "','" . date("Y-m-d H:i:s") . "','自取','".$gs."')";
        mysqli_query($con, $sql) or die("<script>alert('网络好像出了点问题');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>");
        mysqli_close($con);
        unset($_SESSION['cod']);
        echo <<<EOF
        <meta http-equiv="refresh" content="0.1;url=http://www.nbzxapi.com/zxkz/fin.html">
EOF;
		}else if($_GET['kzt']=="法人章"){
		$fname = $_GET['fname'];
        $sql = "insert into skh(sjr,dh,kzt,cz,sj,fs,fname) values('" . $sjr . "','" . $dh . "','" . $kzt . "','" . $cz . "','" . date("Y-m-d H:i:s") . "','自取','".$fname."')";
        mysqli_query($con, $sql) or die("<script>alert('网络好像出了点问题');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>");
        mysqli_close($con);
        unset($_SESSION['cod']);
        echo <<<EOF
        <meta http-equiv="refresh" content="0.1;url=http://www.nbzxapi.com/zxkz/fin.html">
EOF;
		}
		else{
        $sql = "insert into skh(sjr,dh,kzt,cz,sj,fs) values('" . $sjr . "','" . $dh . "','" . $kzt . "','" . $cz . "','" . date("Y-m-d H:i:s") . "','自取')";
        mysqli_query($con, $sql) or die("<script>alert('网络好像出了点问题');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>");
        mysqli_close($con);
        unset($_SESSION['cod']);
        echo <<<EOF
        <meta http-equiv="refresh" content="0.1;url=http://www.nbzxapi.com/zxkz/fin.html">
EOF;
		}
    } else if ($_GET['fs'] == "邮寄") {
		if($_GET['kzt']=="公章"){
        $sf = $_GET['s_province'];
        $djs = $_GET['s_city'];
        $xjs = $_GET['s_county'];
        $dz = $_GET['address'];
		$gs = $_GET['gs'];
        $sql = "insert into skh(sjr,dh,kzt,cz,sf,djs,xjs,dz,sj,fs,gs) values('" . $sjr . "','" . $dh . "','" . $kzt . "','" . $cz . "','" . $sf . "','" . $djs . "','" . $xjs . "','" . $dz . "','" . date("Y-m-d H:i:s") . "','邮寄','".$gs."')";
        mysqli_query($con, $sql) or die("<script>alert('网络好像出了点问题');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>");
        mysqli_close($con);
        unset($_SESSION['cod']);
        echo <<<EOF
        <meta http-equiv="refresh" content="0.1;url=http://www.nbzxapi.com/zxkz/fin.html">
EOF;
		}else if($_GET['kzt']=="法人章"){
		$sf = $_GET['s_province'];
        $djs = $_GET['s_city'];
        $xjs = $_GET['s_county'];
        $dz = $_GET['address'];
		$fname = $_GET['fname'];
        $sql = "insert into skh(sjr,dh,kzt,cz,sf,djs,xjs,dz,sj,fs,fname) values('" . $sjr . "','" . $dh . "','" . $kzt . "','" . $cz . "','" . $sf . "','" . $djs . "','" . $xjs . "','" . $dz . "','" . date("Y-m-d H:i:s") . "','邮寄','".$fname."')";
        mysqli_query($con, $sql) or die("<script>alert('网络好像出了点问题');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>");
        mysqli_close($con);
        unset($_SESSION['cod']);
        echo <<<EOF
        <meta http-equiv="refresh" content="0.1;url=http://www.nbzxapi.com/zxkz/fin.html">
EOF;
		}else{
		$sf = $_GET['s_province'];
        $djs = $_GET['s_city'];
        $xjs = $_GET['s_county'];
        $dz = $_GET['address'];
        $sql = "insert into skh(sjr,dh,kzt,cz,sf,djs,xjs,dz,sj,fs) values('" . $sjr . "','" . $dh . "','" . $kzt . "','" . $cz . "','" . $sf . "','" . $djs . "','" . $xjs . "','" . $dz . "','" . date("Y-m-d H:i:s") . "','邮寄')";
        mysqli_query($con, $sql) or die("<script>alert('网络好像出了点问题');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>");
        mysqli_close($con);
        unset($_SESSION['cod']);
        echo <<<EOF
        <meta http-equiv="refresh" content="0.1;url=http://www.nbzxapi.com/zxkz/fin.html">
EOF;
		}
    } else
        echo "<script>alert('请先填表！');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>";
}else{
echo "<script>alert('请先填表！');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>";
}
?>