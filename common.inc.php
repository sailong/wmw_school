<?php
/**
* Author:
* Email:@qq.com
* Date: 2011-5-28
* http://hi.baidu.com/
*/
if(!defined('GUY')){
	exit('getout!');
}
header('content-type:text/html;charset=utf-8');
//防止config.php文件被直接访问
define("IN_SCHOOL" , true);

require 'config.php';
require 'global.func.php';

global $_G;

//加载并初始化域名的相关配置
$init_flag = initHost();
if(!$init_flag) {
    echo "请先正确配置网站的域名数据!";
    exit;
}

//网站目录
define("WEB_ROOT" , realpath(dirname(__FILE__)));
//网站URL地址
define("HTTP_URL" , getHttpUrl());
//数据库链接
//echo HOST . USER . PASSWORD;
$_conn = @mysql_connect(HOST,USER,PASSWORD) or die('MYSQL连接错误');
@mysql_select_db(DB) or die('数据库连接错误');
@mysql_query('set names utf8') or die('query字符集错误');
//检查数据库的相关设置
sys_check();

//加载系统的设置数据
$settings_sql = "select * from " . table('settings') ."" . ((!empty($_G['load_settings'])) ? " where skey in('" . implode("','" , $_G['load_settings']) . "')" : "");
$query = mysql_query($settings_sql);
while($setting = mysql_fetch_array($query , MYSQL_ASSOC)) {
    $_G['setttings'][$setting['skey']] = $setting['svalue'];
}

//初始化友情链接
initFriendLink();

global $_system;
$_system = getSysConf();

//smarty
require(WEB_ROOT.'/smarty/Smarty.class.php');
class MySmarty extends Smarty {
    function display($template = null, $cache_id = null, $compile_id = null, $parent = null) {
        //获取模板要显示的前缀
        $pre = getTplPre();
        $template = $pre . "/" . $template . ".html";
        parent::display($template , $cache_id , $compile_id , $parent);
    }
}

$smarty = new MySmarty;
$smarty->compile_dir = WEB_ROOT . '/template_c';
$smarty->template_dir = WEB_ROOT . '/template';

//初始化的相关赋值
$smarty->assign('system' , getSysConf());
