<?php
session_start();
date_default_timezone_set('PRC');
if ($_SESSION['cod']==504) {
    $con = mysqli_connect("127.0.0.1", "root", "nbzx0769", "zxkz", "3306") or die("<script>alert('网络好像出了点问题');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>");
    mysqli_query($con, 'set names utf8') or die("<script>alert('网络好像出了点问题');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>");
    $sjr = $_POST['sjr'];
    $dh = $_POST['mobile'];
    $kzt = $_POST['kzt'];
    $cz = $_POST['cz'];
    $sj = date("Y-m-d H:i:s");
    if ($_POST['fs'] == "自取") {
		if($_POST['kzt']=="公章"){
		$gs = $_POST['gs'];
        $sql = "insert into skh(sjr,dh,kzt,cz,sj,fs,gs) values('" . $sjr . "','" . $dh . "','" . $kzt . "','" . $cz . "','" . date("Y-m-d H:i:s") . "','自取','".$gs."')";
        mysqli_query($con, $sql) or die("<script>alert('网络好像出了点问题');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>");
        mysqli_close($con);
        unset($_SESSION['cod']);
        echo <<<EOF
        <meta http-equiv="refresh" content="0.1;url=http://www.nbzxapi.com/zxkz/fin.html">
EOF;
		}else if($_POST['kzt']=="法人章"){
		$fname = $_POST['fname'];
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
    } else if ($_POST['fs'] == "邮寄") {
		if($_POST['kzt']=="公章"){
        $sf = $_POST['s_province'];
        $djs = $_POST['s_city'];
        $xjs = $_POST['s_county'];
        $dz = $_POST['address'];
		$gs = $_POST['gs'];
        $sql = "insert into skh(sjr,dh,kzt,cz,sf,djs,xjs,dz,sj,fs,gs) values('" . $sjr . "','" . $dh . "','" . $kzt . "','" . $cz . "','" . $sf . "','" . $djs . "','" . $xjs . "','" . $dz . "','" . date("Y-m-d H:i:s") . "','邮寄','".$gs."')";
        mysqli_query($con, $sql) or die("<script>alert('网络好像出了点问题');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>");
        mysqli_close($con);
        unset($_SESSION['cod']);
        echo <<<EOF
        <meta http-equiv="refresh" content="0.1;url=http://www.nbzxapi.com/zxkz/fin.html">
EOF;
		}else if($_POST['kzt']=="法人章"){
		$sf = $_POST['s_province'];
        $djs = $_POST['s_city'];
        $xjs = $_POST['s_county'];
        $dz = $_POST['address'];
		$fname = $_POST['fname'];
        $sql = "insert into skh(sjr,dh,kzt,cz,sf,djs,xjs,dz,sj,fs,fname) values('" . $sjr . "','" . $dh . "','" . $kzt . "','" . $cz . "','" . $sf . "','" . $djs . "','" . $xjs . "','" . $dz . "','" . date("Y-m-d H:i:s") . "','邮寄','".$fname."')";
        mysqli_query($con, $sql) or die("<script>alert('网络好像出了点问题');window.location.href='http://www.nbzxapi.com/zxkz/index.php';</script>");
        mysqli_close($con);
        unset($_SESSION['cod']);
        echo <<<EOF
        <meta http-equiv="refresh" content="0.1;url=http://www.nbzxapi.com/zxkz/fin.html">
EOF;
		}else{
		$sf = $_POST['s_province'];
        $djs = $_POST['s_city'];
        $xjs = $_POST['s_county'];
        $dz = $_POST['address'];
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