<?php
/**
* Author:sqlgun
* Email:sqlgun@qq.com
* Date: 2011-6-8
* http://hi.baidu.com/sqlgun
*/
define('GUY','true');
require 'common.inc.php';
global $_system;

if(isset($_POST['key'])){
	$_key = trim($_POST['key']);
}elseif(isset($_GET['key'])){
	$_key = trim($_GET['key']);
}else{
    echo '<script type="text/javascript">alert("非法访问!");location.href="index.php";</script>';
}

if(isset($_GET['page'])){
	$_page = $_GET['page'];
	if(empty($_page)|| !is_numeric($_page)||$_page<0|| ($_page>0 && $_page<1)){
		$_page=1;
	}else{
		$_page = intval($_page);
	}	
}else{
	$_page = 1;
}
$_pagenums = $_system['pagenums'];
$_pageopen = ($_page-1)*$_pagenums;
$_result = mysql_query("select id from " . table('news') ." where title like '%$_key%'");
$_nums = mysql_num_rows($_result);
$_pages = ceil($_nums/$_pagenums);
$_results = mysql_query("select id,title,date from " . table('news') ."  where title like '%$_key%' order by date DESC limit $_pageopen,$_pagenums");

$_result = mysql_query("select id,title from " . table('news') ." order by date DESC limit 0,{$_system['newsnums']}");

while(!!$_rows=mysql_fetch_array($_result, MYSQL_ASSOC)){
    $_rows['title'] = mb_substr($_rows['title'],0,17,'utf-8');
    $zuinews[] = $_rows;
}


$_result = mysql_query("select id,title from " . table('news') ." order by hits DESC limit 0,{$_system['hotnums']}");
while(!!$_rows = mysql_fetch_array($_result, MYSQL_ASSOC)){
    $_rows['title'] = mb_substr($_rows['title'],0,17,'utf-8');
    $renews[] = $_rows;
}

while(!!$_rows=mysql_fetch_array($_results, MYSQL_ASSOC)){
    $_rows['title'] = mb_substr($_rows['title'],0,17,'utf-8');
    $allnews[] = $_rows;
}

$smarty->assign('zuinews',$zuinews);
$smarty->assign('renews',$renews);
$smarty->assign('allnews',$allnews);
$smarty->assign('system',$_system['name']);
$smarty->assign('page',$_page);
$smarty->assign('num',$_nums);
$smarty->assign('pages',$_pages);
$smarty->assign('date',$_rows['date']);
$smarty->assign('searchkey',$_key);

$smarty->display('search');
?>