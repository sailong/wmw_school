<?php
/**
* Author:
* Email:@qq.com
* Date: 2011-6-2
* http://hi.baidu.com/
*/
define('GUY','true');
require '../common.inc.php';
if(!isset($_COOKIE['username'])){
	echo'<script type="text/javascript"> alert("非法登录!");top.location.href="login.php"; </script>';	
	exit;
}

if(!$_GET['uid']){
	$_GET['uid']=1;
}
if($_GET['del']=='del'){
	mysql_query("delete from " . table('news') . " where id='{$_GET['id']}'");
	echo"<script type='text/javascript'> alert('删除记录成功!');location.href='addlook.php?uid=".$_GET['uid']."'; </script>";	
}

if(isset($_GET['page'])){
	$_page=$_GET['page'];
	if(empty($_page)|| !is_numeric($_page)||$_page<0|| ($_page>0 && $_page<1)){
		$_page=1;
	}else{
		$_page=intval($_page);
	}	
}else{
	$_page=1;
}
$_pagenum=15;
if($_GET['uid']){
	$_results=mysql_query("select id from " . table('news') ."where uid='".$_GET['uid']."'");
}else{
	$_results=mysql_query("select id from " . table('news') ."");
}

$_num=mysql_num_rows($_results);
$_pages=ceil($_num/$_pagenum);
$_pageopen=($_page-1)*$_pagenum;
if($_GET['uid']){
  $_result=mysql_query("select * from " . table('news') ." where uid='".$_GET['uid']."' order by date DESC limit $_pageopen,$_pagenum");
}else{
  $_result=mysql_query("select * from " . table('news') ." order by date DESC limit $_pageopen,$_pagenum");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/addlook.css" />
<title>新闻列表</title>
</head>

<body>
<div id="addlook">
<p>新闻列表</p>
<table>
<tr>
<th>编号</th>
<th>
  <select id="uid" name="uid" onchange="selectopt()">
<?php 
     $_result1=mysql_query("select id,class,is_pic_list from " . table('class') ."where typeid=1");
     while(!!$_rows1=mysql_fetch_array($_result1,MYSQL_ASSOC)){
     	if($_GET['uid']==$_rows1['id']){
?>
		  <option value="<?php echo $_rows1['id']?>" selected><?php echo $_rows1['class']; ?></option>
<?php    		
     	}else{
?>
		  <option value="<?php echo $_rows1['id']?>"><?php echo $_rows1['class']; ?></option>
<?php    		
     	}
?>
<?php
     trees($_rows1['id'],1,$_GET['uid']);
     }
?>
  </select>
</th>
<th>新闻标题</th>
<th>编辑</th>
<th>发布时间</th>
<th>操作</th>
</tr>
<?php 

while(!!$_rows=mysql_fetch_array($_result,MYSQL_ASSOC)){
?>
<tr>
    <td><?php echo $_rows['id'] ?></td>
    <td>
        <?php 
        $_results = mysql_query("select class,is_pic_list from " . table('class') ." where id='{$_GET['uid'] }'");
        $_row = mysql_fetch_array($_results , MYSQL_ASSOC);
        echo $_row['class'];
        ?>
    </td>
    <td><a href="../news.php?id=<?php echo $_rows['id'];?>" target="_blank"><?php echo cutstr($_rows['title'] , 70);?></a></td>
    <td><?php echo cutstr($_rows['birth'] , 30);?></td>
    <td><?php echo $_rows['date'] ?></td>
    <td>
        <a href="modifynews.php?ispic=<?php echo $_row['is_pic_list']?>&&id=<?php echo $_rows['id']?>">修改</a>　
        <a href="?id=<?php echo $_rows['id']?>&del=del&uid=<?php echo $_GET['uid']?>">删除</a>
    </td>
</tr>
<?php 	
}?>
</table>
<ul>
<li><?php echo $_page?>/<?php echo $_pages?>页</li>
<li>共有<?php echo $_num?>条新闻</li>
<?php if($_page==1){
echo'<li>首页</li>';
echo'<li>上一页</li>';	
}else{
echo'<li><a href="?">首页</a></li>';
echo'<li><a href="?page='.($_page-1).'&uid='.$_GET['uid'].'">上一页</a></li>';
}
?>
<?php if($_page==$_pages){
echo'<li>下一页</li>';	
echo'<li>尾页</li>';
}else{
echo'<li><a href="?page='.($_page+1).'&uid='.$_GET['uid'].'">下一页</a></li>';	
echo'<li><a href="?page='.$_pages.'&uid='.$_GET['uid'].'">尾页</a></li>';	
}
 ?>
</ul>
</div>
</body>
<script language="javascript">
function selectopt(){
	var uid = window.document.getElementById("uid").value;
	window.location.href="addlook.php?uid="+uid;
}
</script>
</html>