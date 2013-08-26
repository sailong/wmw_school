<?php
/**
* Author:
* Email:@qq.com
* Date: 2011-5-25
* http://hi.baidu.com/
*/
header('content-type:text/html;charset=utf-8');
if(!isset($_COOKIE['username'])){
	echo'<script type="text/javascript"> alert("非法登录!");top.location.href="login.php"; </script>';	
	exit;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/center.css" />
<title>center</title>
</head>
<body>
<table>
	<tr>
    <td width="8" bgcolor="#353c44"></td>	
	<td width="147"><iframe width="100%" height="100%"  frameborder="0" src="left.php"></iframe></td>
	<td width="10" bgcolor="#add2da"></td>
	<td >
	<iframe name="main" width="100%" height="100%" scrolling="Yes" frameborder="0" src="right.php"></iframe>
	</td>
    <td width="8" bgcolor="#353c44"></td>	
	</tr>
</table>
</body>
</html>
