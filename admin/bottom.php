<?php
/**
* Author:
* Email:@qq.com
* Date: 2011-5-26
* http://hi.baidu.com/
*/
header('content-type:text/html;charset=utf-8');
if(!isset($_COOKIE['username'])){
	echo'<script type="text/javascript"> alert("非法登录!");top.location.href="login.php"; </script>';	
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/bottom.css" />
<title>bottom</title>
</head>

<body>
	<div id="bottom">
		<p class="p1"></p><p class="p2"></p>
	</div>
</body>
</html>
