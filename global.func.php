<?php
/**
* Author:
* Email:@qq.com
* Date: 2011-5-28
* http://hi.baidu.com/
*/
if(!defined('GUY') || !defined('IN_SCHOOL')){
	exit('getout!');
}

function echoalert($_info) {
	echo "<script type='text/javascript'>alert('$_info');</script>";
}

function echoalerthistory($_info){
	echo "<script type='text/javascript'>alert('$_info');history.back();</script>;";
}

function tree($_id,$_num){
    $_results=mysql_query("select id,class from " . table('class') ." where uptypeid='{$_id}'");
    while(!!$_row=mysql_fetch_array($_results,MYSQL_ASSOC)){
        echo "<option value='".$_row['id']."'>".str_repeat('　',$_num)."|-{$_row['class']}</option>";
        tree($_row['id'],$_num+1);
    }
}

function trees($_id,$_num,$id){
    $_results=mysql_query("select id,class,is_pic_list from " . table('class') ." where uptypeid='{$_id}'");
    while(!!$_row=mysql_fetch_array($_results,MYSQL_ASSOC)){
    	if($id!=''){
	    	if($_row['id']==$id){
	    		echo "<option value='".$_row['id']."' ispic='".$_row['is_pic_list']."' selected>".str_repeat('　',$_num)."|-{$_row['class']}</option>";
	    	}else{
	    		echo "<option value='".$_row['id']."' ispic='".$_row['is_pic_list']."'>".str_repeat('　',$_num)."|-{$_row['class']}</option>";
	    	}
    	}else{
    		echo "<option value='".$_row['id']."' ispic='".$_row['is_pic_list']."'>".str_repeat('　',$_num)."|-{$_row['class']}</option>";
    	}
        trees($_row['id'],$_num+1,$id);
    }
}


/**
 * 获取系统的相关配置
 */
function getSysConf() {
    static $_system = array();
    
    if(empty($_system)) {
        $query = mysql_query("select * from " . table('system') ." limit 1");  
        $_rows = mysql_fetch_array($query , MYSQL_ASSOC); 
        if(!empty($_rows)) {
        	$_system = array();
        	$_system['name']=$_rows['name'];
        	$_system['pagenums']=$_rows['pagenums'];
        	$_system['newsnums']=$_rows['newsnums'];
        	$_system['hotnums']=$_rows['hotnums'];
        	$_system['copy']=$_rows['copy'];
        }
    }
    
    return !empty($_system) ? $_system : false;
}

/**
 * 获取头部的信息
 * @param $params
 * @param $smarty
 */
function insert_getHead($params = array() , &$smarty) {
    global $_G;
    
    $static_nav_ids = $_G['settings']['nav']['static_nav_ids'];
    $static_nav_noexists = $_G['settings']['nav']['static_nav_noexists'];
    
    $static_nav_ids = is_array($static_nav_ids) ? $static_nav_ids : array($static_nav_ids);
    $static_nav_noexists = is_array($static_nav_noexists) ? $static_nav_noexists : array($static_nav_noexists);
    
    $query = mysql_query("select * from " . table('class') ." where typeid='1'");
    $navlist = $sub_navlist = array();
    while($nav = mysql_fetch_array($query , MYSQL_ASSOC)) {
        $navlist[$nav['id']] = $nav;
    }
    $new_navlist = array();
    //排序
    if(!empty($navlist)) {
        if(!empty($static_nav_ids)) {
            foreach($static_nav_ids as $id) {
                if(isset($navlist[$id])) {
                    $new_navlist[$id] = $navlist[$id];
                    unset($navlist[$id]);
                }
            }
        }
        //合并数组
        if(!empty($navlist)) {
            foreach($navlist as $id=>$nav) {
                $new_navlist[$id] = $nav;
            }
        }
        //排除不显示的
        if(!empty($new_navlist) && !empty($static_nav_noexists)) {
            foreach($static_nav_noexists as $id) {
                if(isset($new_navlist[$id])) {
                    unset($new_navlist[$id]);
                }
            }
        }
    }
    //截取
    $new_navlist = array_slice($new_navlist , 0 , 9);
    
    $sub_query = mysql_query("select * from " . table('class') ." where typeid='2'");
    while($sub_nav = mysql_fetch_array($sub_query , MYSQL_ASSOC)) {
        $sub_navlist[] = $sub_nav;
    }
    
    $smarty->assign('navlist' , $new_navlist);
    $smarty->assign('sub_navlist' , $sub_navlist);
    
    $pre = getTplPre();
    
    return $smarty->fetch(WEB_ROOT . "/template/$pre/head.html");
}

/**
 * 获取foot
 * @param $params
 * @param $smarty
 */
function insert_getFoot($params = array() , &$smarty) {
    global $_G;

    //初始化友情链接
    initFriendLink();
    
    //页脚配置
    $smarty->assign('footlist' , getSysConf());
    //友情链接
    $smarty->assign('friendlinks' , $_G['settings']['friendlinks']);
    //获取模板前缀
    $pre = getTplPre();
    
    return $smarty->fetch(WEB_ROOT . "/template/$pre/foot.html");
}

/**
 * 初始化友情链接
 */
function initFriendLink() {
    global $_G;
    static $inited = false;
    
    //如果没有初始化友情链接初始化
    if(!$inited) {
        //获取友情链接
        if(!empty($_G['settings']['friendlinks'])) {
            if(!is_array($_G['settings']['friendlinks'])) {
                $_G['settings']['friendlinks'] = @unserialize($_G['settings']['friendlinks']);
            }
        } else {
            $friendlinks = getFriendlinks();
            if(!empty($friendlinks)) {
                $_G['settings']['friendlinks'] = $friendlinks['svalue'];
            } else {
                $_G['settings']['friendlinks'] = array();
            }
            unset($friendlinks);
        }
        //最多显示100个友情链接
        if(count($_G['settings']['friendlinks']) > 100) {
            $_G['settings']['friendlinks'] = array_slice($_G['settings']['friendlinks'] , 0 , 100);
        }
        $inited = true;
    }
    
    return true;
}

/**
 * 获取网站的url
 */
function getHttpUrl() {
    $http_host = "http://" . $_SERVER['HTTP_HOST'];
    $request_uri = $_SERVER['REQUEST_URI'];
    
    if($request_uri{0} == '/') {
        $request_uri = substr($request_uri , 1);
    }
    $dir = "";
    $arr = explode("/" , $request_uri);
    if(count($arr) == 2) {
        $dir = $arr[0];
    }
    
    return $http_host . (!empty($dir) ? "/" . $dir : "");
}

/**
 * 初始化和当前域名相关的数据
 */
//todolist 域名的相关标准需要配置
function initHost() {
    global $_G;
    
    $host_config_arr = $_G['settings']['host_config'];
    
    //初始化当前域名下的相关信息
    $http_host = trim($_SERVER['HTTP_HOST']);
    
    $current_host = array();
    $switch_name = "";
    $flag = false;
    //先满足完全相等的情况
    if(!empty($host_config_arr)) {
        foreach($host_config_arr as $key=>$val) {
            if($http_host == trim($val['http_host'])) {
                $current_host = $val;
                $switch_name = $key;
                $flag = true;
                break;
            }
        }
    }
    //查找域名中包含设定的子串
    if(!$flag && !empty($host_config_arr)) {
        foreach($host_config_arr as $key=>$val) {
            if(stripos($http_host , $val['http_host']) !== false) {
                $current_host = $val;
                $switch_name = $key;
                break;
            }
        }
    }
    //设置默认的情况    
    if(empty($current_host)) {
        $current_host = $host_config_arr['school'];
        $switch_name = "school";
    }
    if(!empty($current_host)) {
        //重新组织$_G的值
        $_G = array_merge($_G , $current_host['global_var']);
        unset($current_host['global_var']);
        
        $current_host['switch_name'] = $switch_name;
        $_G['settings']['host_config']['current'] = $current_host;
        return true;
    }
    return false;
}
/**
 * 获取模板前缀
 */
function getTplPre() {
    global $_G;
    
    $tpl_pre = trim($_G['settings']['host_config']['current']['tpl_pre']);
    
    return $tpl_pre ? $tpl_pre : "";
}
/**
 * 给表名加前缀
 * @param unknown_type $table
 */
function table($table) {
    global $_G;
    
    if(empty($table)) {
        return "";
    }
    
    $table_pre = trim($_G['settings']['host_config']['current']['table_pre']);
    $table_pre = !empty($table_pre) && is_string($table_pre) ? $table_pre : "";
    $tablename = " `" . $table_pre . $table . "` ";
    
    return $tablename;
}

/**
 * 分页函数
 * @param $num
 * @param $perpage
 * @param $curpage
 * @param $mpurl
 * @param $maxpages
 * @param $page
 * @param $autogoto
 * @param $simple
 */
function multi($num, $perpage, $curpage, $mpurl, $maxpages = 0, $page = 10, $autogoto = FALSE, $simple = FALSE) {
	$_G = array();
	$ajaxtarget = '';
	$a_name = '';
	$dot = '...';
	
	if(strpos($mpurl, '#') !== FALSE) {
		$a_strs = explode('#', $mpurl);
		$mpurl = $a_strs[0];
		$a_name = '#'.$a_strs[1];
	}

	$shownum = $showkbd = FALSE;
	
	$lang['prev'] = '&nbsp;&nbsp;';
	$lang['next'] = "下一页";
	
	$multipage = '';
	$mpurl .= strpos($mpurl, '?') !== FALSE ? '&amp;' : '?';

	$realpages = 1;
	$_G['page_next'] = 0;
	$page -= strlen($curpage) - 1;
	if($page <= 0) {
		$page = 1;
	}
	if($num > $perpage) {

		$offset = floor($page * 0.5);

		$realpages = @ceil($num / $perpage);
		$pages = $maxpages && $maxpages < $realpages ? $maxpages : $realpages;

		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$_G['page_next'] = $to;
		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'page=1'.$a_name.'" class="first"'.$ajaxtarget.'>1 '.$dot.'</a>' : '').
		($curpage > 1 && !$simple ? '<a href="'.$mpurl.'page='.($curpage - 1).$a_name.'" class="prev"'.$ajaxtarget.'>'.$lang['prev'].'</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :
			'<a href="'.$mpurl.'page='.$i.($ajaxtarget && $i == $pages && $autogoto ? '#' : $a_name).'"'.$ajaxtarget.'>'.$i.'</a>';
		}
		$multipage .= ($to < $pages ? '<a href="'.$mpurl.'page='.$pages.$a_name.'" class="last"'.$ajaxtarget.'>'.$dot.' '.$realpages.'</a>' : '').
		($curpage < $pages && !$simple ? '<a href="'.$mpurl.'page='.($curpage + 1).$a_name.'" class="nxt"'.$ajaxtarget.'>'.$lang['next'].'</a>' : '').
		($showkbd && !$simple && $pages > $page && !$ajaxtarget ? '<kbd><input type="text" name="custompage" size="3" onkeydown="if(event.keyCode==13) {window.location=\''.$mpurl.'page=\'+this.value; doane(event);}" /></kbd>' : '');

		$multipage = $multipage ? '<div class="pg">'.($shownum && !$simple ? '<em>&nbsp;'.$num.'&nbsp;</em>' : '').$multipage.'</div>' : '';
	}
	$maxpage = $realpages;
	return $multipage;
}

/**
 * 获取指定id的子板块和自身id的集合
 * @param $id
 */
function getSubClass($ids) {
    if(empty($ids)) {
        return false;
    }
    $ids = is_array($ids) ? array_shift($ids) : $ids;
    
    $current_class = mysql_fetch_array(mysql_query("select * from " . table('class') . " where id='$ids' limit 1") , MYSQL_ASSOC);
    $typeid = max(intval($current_class['typeid']) , 1);
    $i = 4 - $typeid;
    //避免由于$typeid的异常导致的死循环
    $i = $i > 0 ? $i : 0;
    
    //初始化要返回的id值
    $class_ids = is_array($ids) ? $ids : array($ids);
    
    while($i--) {
        $ids = is_array($ids) ? $ids : array($ids);
        $query = mysql_query("select id from " . table('class') ." where uptypeid in(" . implode("," , $ids) . ")");
        while($class = mysql_fetch_array($query , MYSQL_ASSOC)) {
            $class_ids[] = intval($class['id']);
        }
        $class_ids = array_unique(array_merge($class_ids , $ids));
        $ids = $class_ids;
    }
    
    return array_unique($class_ids);
}

/**
 * 获取分类信息
 * @param $ids
 */
function getClass($ids) {
    if(empty($ids)) {
        return false;
    }
    $ids = is_array($ids) ? $ids : array($ids);
    $ids = array_unique($ids);
    
    $query = mysql_query("select * from " . table('class') ." where id in(" . implode(',' , $ids) . ")");
    $class_list = array();
    while($class = mysql_fetch_array($query, MYSQL_ASSOC)) {
        $class_list[$class['id']] = $class;
    }
    
    return !empty($class_list) ? $class_list : false;
}

/**
 * 获取友情链接
 */
function getFriendlinks() {
    $query = mysql_query("select * from " . table('settings') ." where skey='friendlinks' limit 1");
    $friendlinks = mysql_fetch_array($query);
    $friendlinks['svalue'];
    if(!empty($friendlinks['svalue'])) {
        $friendlinks['svalue'] = @unserialize($friendlinks['svalue']);
    } else {
        $friendlinks['svalue'] = array();
    }
    
    return !empty($friendlinks) ? $friendlinks : false;
}

/**
 * 字符串截取
 * @param $str
 * @param $start
 * @param $length
 * @param $dot
 */
function mb_cutstr($str , $length , $dot = "...") {
    if(empty($str)) {
        return "";
    }
    if(mb_strlen($str , 'utf-8') > $length) {
        $str = mb_substr($str , 0 , $length , 'utf-8') . $dot;
    }
    
    return $str;
}

/**
 * 中英文字符串截取
 * @param string $string 需要截取的字符串
 * @param array $length 截取宽度,即多少个英文字母,2个英文字母相当一个汉字的宽度
 * @param bool $append ture后增加...,false没有后缀
 * @return string 返回结果
 */
function cutstr($string, $length, $append = '...') {
    $l = strlen($string);
    if($l <= $length) {
        return $string;
    }

    $pre = chr(1);
    $end = chr(1);
    //html实体
    $entity_arr = array(
            '&amp;', 
            '&quot;', 
            '&lt;', 
            '&gt;',
            '&nbsp;'
            );
    //实体对应字符串
    $char_arr = array(
            $pre.'&'.$end, 
            $pre.'"'.$end, 
            $pre.'<'.$end, 
            $pre.'>'.$end,
            $pre.' '.$end
            );
    $string = str_replace($entity_arr, $char_arr, $string);

    $strcut = '';

    $n = $tn = $noc = 0;
    while($n < $l) {

        $t = ord($string[$n]);
        if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
            $tn = 1; $n++; $noc++;
        } elseif(194 <= $t && $t <= 223) {
            $tn = 2; $n += 2; $noc += 1;
        } elseif(224 <= $t && $t <= 239) {
            $tn = 3; $n += 3; $noc += 2;
        } elseif(240 <= $t && $t <= 247) {
            $tn = 4; $n += 4; $noc += 1;
        } elseif(248 <= $t && $t <= 251) {
            $tn = 5; $n += 5; $noc += 1;
        } elseif($t == 252 || $t == 253) {
            $tn = 6; $n += 6; $noc += 1;
        } else {
            $n++;
        }

        if($noc >= $length) {
            break;
        }

    }
    if($noc > $length) {
        $n -= $tn;
    }

    $strcut = substr($string, 0, $n);

    $strcut = str_replace($char_arr, $entity_arr, $strcut);

    $pos = strrpos($strcut, chr(1));
    if($pos !== false) {
        $strcut = substr($strcut,0,$pos);
    }
    //字符串进行了截取的才增加后缀
    if(!empty($append) && strlen($strcut) < $l) {
        $strcut .= $append;
    }
    
    return $strcut;
}

/**
 * 检测图片是否存在
 * @param $file_path
 */
function checkpictitle($pictitle) {
    if(empty($pictitle)) {
        return false;
    }
    $real_path = WEB_ROOT . "/" .$pictitle;
    
    return is_file($real_path) ? $pictitle : false;
}

/**
 * 获取指定分类id的上级分类信息
 * @param $id
 */
function showmenu($id){
    if(empty($id)) {
        return false;
    }
    
    $query = mysql_query("select * from " . table('class') ." where id=$id limit 1");
    $current_class = mysql_fetch_array($query , MYSQL_ASSOC);
    
    $class_info = array();
    $class_info[] = $current_class;
    
    $uptypeid = intval($current_class['uptypeid']);
    
    while($uptypeid){
        $query = mysql_query("select * from " . table('class') ." where id='$uptypeid' limit 1");
        $current_class = mysql_fetch_array($query , MYSQL_ASSOC);
        if(!empty($current_class)) {
            $class_info[] = $current_class;
            $uptypeid = intval($current_class['uptypeid']);
            $uptypeid = $uptypeid > 0 ? $uptypeid : 0;
        } else {
            break;
        }
        
    }
    //数组逆序
    $new_class_info = array();
    if(!empty($class_info)) {
        while($class = array_pop($class_info)) {
            $new_class_info[] = $class;
        }
    }
    
    return !empty($new_class_info) ? $new_class_info : false;
}

/**
 * 获取新闻同级分类下的其他子分类
 * @param $id
 */
function getSameLevelClass($id , $limit = 0) {
    if(empty($id)) {
        return false;
    }
    $class_list = $up_class = array();
    
    $limit = max($limit , 0);
    
    //查询当前id下的相关信息
    $query_current = mysql_query("select * from " .table('class') . " where id='$id' limit 1");
    $current_class = mysql_fetch_array($query_current , MYSQL_ASSOC);
    $upid = intval($current_class['uptypeid']);
    
    if($upid > 0) {
        $query_current = mysql_query("select * from " .table('class') . " where id='$upid' limit 1");
        $up_class = mysql_fetch_array($query_current , MYSQL_ASSOC);
        
        $limitsql = $limit ? "limit 0,$limit" : "";
        $query_same_level = mysql_query("select * from " . table('class') . " where uptypeid='$upid' $limitsql");
        while($class = mysql_fetch_array($query_same_level , MYSQL_ASSOC)) {
             $class_list[$class['id']] = $class;
        }
    }
    if($limit && !empty($class_list)) {
        $class_list = array_slice($class_list , 0 , $limit);
    }
    //如果当期的分类信息不在返回的列表中
    if(!isset($class_list[$id])) {
        array_pop($class_list);
        array_push($class_list , $current_class);
    }
    $up_class = !empty($up_class) ? $up_class : $current_class;
    
    return array($up_class , $class_list);
} 

/**
 * 获取默认的图文信息
 */
function getStaticPicListId() {
    global $_G;
    
    $static_ids = $_G['static_ids'];
    $checklist = $_G['system']['checklist'];
    
    $pic_list = array();
    if(!empty($static_ids) && !empty($checklist)) {
        foreach($static_ids as $name=>$id) {
            if(isset($checklist[$name])) {
                $is_pic = intval($checklist[$name]);
                if(!empty($is_pic)) {
                    $pic_list[] = $id;
                }
            }
        }
    }
    
    return !empty($pic_list) ? $pic_list : false;
}

/**
 * 获取图文列表信息
 */
function getPiclist($limit = 0 , $title_len = 0) {
    //图片新闻标题图片轮换展示
    $class_ids = $pic_news_list = array();
    
    //获取系统占用的图文id
    $static_pic_ids = getStaticPicListId();
    
    $query_class = mysql_query("select id from " . table('class') . " where is_pic_list='". IS_PIC_LIST . "'");
    while($class = mysql_fetch_array($query_class , MYSQL_ASSOC)) {
        $id = intval($class['id']);
        if($id > 0) {
            if(!empty($static_pic_ids) && in_array($id , $static_pic_ids)) {
                continue;
            }
            $class_ids[] = $id;
        }
    }
    if(!empty($class_ids)) {
        $wheresql = "where uid in(" . implode(',' , $class_ids) . ") and pictitle != ''";
        $ordersql = "order by date desc";
        $limitsql = $limit > 0 ? "limit 0 , $limit" : "limit 0 , 7";
        
        $query_pic_news = mysql_query("select * from " . table('news') . " $wheresql $ordersql $limitsql");
        while($news = mysql_fetch_array($query_pic_news, MYSQL_ASSOC)) {
            $news['pictitle'] = checkpictitle($news['pictitle']);
            $news['title_all'] = $news['title'];
            if($title_len) {
                $news['title'] = cutstr($news['title'] , $title_len , '');
            }
            if(!empty($news['date'])) {
                $news['date'] = date('Y-m-d' , strtotime($news['date']));
            }
            $pic_news_list[] = $news;
        }
    }
    
    return !empty($pic_news_list) ? $pic_news_list : false;
}

/**
 * 获取文章列表
 * @param $type
 */
function getClassNewsList($type = 'settings') {
    global $_G , $_system;
    
    //获取配置文件中的相关设置
    if($type == 'static') {
        $class_arr = $_G['static_ids'];
        $config_arr = $_G['config']['static'];
    } elseif($type == 'settings') {
        $class_arr = $_G['setttings']['layoutorders'];
        if(!empty($class_arr)) {
            $class_arr = is_array($class_arr) ? $class_arr : explode(',' , $class_arr);
        } else {
            $class_arr = array();
        }
        $config_arr = $_G['config']['settings'];
    }
    //处理数据异常
    if(empty($class_arr)) {
        return false;
    }
    $class_arr = is_array($class_arr) ? $class_arr : array($class_arr);
    
    $class_ids = array_values($class_arr);
    $class_list = getClass($class_ids);
    //保存数据结果
    $result_list = array();
    foreach($class_arr as $name=>$id) {
        //获取对应板块的子版块
        $subids = getSubClass($id);
        
        //获取相关配置
        $fields = isset($config_arr[$name]['fields']) ? $config_arr[$name]['fields'] : $config_arr['default']['fields'];
        $limit = isset($config_arr[$name]['limit']) ? $config_arr[$name]['limit'] : $config_arr['default']['limit'];
        $content_len = isset($config_arr[$name]['content_len']) ? $config_arr[$name]['content_len'] : $config_arr['default']['content_len'];
        $title_len = isset($config_arr[$name]['title_len']) ? $config_arr[$name]['title_len'] : $config_arr['default']['title_len'];
        $strip_tag = isset($config_arr[$name]['strip_tag']) ? $config_arr[$name]['strip_tag'] : $config_arr['default']['strip_tag'];
        $date_formart = isset($config_arr[$name]['date_format']) ? $config_arr[$name]['date_format'] : $config_arr['default']['date_format'];
        
        //处理默认值，保证函数正确执行
        $fields = !empty($fields) ? $fields : "id,title";
        $limit = $limit > 0 ? $limit : (intval($_system['newsnums']) > 0 ? $_system['newsnums'] : 5);
        $content_len = max($content_len , 0);
        $title_len = max($title_len , 0);
        $strip_tag = $strip_tag ? true : false;
        $date_formart = !empty($date_formart) ? $date_formart : false;
        
        $sql = "select $fields from " . table('news') ." where uid in(" . implode("," , $subids) . ") order by date desc limit 0,$limit";
        $query = mysql_query($sql);
        
        //检测板块类型
        $class_info = $class_list[$id];
        $is_pic_list = intval($class_info['is_pic_list']) == IS_PIC_LIST ? true : false;
        
        $list = array();
        while($news = mysql_fetch_array($query , MYSQL_ASSOC)) {
            if(isset($news['title']) && empty($news['title'])) {
                continue;
            }
            $news['title_all'] = $news['title'];
            if($title_len) {
                $news['title'] = cutstr($news['title'] , $title_len);
            }
            //处理内容相关的数据
            if(isset($news['content'])) {
                if($strip_tag) {
                    $news['content'] = strip_tags($news['content']);
                }
                if($content_len) {
                    $news['content'] = cutstr($news['content'] , $content_len);
                }
            }
            //格式化时间
            if(!empty($news['date']) && $date_formart) {
                $news['date'] = date($date_formart , strtotime($news['date']));
            }
            //检测图片路径是否存在
            if($is_pic_list) {
                $news['pictitle'] = checkpictitle($news['pictitle']);
            }
            if(isset($news['hits'])) {
                $news['hits'] = intval($news['hits']);
            }
            $news['uid'] = intval($news['uid']);
            
            $list[$news['id']] = $news;
        }
        
        //处理不同情况下的命名规则
        if(is_string($name) && !is_numeric($name)) {    //固定模块的命名规则，兼容以前的代码
            $name = $name . "_list";
        } else {
            $name = "list_$id";                         //用户自定义增加的模块
        }
        
        $result_list[$name]['class_info'] = $class_info;
        $result_list[$name]['datas'] = $list;
    }
    
    return !empty($result_list) ? $result_list : false;
}

/**
 *检查系统的相关配置
 */
function sys_check() {
    global $_G;
    
    $static_ids = $_G['static_ids'];
    $checklist = $_G['system']['checklist'];
    
    //检查id是否完整
    $ids = array_values($static_ids);
    if(!empty($ids)) {
        $query = mysql_query("select * from " . table('class') . " where id in(" . implode(',' , $ids) . ")");
        $class_list = $not_system = array();
        while($class = mysql_fetch_array($query , MYSQL_ASSOC)) {
            if(intval($class['is_system']) !== 1) {
                $not_system[] = $class['id'];
            }
            $class_list[$class['id']] = $class;
        }
        $exists_ids = array_keys($class_list);
        $diff_arr = array_diff($ids , $exists_ids);
        if(!empty($diff_arr)) {
            $message = "系统默认的分类id：" . implode(',' , $diff_arr) . ";不存在!请在数据库中添加!";
            echoalert($message);
            echo $message;
            exit;
        } 
        //自动初始化设定的模块中不是系统的值
        if(!empty($not_system)) {
            mysql_query("update " . table('class') . " set is_system=1 where id in(" . implode(',' , $not_system) . ") and is_system=0");
        }
    }
    
    //检查对应的要求是图文信息的模块是否满足
    $no_meet_list = array();
    
    //获取需要检测的分类名
    if(!empty($checklist)) {
        foreach($checklist as $name=>$val) {
            if(empty($val)) {
                unset($checklist[$name]);
            }
        }
    }
    
    if(!empty($static_ids) && !empty($checklist)) {
        $checkname_arr = array_keys($checklist);
        foreach($checkname_arr as $name) {
            $id = $static_ids[$name];
            //获取对应的分类信息
            $class = $class_list[$id];
            if($class['is_pic_list'] != IS_PIC_LIST) {
                $no_meet_list[$id] = $class['class'];
            }
        }
        if(!empty($no_meet_list)) {
            $comma = "";
            foreach($no_meet_list as $id=>$name) {
                $message .= $comma . "$name(id:$id)";
                $comma = ',';
            }
            $message = "分类:" . $message . ";必须是图文类型!";
            echoalert($message);
            echo $message;
            exit;
        }
    }
    
    return true;
}

// 浏览器友好的变量输出
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = "<pre>" . $label . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
         
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}

//检测字符串长度，不区分大小写
function mbStrLenth($string) {
	if(strlen($string) <= 0) {
		return 0;
	}
	$string = trim($string);
	
	$pre = chr(1);
	$end = chr(1);
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), $string);
	
	$n = $length = 0;
	$strlen = strlen($string);
	
	while($n < $strlen) {
		$t = ord($string[$n]);
		if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
			$n++;
		} elseif(194 <= $t && $t <= 223) {
			$n += 2; 
		} elseif(224 <= $t && $t <= 239) {
			$n += 3; 
		} elseif(240 <= $t && $t <= 247) {
			$n += 4; 
		} elseif(248 <= $t && $t <= 251) {
			$n += 5;
		} elseif($t == 252 || $t == 253) {
			$n += 6;
		} else {
			$n++;
		}
		$length ++;
	}
	
	return $length;
}



//解析当前传入的id参数，并验证数据的级联关系是否正确
function parse_classid($id = 0) {
    if(empty($id)) {
        $id = $_GET['id'];
    }
    //如果将对应的id转换成整数为空，则退出,保证id不以0或者字母开头
    if(intval($id) === 0) {
        return false;
    }
    //将参数转换成字符串，保证分割不出错
    $id = strval($id);
    $ids_arr = explode('_' , $id);
    //初步检测参数的合法性
    $new_ids_arr = array();
    if(!empty($ids_arr)) {
        foreach($ids_arr as $key=>$cid) {
           $cid = intval($cid);
           if(!empty($cid)) {
               $new_ids_arr[] = $cid;
           } else {
               break;
           }
        }
    }
    //检测转换后的数组中的第一个元素是否和传入的参数对应
    if(empty($new_ids_arr) || $new_ids_arr[0] != intval($id)) {
        return false;
    }
    //保证数据最多处理3级
    $count = count($new_ids_arr);
    if($count > 3) {
        $new_ids_arr = array_slice($new_ids_arr , 0 , 3);
    }
    //保证参数的层级关系正确
    if(isset($new_ids_arr[0])) {
        $query = mysql_query("select id from " . table('class') . " where id='" . $new_ids_arr[0] . "' limit 1");
        $result = mysql_fetch_array($query , MYSQL_ASSOC);
        if(empty($result)) {
            unset($new_ids_arr);
        }
    }
    if(isset($new_ids_arr[1])) {
        $query = mysql_query("select id from " . table('class') . " where id='" . $new_ids_arr[1] . "' and uptypeid='" . $new_ids_arr[0] . "' limit 1");
        $result = mysql_fetch_array($query , MYSQL_ASSOC);
        if(empty($result)) {
            $new_ids_arr = array_slice($new_ids_arr , 0 , 1);
        }
    }
    if(isset($new_ids_arr[2])) {
        $query = mysql_query("select id from " . table('class') . " where id='" . $new_ids_arr[2] . "' and uptypeid='" . $new_ids_arr[1] . "' limit 1");
        $result = mysql_fetch_array($query , MYSQL_ASSOC);
        if(empty($result)) {
            $new_ids_arr = array_slice($new_ids_arr , 0 , 2);
        }
    }
    
    return !empty($new_ids_arr) ? $new_ids_arr : false;
}

/**
 * 初始化左侧菜单信息
 * @param $id
 */
function getSublistById($id) {
    if(empty($id)) {
        return false;
    }
    //获取分类下的typeid
    $query_current = mysql_query("select * from " . table('class') . " where id='$id' limit 1");
    $current_class = mysql_fetch_array($query_current , MYSQL_ASSOC);
    $typeid = intval($current_class['typeid']);
    
    switch($typeid) {
        case 1:
        case 2:
            $first = $second = array();
            $first_query = mysql_query("select * from " . table('class') . " where uptypeid='$id'");
            while($class = mysql_fetch_array($first_query , MYSQL_ASSOC)) {
                if(!empty($class['id'])) {
                    $class['id_all'] = $id . "_" . $class['id'];
                    $first[$class['id']] = $class;
                }
            }
            if(!empty($first)) {
                $second_ids = array_keys($first);
                $second_query = mysql_query("select * from " . table('class') . " where uptypeid in(" . implode(',' , $second_ids) . ")");
                while($class = mysql_fetch_array($second_query , MYSQL_ASSOC)) {
                    if(!empty($id) && !empty($class['uptypeid']) && !empty($class['id'])) {
                        $class['id_all'] = $id . "_" . $class['uptypeid'] . "_" . $class['id'];
                        $second[$class['uptypeid']][$class['id']] = $class;
                    }
                }
                //数据合并
                if(!empty($second) && !empty($first)) {
                    foreach($first as $key=>$class) {
                        if(!empty($second[$key])) {
                            $class['sub_list'] = $second[$key];
                        }
                        $first[$key] = $class;
                    }
                }
            }
            break;
        case 3:
            $first_query = mysql_query("select * from " . table('class') . " where uptypeid='$id'");
            while($class = mysql_fetch_array($first_query , MYSQL_ASSOC)) {
                if(!empty($class['id'])) {
                    $class['id_all'] = $id . "_" . $class['id'];
                    $first[$class['id']] = $class;
                }
            }
            break;
        default:
            break;
    }
    //如果没有找到相应的结果
    if(empty($first) && !empty($current_class)) {
        $current_class['id_all'] = $id;
        $first = array(
            $id=>$current_class
        );
    }
    
    return !empty($first) ? $first : false;
}

/**
 * 获取页面初始化的id和作为查询条件的id
 * @param $last_id
 */
function getQueryId($last_id) {
    if(empty($last_id)) {
        return false;
    }
    
    $last_id = is_array($last_id) ? array_shift($last_id) : $last_id;
    
    $query = mysql_query("select * from " . table('class') . " where id='$last_id' limit 1");
    $result = mysql_fetch_array($query , MYSQL_ASSOC);
    
    $typeid = intval($result['typeid']);
    $id = intval($result['id']);
    
    $return_id = 0;
    $where_ids = $sub_list = array();
    
    //获取当前分类及其子分类
    if($typeid == 1) {
        //获取第一个子分类和其子分类的下级分类
       $query = mysql_query("select * from " . table('class') . " where uptypeid='$id' and typeid='2' limit 1");
       $class = mysql_fetch_array($query , MYSQL_ASSOC);
       //获取第一个子分类信息
       $return_id = intval($class['id']);
    } else {
        $return_id = $id;
    }
    
    //查询其子分类
    if(in_array($typeid , array(1, 2, 3)) && !empty($return_id)) {
       $query_sub = mysql_query("select * from " . table('class') . " where uptypeid='$return_id' and typeid='3'");
       while($class = mysql_fetch_array($query_sub , MYSQL_ASSOC)) {
           $classid = intval($class['id']);
           if(!empty($classid)) {
               $sub_list[] = $classid;
           }
       }
    }
    
    $return_id = $return_id ? $return_id : $id;
    //组合查询条件
    if(!empty($return_id) && !empty($sub_list)) {
        $sub_list = array_merge(array($return_id) , $sub_list);
        $sub_list = array_unique($sub_list);
    } else {
        $sub_list[] = $return_id;
    }
    
    return array($return_id , $sub_list);
}

?>