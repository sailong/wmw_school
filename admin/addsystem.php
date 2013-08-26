<?php
/**
* Author:
* Email:@qq.com
* Date: 2011-5-30
* http://hi.baidu.com/
*/
define('GUY','true');
require '../common.inc.php';
if(!isset($_COOKIE['username'])){
	echo'<script type="text/javascript"> alert("非法登录!");top.location.href="login.php"; </script>';	
	exit;
}
if($_GET['action']=='add'){
	$_html=array();
	$_html['name']=trim($_POST['name']);
	$_html['pagenums']=$_POST['pagenums'];	
	$_html['newsnums']=$_POST['newsnums'];	
	$_html['hotnums']=$_POST['hotnums'];			
	$_html['copy']=trim($_POST['copy']);
	
	mysql_query("update " . table('system') . " set name='{$_html['name']}',
	pagenums='{$_html['pagenums']}',
	newsnums='{$_html['newsnums']}',
	hotnums='{$_html['hotnums']}',
	copy='{$_html['copy']}'");	
	echoalert('添加修改成功!');
}

$_result=mysql_query("select * from " . table('system'));
$_rows=mysql_fetch_array($_result , MYSQL_ASSOC);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/addsystem.css" />
<title>添加系统参数</title>
</head>
<body>
<div id="addnews">
<p>添加系统参数</p>
<form method="post" action="?action=add">
<ul id="sysset">
<li style="align-bottom:60;">网站名称：&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="name" value="<?php echo $_rows['name']?>" /></li>
<li >底部版权：&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="copy" value="<?php echo $_rows['copy']?>" /></li> 
<li class="li1">新闻列表页每页显示条数 ：  	&nbsp;<input type="text" name="pagenums" value="<?php echo $_rows['pagenums']?>" /></li>
<li class="li1"  >首页“最新新闻”显示条数：&nbsp;<input type="text" name="newsnums" value="<?php echo $_rows['newsnums']?>" /></li>
<!-- <li class="li1">热门新闻数？？？：<input type="text" name="hotnums" value="<?php echo $_rows['newsnums']?>" /></li> -->

<li class="li3"><input type="submit" value="提交" /></li>
</ul>
</form>
</div>
<?php 
mysql_close();
?>
</body>
</html>
