<?php
/**
* Author:changsailong
*/
define('GUY','true');
require 'common.inc.php';
global $_system;
global $_G;

$_result = mysql_query("select uid,id,title from " . table('news') ." order by date DESC limit 0,10");
//最新新闻
$zj_news = array();
$str_len = $_G['strlen']['zj_news_str_len'];
$str_len = $str_len > 0 ? $str_len : 15;

while($news = mysql_fetch_array($_result, MYSQL_ASSOC)){
    if(empty($news['title'])) {
        continue;
    }
    $news['title_all'] = $news['title'];
	$news['title'] = cutstr($news['title'], $str_len);
	$zj_news[] = $news;
}

//图片新闻标题图片轮换展示
$switch_name = $_G['settings']['host_config']['current']['switch_name'];
if($switch_name == 'school') {
    $pic_nums = 7;
} elseif($switch_name == 'queue') {
    $pic_nums = 14;
    $title_len = 5;
} else {
    $pic_nums = 7;
}

$pic_news_content = getPiclist($pic_nums , $title_len);
//其他固定模块
$static_list = getClassNewsList('static');

//用户设置模块
$settings_list = getClassNewsList('settings');
$smarty->assign("pic_news_content", $pic_news_content);
$smarty->assign('system' , $_system);
$smarty->assign('zj_news',$zj_news);

$smarty->assign('settings_list' , $settings_list);
$smarty->assign('static_list' , $static_list);

//初始化友情链接
$smarty->assign('friendlinks' , $_G['settings']['friendlinks']);

$smarty->assign('footlist' , getSysConf()); //版权
$smarty->display('index');

