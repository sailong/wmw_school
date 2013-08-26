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

$page = max(intval($_GET['page']) , 1);
$page_id = trim($_GET['id']);

//检验页面参数
$id_arr = parse_classid($page_id);
//保证分页id的正确性
$page_id = implode("_" , $id_arr);
//初始化左侧菜单
$first_id = $id_arr[0];
$class_treelist = getSublistById($first_id);
//获取页面id和查询条件
$last_id = array_pop($id_arr);

list($id , $query_ids) = getQueryId($last_id);

if(!empty($id)) {
    //获取当前分类信息
    $menulist = showmenu($id);
}
//如果对应的分类信息不存在
if(empty($id) || empty($menulist)) {
    header("Location: index.php");
}

//查询当前分类id下的相关信息
$query_current = mysql_query("select * from " . table('class') . " where id='$id' limit 1");
$current_class = mysql_fetch_array($query_current , MYSQL_ASSOC);

$perpage = $_system['pagenums'];
$start = ($page - 1) * $perpage;

$wheresql = "where uid in(" . implode(',' , $query_ids) . ")";
//新闻列表信息
$news_query = mysql_query("select id,title,date,hits,pictitle,uid from " . table('news') ." $wheresql order by date desc limit $start , $perpage");
dump("select id,title,date,hits,pictitle,uid from " . table('news') ." $wheresql order by date desc limit $start , $perpage");

$news_arr = array(); 
while($news = mysql_fetch_array($news_query , MYSQL_ASSOC) ) {
    if(empty($news['title'])) {
        continue;
    }
    if(strlen($news['title'])>60){
        $news['title'] = mb_substr($news['title'],0,30).".....";
    }
    
    $news_arr[] = $news;
}
//分页
$count_query = mysql_query("select count(id) as totalnums from " . table('news') ." $wheresql");
$count_result = mysql_fetch_array($count_query , MYSQL_ASSOC);
$totalnums = $count_result['totalnums'];
$pages = ceil($totalnums/$perpage);

$smarty->assign('page',$page);
$smarty->assign('nums',$totalnums);
$smarty->assign('pages',$pages);
$smarty->assign('id' , $page_id);
$smarty->assign('menulist', $menulist);
$smarty->assign('system' , $_system);
$smarty->assign('class_treelist' , $class_treelist);
$smarty->assign('news_arr' , $news_arr);


//倒序
$reverarr = array_reverse($menulist);
$smarty->assign('current_class' , $reverarr[0]);
if($reverarr[0]['is_pic_list'] == 2){
	$smarty->display('listpageimg');
}else{
	$smarty->display('class');
}

?>