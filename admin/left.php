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
if($_GET['action']=='exit'){
	setcookie('username','',time()-1);
	echo'<script type="text/javascript">top.location.href="login.php"; </script>';	
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/left.css" />
<script type="text/javascript" src="js/js.js"></script>
<title>left</title>
</head>

<body>
<ul id="left">
	<li><a href="#"><div><img id="flag" src="images/Contraction.jpg"></img><span style="color:blue">新闻发布管理>></span></div></a>
		<ul>
			<li class="leftin"><a href="addnews.php" target="main">发布新闻</a></li>
			<li class="leftin"><a href="addlook.php" target="main">修改新闻</a></li>	
		</ul>
	</li>
	<li><a href="#"><div><img id="flag" src="images/Contraction.jpg"></img><span style="color:blue">新闻分类管理>></span></div></a>
		<ul>
		<li class="leftin"><a href="addclass.php" target="main">分类管理</a></li>
		<!-- 
			<li class="leftin"><a href="addclass.php" target="main">添加分类</a></li>
			<li class="leftin"><a href="addclass.php" target="main">修改分类</a></li>
			<li class="leftin"><a href="addclass.php" target="main">删除分类</a></li>
	     -->				
		</ul>	
	</li>	
	<li><a href="#" ><div><img id="flag" src="images/Contraction.jpg"></img><span style="color:blue">系统配置管理>></span></div></a>
		<ul>
			<li class="leftin"><a href="addsystem.php" target="main">系统参数</a></li>	
		</ul>	
	</li>
	<li><a href="#"><div><img id="flag" src="images/Contraction.jpg"></img><span style="color:blue">布局管理>></span></div></a>
		<ul>
			<li class="leftin"><a href="layoutindex.php" target="main">首页布局</a></li>
		</ul>
	</li>
	<li><a href="#"><div><img id="flag" src="images/Contraction.jpg"></img><span style="color:blue">友情链接>></span></div></a>
		<ul>
			<li class="leftin"><a href="friendlink.php?action=show" target="main">友链管理</a></li>
		</ul>
	</li>
	<li><a href="#"><div><img id="flag" src="images/Contraction.jpg"></img><span style="color:blue">领导班子设置>></span></div></a>
		<ul>
			<li class="leftin"><a href="leadermanage.php?action=show" target="main">管理领导</a></li>
			<li class="leftin"><a href="leadersettings.php?action=add" target="main">添加领导</a></li>
		</ul>
	</li>
	<li><a href="login.html"><div><img id="flag" src="images/Contraction.jpg"></img><span style="color:blue">用户权限管理>></span></div></a>
	
		<ul>
			<li class="leftin"><a href="addadmin.php" target="main">添加管理员</a></li>
			<li class="leftin"><a href="addadmin.php" target="main">修改/删除管理员</a></li>		
		</ul>		
	</li>	
	<li><a href="?action=exit"><div style="color:red"><退出></div></a></li>
</ul>
</body>
</html>
