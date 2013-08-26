<?php
/**
* Author:
* Email:@qq.com
* Date: 2011-5-26
* http://hi.baidu.com/
*/
header('content-type:text/html;charset=utf-8');
if(!isset($_COOKIE['username'])){
	echo'<script type="text/javascript"> alert("还没有登录？请先登录！");top.location.href="login.php"; </script>';	
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/top.css" />
<title>top</title>
<script language="javascript">
function quit(){
	top.location.href="left.php?action=exit";	
}

function users () {
	location.href="login.php";	
}
	
	
</script>
</head>

<body>
<div id="top">
<p></p>
	<div id="topright">
		<p class="p1"></p>
			 <ul>
			 	<li ><a href="addadmin.php" target="main"><img id="imgli1" src="images/pass.gif" style="border:0px;"/></a></li>
			 	<li><a href="#" ><img onclick="quit();" src="images/quit.gif" class="li3" style="border:0px;"/></a></li>
			 </ul>
	</div>
</div>
<div id="end">
	
	<p class="p2">
		<script type="text/javascript">
		var day="";
		var month="";
		var ampm="";
		var ampmhour="";
		var myweekday="";
		var year="";
		mydate=new Date();
		myweekday=mydate.getDay();
		mymonth=mydate.getMonth()+1;
		myday= mydate.getDate();
		myyear= mydate.getYear();
		year=(myyear > 200) ? myyear : 1900 + myyear;
		if(myweekday == 0)
		weekday=" 星期日 ";
		else if(myweekday == 1)
		weekday=" 星期一 ";
		else if(myweekday == 2)
		weekday=" 星期二 ";
		else if(myweekday == 3)
		weekday=" 星期三 ";
		else if(myweekday == 4)
		weekday=" 星期四 ";
		else if(myweekday == 5)
		weekday=" 星期五 ";
		else if(myweekday == 6)
		weekday=" 星期六 ";
		document.write("<font color=white>北京时间 "+year+"年"+mymonth+"月"+myday+"日 "+weekday+"</font>");
		</script>
	</p>
</div>
<div id="down">
<p class="p0"></p>
<p class="p1"></p>

<p class="p2"> 当前登录用户：<?php echo $_COOKIE['username'];?> &nbsp;&nbsp;用户角色：管理员</p>

 <!-- 
<ul>

<li>IP：192.168.0.1</li>
<li>开发者：</li>
<li class="email">Email：@qq.com</li>
<li class="li1"></li>
</ul>
-->
</div>
</body>
</html>
