<?php
/**
* Author:zuchaoyang
* Email:zuchaoyang123@163.com
* Date: 2011-10-24
* http://www.wmw.cn
*/
define('GUY','true');
require 'common.inc.php';
global $_system;
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
$_pagenums=$_system['pagenums'];
$_pageopen=($_page-1)*$_pagenums;
$_result=mysql_query("select id from " . table('news'));
$_nums=mysql_num_rows($_result);
$_pages=ceil($_nums/$_pagenums);

//全部新闻
$_results=mysql_query("select id,title,date from " . table('news') ." order by date DESC limit $_pageopen,$_pagenums");
$allnews = array();
while($_rows = mysql_fetch_array($_results , MYSQL_ASSOC)){
    $allnews[] = $_rows;
}


//最新新闻 
$_result=mysql_query("select id,title from " . table('news') ." order by date DESC limit 0,{$_system['newsnums']}");
$zuinews = array();
while($_rows=mysql_fetch_array($_result , MYSQL_ASSOC)){
    $_rows['title']=mb_substr($_rows['title'],0,17,'utf-8');
    $zuinews[]=$_rows;	
}


//热门新闻
$_result=mysql_query("select id,title from " . table('news') ." order by hits DESC limit 0,{$_system['hotnums']}");
$hotnews = array();
while($_rows=mysql_fetch_array($_result , MYSQL_ASSOC)){
$_rows['title'] = mb_substr($_rows['title'],0,17,'utf-8');
$hotnews[] = $_rows;
}

$smarty->assign('system',$_system['name']);
$smarty->assign('allnews',$allnews);
$smarty->assign('zuinews',$zuinews);
$smarty->assign('page',$_page);
$smarty->assign('nums',$_nums);
$smarty->assign('pages',$_pages);
$smarty->assign('hotnews',$hotnews);
$smarty->display('schoolallnews'); 
?>