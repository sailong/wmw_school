<?php 
if(!defined("IN_SCHOOL")) {
    exit("Have no access!");
}

//数据库相关配置
define('HOST' , '118.193.52.14');    //主机
define('USER' , 'zjwdb_118735');             //用户名
define('PASSWORD' , 'baixike521');   //密码
define('DB','zjwdb_118735');           //数据库名

define("IS_PIC_LIST" , 2);           //图文板块

//设置首页的固定显示的模块id
global $_G , $_S , $_Q , $_M , $_N , $_K, $_system;

//网站相关
$_G['settings']['host_config'] = array(
    //学校相关的域名标记，表名前缀和模板前缀
    'school' => array(
        'http_host' => 'http://ftp118735.host276.web522.com',
        'table_pre' => 'school_',
        'tpl_pre' => 'school',
        'global_var' => & $_S,            //必须是引用传值
    ),
    //大队相关的域名标记，表名前缀和模板前缀
    'queue' => array(
        'http_host' => '',
        'table_pre' => 'queue_',
        'tpl_pre' => 'queue',
        'global_var' => & $_Q,            //必须是引用传值
    ),
    //中学网站门户
    'middle_school' => array(
        'http_host' => '',
        'table_pre' => 'middle_',
        'tpl_pre' => 'middle_school',
        'global_var' => & $_M,            //必须是引用传值
    ),
    //学校相关的域名标记，表名前缀和模板前缀
    'nursery' => array(
        'http_host' => '',
        'table_pre' => 'school_',
        'tpl_pre' => 'nursery_school',
        'global_var' => & $_N,            //必须是引用传值
    ),
    //幼儿园
    'kindergarten' => array(
        'http_host' => '',
        'table_pre' => 'kindergarten_', //???
        'tpl_pre' => 'kindergarten',
        'global_var' => & $_K,         //???   //必须是引用传值
    ),
);

/**
 * school域名下对应的相关配置
 */
//导航栏的固定id
$_S['settings']['nav']['static_nav_ids'] = array();
//需要排除的id一级id
$_S['settings']['nav']['static_nav_noexists'] = array(1,7,8);

//系统固定模块
$_S['static_ids'] = array(
    'notice' => 1,                   //公告   
    'dy' => 2,                       //德育天地
	'dq' => 3,                       //党旗飘飘
	'educate' => 4,                  //教育科研
    'student' => 5,                  //学生天地
    'family' => 6,                   //家校联系
    'leader' => 7,  				 //领导班子
	'hdimg'=>8,                      //首页幻灯片
	'tskz'=>9,                      //特色科组
);
//需要检查
$_S['system']['checklist'] = array(
    'notice' => 0,                    //公告   
    'dy' => 0,                        //德育天地
	'dq' => 0,                       //党旗飘飘
	'educate' => 0,                  //教育科研
    'student' => 0,                  //学生天地
    'family' => 0,                   //家校联系
    'leader' => 1,                    //领导班子
	'hdimg'=>1, 					//首页幻灯片
	'tskz'=>0,                      //特色科组
);

//获取信息列表的相关参数设置
$_S['config']['static'] = array(
    'notice' => array(   //公告   
    	'fields' => 'id,title',
        'limit' => 10,
        'content_len' => 0,
        'title_len' => 20,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
    'dy' => array(          //德育天地
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ), 
	'dq' => array(                 //党旗飘飘
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
    'educate' => array(                  //教育科研
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ), 
	'student' =>array(            //学生天地
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
    'family' =>array(            //家校联系
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
    'leader' =>array(            //领导班子
    	'fields' => 'id,title,pictitle,content',
        'limit' => 3,
        'content_len' =>150,
        'title_len' => 20,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
    'hdimg' =>array(            //首页幻灯片
    	'fields' => 'id,pictitle',
        'limit' => 6,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ),
    'tekz' => array(                //默认配置
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ), 
    'default' => array(                //默认配置
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
);
//用户自定义板块的相关设置
$_S['config']['settings'] = array(
    'default' => array(
		'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
);
$_S['strlen']['zj_news_str_len'] = 24;         //校园新闻

//设置需要加载的设置项
$_S['load_settings']  = array(
    'friendlinks' ,             //友情链接
    'layoutorders',             //首页模块布局
);


/**
 * queue域名下的相关配置
 */
/**
 * school域名下对应的相关配置
 */
//导航栏的固定id
$_Q['settings']['nav']['static_nav_ids'] = array(11,4,5,6);
//需要排除的id一级id
$_Q['settings']['nav']['static_nav_noexists'] = array(2);

//系统固定模块
$_Q['static_ids'] = array(
    'young_pioneer' => 1,              //少先队员简介   
    'activity_show' => 2,              //活动播报
	'news' => 3,                     //文章列表
    'leader' => 4,                    //我们的校长
    'coach_members' => 5,             //我们的辅导员
);

//需要检查
$_Q['system']['checklist'] = array(
    'young_pioneer' => 1,              //少先队员简介   
    'activity_show' => 0,              //活动播报
	'news' => 0,                     //文章列表
    'leader' => 1,                    //我们的校长
    'coach_members' => 1,             //我们的辅导员
);

//获取信息列表的相关参数设置
$_Q['config']['static'] = array(
    'young_pioneer' => array(  //少先队员简介  
    	'fields' => "id,title,content,pictitle",
        'limit' => 1,
        'content_len' => 130,
        'title_len' => 24,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ),
    'activity_show' => array(             //活动播报
    	'fields' => 'id,title,date',
        'limit' => 5,
        'content_len' => 0,
        'title_len' => 24,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ), 
	'news' => array(                 //文章列表
    	'fields' => 'id,title,birth,date,hits',
        'limit' => 10,
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
    'leader' => array(                  //我们的校长
    	'fields' => '*',
        'limit' => 2,
        'content_len' => 200,
        'title_len' => 20,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
	'coach_members' =>array(            //我们的辅导员
    	'fields' => '*',
        'limit' => 2,
        'content_len' => 200,
        'title_len' => 20,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
    'default' => array(                //默认配置
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 200,
        'title_len' => 20,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ),
);

//用户自定义模块的相关设置
$_Q['config']['settings'] = array(
    'defalut' => array(
        'fields' => 'id,title,date',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 20,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
);

$_Q['strlen']['zj_news_str_len'] = 24;         //校园新闻

//设置需要加载的设置项
$_Q['load_settings']  = array(
    'friendlinks' ,             //友情链接
    'layoutorders',             //首页模块布局
);


/**
 * middle_school域名下对应的相关配置
 */
//导航栏的固定id
$_M['settings']['nav']['static_nav_ids'] = array();
//需要排除的id一级id
$_M['settings']['nav']['static_nav_noexists'] = array(1,7,8);

//系统固定模块
$_M['static_ids'] = array(
    'notice' => 1,                   //公告   
    'dy' => 2,                       //德育天地
	'dq' => 3,                       //党旗飘飘
	'educate' => 4,                  //教育科研
    'student' => 5,                  //学生天地
    'family' => 6,                   //家校联系
    'leader' => 7,  				 //领导班子
	'hdimg'=>8,                      //首页幻灯片
	'tskz'=>9,                      //特色科组
);
//需要检查
$_M['system']['checklist'] = array(
    'notice' => 0,                    //公告   
    'dy' => 0,                        //德育天地
	'dq' => 0,                       //党旗飘飘
	'educate' => 0,                  //教育科研
    'student' => 0,                  //学生天地
    'family' => 0,                   //家校联系
    'leader' => 1,                    //领导班子
	'hdimg'=>1, 					//首页幻灯片
	'tskz'=>0,                      //特色科组
);

//获取信息列表的相关参数设置
$_M['config']['static'] = array(
    'notice' => array(   //公告   
    	'fields' => 'id,title',
        'limit' => 10,
        'content_len' => 0,
        'title_len' => 20,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
    'dy' => array(          //德育天地
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ), 
	'dq' => array(                 //党旗飘飘
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
    'educate' => array(                  //教育科研
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ), 
	'student' =>array(            //学生天地
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
    'family' =>array(            //家校联系
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
    'leader' =>array(            //领导班子
    	'fields' => 'id,title,pictitle,content',
        'limit' => 3,
        'content_len' =>150,
        'title_len' => 20,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
    'hdimg' =>array(            //首页幻灯片
    	'fields' => 'id,pictitle',
        'limit' => 6,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ),
    'tekz' => array(                //默认配置
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ), 
    'default' => array(                //默认配置
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
);
//用户自定义板块的相关设置
$_M['config']['settings'] = array(
    'default' => array(
		'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
);
$_M['strlen']['zj_news_str_len'] = 24;         //校园新闻

//设置需要加载的设置项
$_M['load_settings']  = array(
    'friendlinks' ,             //友情链接
    'layoutorders',             //首页模块布局
);


/**
 * nursery_school域名下对应的相关配置
 */
//导航栏的固定id
$_N['settings']['nav']['static_nav_ids'] = array();
//需要排除的id一级id
$_N['settings']['nav']['static_nav_noexists'] = array(1,7,8);

//系统固定模块
$_N['static_ids'] = array(
    'notice' => 1,                   //公告   
    'dy' => 2,                       //德育天地
	'dq' => 3,                       //党旗飘飘
	'educate' => 4,                  //教育科研
    'student' => 5,                  //学生天地
    'family' => 6,                   //家校联系
    'leader' => 7,  				 //领导班子
	'hdimg'=>8,                      //首页幻灯片
	'tskz'=>9,                      //特色科组
);
//需要检查
$_N['system']['checklist'] = array(
    'notice' => 0,                    //公告   
    'dy' => 0,                        //德育天地
	'dq' => 0,                       //党旗飘飘
	'educate' => 0,                  //教育科研
    'student' => 0,                  //学生天地
    'family' => 0,                   //家校联系
    'leader' => 1,                    //领导班子
	'hdimg'=>1, 					//首页幻灯片
	'tskz'=>0,                      //特色科组
);

//获取信息列表的相关参数设置
$_N['config']['static'] = array(
    'notice' => array(   //公告   
    	'fields' => 'id,title',
        'limit' => 10,
        'content_len' => 0,
        'title_len' => 20,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
    'dy' => array(          //德育天地
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 34,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ), 
	'dq' => array(                 //党旗飘飘
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
    'educate' => array(                  //教育科研
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ), 
	'student' =>array(            //学生天地
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
    'family' =>array(            //家校联系
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
    'leader' =>array(            //领导班子
    	'fields' => 'id,title,pictitle,content',
        'limit' => 3,
        'content_len' =>150,
        'title_len' => 20,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
    'hdimg' =>array(            //首页幻灯片
    	'fields' => 'id,pictitle',
        'limit' => 6,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ),
    'tekz' => array(                //默认配置
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ), 
    'default' => array(                //默认配置
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
);
//用户自定义板块的相关设置
$_N['config']['settings'] = array(
    'default' => array(
		'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
);
$_N['strlen']['zj_news_str_len'] = 24;         //校园新闻

//设置需要加载的设置项
$_N['load_settings']  = array(
    'friendlinks' ,             //友情链接
    'layoutorders',             //首页模块布局
);




/**
 * kindergarten域名下对应的相关配置
 */
//导航栏的固定id
$_K['settings']['nav']['static_nav_ids'] = array();
//需要排除的id一级id
$_K['settings']['nav']['static_nav_noexists'] = array(1,7,8);

//系统固定模块
$_K['static_ids'] = array(
    'notice' => 1,                   //-----公告  ----- 
    'dy' => 2,                       //德育天地
	'dq' => 3,                       //党旗飘飘
	'educate' => 4,                  //教育科研
    'student' => 5,                  //学生天地
    'family' => 6,                   //家校联系
    'leader' => 7,  				 //----领导班子----
	'hdimg'=>8,                      //---首页幻灯片---
	'tskz'=>9,                      //特色科组
);
//需要检查
$_K['system']['checklist'] = array(
    'notice' => 0,                    //公告   
    'dy' => 0,                        //德育天地
	'dq' => 0,                       //党旗飘飘
	'educate' => 0,                  //教育科研
    'student' => 0,                  //学生天地
    'family' => 0,                   //家校联系
    'leader' => 1,                    //领导班子
	'hdimg'=>1, 					//首页幻灯片
	'tskz'=>0,                      //特色科组
);

//获取信息列表的相关参数设置
$_K['config']['static'] = array(
    'notice' => array(   //公告   
    	'fields' => 'id,title',
        'limit' => 10,
        'content_len' => 0,
        'title_len' => 20,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
    'dy' => array(          //德育天地
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 34,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ), 
	'dq' => array(                 //学校简介
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
    'educate' => array(                  //教育科研
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ), 
	'student' =>array(            //学生天地
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
    'family' =>array(            //家校联系
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
    'leader' =>array(            //领导班子
    	'fields' => 'id,title,pictitle,content',
        'limit' => 3,
        'content_len' =>150,
        'title_len' => 20,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ), 
    'hdimg' =>array(            //首页幻灯片
    	'fields' => 'id,pictitle',
        'limit' => 6,
        'strip_tag' => true,
        'date_format' => 'Y-m-d',
    ),
    'tekz' => array(                //默认配置
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ), 
    'default' => array(                //默认配置
    	'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
);
//用户自定义板块的相关设置
$_K['config']['settings'] = array(
    'default' => array(
		'fields' => 'id,title',
        'limit' => & $_system['newsnums'],
        'content_len' => 0,
        'title_len' => 40,
        'strip_tag' => false,
        'date_format' => 'Y-m-d',
    ),
);
$_K['strlen']['zj_news_str_len'] = 24;         //校园新闻

//设置需要加载的设置项
$_K['load_settings']  = array(
    'friendlinks' ,             //友情链接
    'layoutorders',             //首页模块布局
);


?>
