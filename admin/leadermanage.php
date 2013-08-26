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

//领导班子默认设置
$uid = intval($_G['static_ids']['leader']);
if(empty($uid)) {
    echo '<script type="text/javascript"> alert("该功能暂时未开通!，请修改配置文件:common.inc.php中的默认设置!");location.href="index.php"; </script>';	
	exit;
}

if($_GET['del']=='del'){
	$flag = mysql_query("delete from " . table('news') . " where id='{$_GET['id']}'");
	if($flag){
	    echo'<script type="text/javascript"> alert("删除记录成功!");location.href="leadermanage.php"; </script>';
	}else{
	    echo'<script type="text/javascript"> alert("删除记录失败!!");window.history.go(-1); </script>';
	    exit;
	}
		
}

//附加的查询条件
$wheresql = $uid ? " where uid='$uid'" : "";

$_page = max(intval($_GET['page']) , 1);
$_pagenum = 15;
$_results = mysql_query("select id from " . table('news') ." $wheresql");
$_num = mysql_num_rows($_results);
$_pages = ceil($_num/$_pagenum);
$_pageopen = ($_page-1) * $_pagenum;

//追加新闻的分类信息
$query_class = mysql_query("select * from " . table('class') ." where id='$uid'");
$classinfo = mysql_fetch_array($query_class , MYSQL_ASSOC);

$query_news = mysql_query("select * from " . table('news') ." $wheresql order by date DESC limit $_pageopen,$_pagenum");
$news_list = array();
while($news = mysql_fetch_array($query_news)) {
    $news['class'] = $classinfo['class'];
    $news_list[] = $news;
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
    	<th>新闻分类</th>
    	<th>新闻标题</th>
    	<th>编辑</th>
    	<th>发布时间</th>
    	<th>操作</th>
    </tr>
    <?php  foreach($news_list as $news){?>
    <tr>
        <td><?php echo $news['id'];?></td>
        <td>
            <?php echo $news['class'];?>
        </td>
        <td>
            <a href="../news.php?id=<?php echo $news['id']?>" target="_blank"><?php echo $news['title'];?></a>
        </td>
        <td>
            <?php echo $news['birth'];?>
        </td>
        <td>
            <?php echo $news['date'];?>
        </td>
        <td>
        	<a href="leadersettings.php?id=<?php echo $news['id']?>&action=update">修改</a>　
        	<a href="?id=<?php echo $news['id']?>&del=del">删除</a>
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
    echo'<li><a href="?page='.($_page-1).'">上一页</a></li>';
    }
    ?>
    <?php if($_page==$_pages){
    echo'<li>下一页</li>';	
    echo'<li>尾页</li>';
    }else{
    echo'<li><a href="?page='.($_page+1).'">下一页</a></li>';	
    echo'<li><a href="?page='.$_pages.'">尾页</a></li>';	
    }
     ?>
</ul>
</div>
</body>
</html>
