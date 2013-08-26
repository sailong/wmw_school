<?php

define('GUY','true'); //???

//配置信息，smarty，连接数据库，head,foot封装等操作
require 'common.inc.php'; 
//被点击的文章的id的合法性
$id = max(intval($_GET['id']) , 0);

//文章详细内容
if(!empty($id)) {
    $detail_query = mysql_query("select * from " . table('news') ." where id='$id' limit 1");
    $detail_rows = mysql_fetch_array($detail_query, MYSQL_ASSOC);
    $detail_rows['title'] = $detail_rows['short_title'] = cutstr($detail_rows['title'] , 70);
}

$menuid = max(intval($detail_rows['uid']),0);
if(!empty($menuid)) {
    //获取当前分类信息
    $menulist = showmenu($menuid);
}

if(empty($id) || empty($detail_rows)) {
    echo '<script type="text/javascript">alert("文章不存在或者已删除!");window.location.href="index.php";</script>';
	exit;
}

//获取右侧导航信息
list($upartmenuinfoes , $submenuinfo) = getSameLevelClass($detail_rows["uid"]);

//点击量增加1
mysql_query("update " . table('news') . " set hits=hits+1 where id='$id' limit 1"); //点击量加一

$smarty->assign("upartmenuinfoes" , $upartmenuinfoes);
$smarty->assign("submenuinfo" , $submenuinfo);
$smarty->assign("menulist",$menulist);
$smarty->assign("detail_rows" , $detail_rows);

$smarty->display("news");
?>
