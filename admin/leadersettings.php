<?php
/**
* Author:
* Email:@qq.com
* Date: 2011-5-30
* http://hi.baidu.com/
*/
define('GUY','true');
require '../common.inc.php';
require './uploadfile.class.php';

//领导班子默认设置
$uid = intval($_G['static_ids']['leader']);

if(!isset($_COOKIE['username'])){
	echo '<script type="text/javascript"> alert("非法登录!");top.location.href="login.php"; </script>';	
	exit;
} elseif(empty($uid)) {
    echo '<script type="text/javascript"> alert("该功能暂时未开通!，请修改配置文件:common.inc.php中的默认设置!");location.href="index.php"; </script>';	
	exit;
}

$action = trim($_GET['action']);
$action = $action && in_array($action, array('add', 'update')) ? $action : 'add';

if($action == 'add' && $_POST['submit']) {
	$_html = array();
	$_html['pictitle'] = '';
	$_html['title'] = trim($_POST['title']);
	$_html['uid'] = $uid;	
	$_html['birth'] = trim($_POST['birth']);
	$_html['content'] = trim($_POST['content']);
	
	if(empty($_html['title'])){
	    echo'<script type="text/javascript"> alert("文章标题不能为空!");window.history.go(-1); </script>';	
	    exit;
	} elseif(empty($_html['content'])){
	    echo'<script type="text/javascript"> alert("文章内容不能为空!");window.history.go(-1); </script>';	
	    exit;
	}
	
    //内容的最大长度
	if(isset($_html['content'][65500])) {
	    echoalert("内容过长，请截取后在输入!");
	    echo "<script type=\"text/javascript\">window.history.go(-1);</script>";
	    exit;
	} elseif(strlen(cutstr($_html['title'] , 70 , '')) < strlen($_html['title'])) {
	    echoalert("标题过长，请截取后在输入!");
	    echo "<script type=\"text/javascript\">window.history.go(-1);</script>";
	    exit;
	}
	
	//处理上传图片
	if( isset($_FILES['pictitle']['name']) && !empty($_FILES['pictitle']['name'])) {
    	$up_init = array(
    			'attachmentspath' => WEB_ROOT.'/uploadpic',
    			'resize_width'=>'176',
    			'resize_height'=>'114',
    			'ifresize'=>'true'
    			);
    	$upload = new uploadfile($up_init);
    	$upload->allow_type = explode(",", 'jpg,gif,png,bmp');
    	$file = $upload->upfile('pictitle');
	    if(!empty($file)){
		    if($file['ext'] != 'bmp' && isset($file['getsmallfilename'])) {
		        $_html['pictitle'] = str_replace(WEB_ROOT . '/', '', $file['getsmallfilename']);
		    } elseif($file['ext'] == 'bmp' && isset($file['getfilename'])) {
		        $_html['pictitle'] = str_replace(WEB_ROOT . '/', '', $file['getfilename']);
		    }
		}
	}
	
	if(!empty($_POST['submit_update'])) {
	    $id = intval($_POST['id']);
	    $id = max($id, 0);
	}
	
	//检测图片信息
	if(empty($_html['pictitle']) && !empty($id)) {
	    $news = mysql_fetch_array(mysql_query("select * from " . table('news') . " where id='$id' limit 1") , MYSQL_ASSOC);
	    $_html['pictitle'] = checkpictitle($news['pictitle']);
	}
	
	//处理异常情况
	if(empty($_html['title'])) {
	    echo "<script>alert('请输入新闻标题!');window.location.href='leadersettings.php';</script>";
	    exit;
	} elseif(empty($_html['content'])) {
	    echo "<script>alert('请输入新闻内容!');window.location.href='leadersettings.php';</script>";
	    exit;
	} elseif(empty($_html['pictitle'])) {
	    echo "<script>alert('请上传图片信息!');window.location.href='leadersettings.php';</script>";
		exit;
	}
	//数据保存
	if(!empty($_POST['submit_add'])) {
	    $flag = mysql_query("insert into " . table('news') . "(title,uid,birth,content,date,pictitle) values('{$_html['title']}','{$_html['uid']}','{$_html['birth']}','{$_html['content']}',now(),'{$_html['pictitle']}')");
	    if($flag){	
	       echoalert('添加成功!');
	    }else{ 
	        echo'<script type="text/javascript"> alert("添加失败!!");window.history.go(-1); </script>';
	        exit;
	    }
	} else {
	    $setsql = $comma = "";
	    if(!empty($_html)) {
	        foreach($_html as $key=>$val) {
	            $setsql .= $comma . "`$key`='$val'";
	            $comma = ",";
	        }
	    }
	    if($id && $setsql) {
	        $flag = mysql_query("update " . table('news') . " set $setsql where id='$id' limit 1");
            if($flag){	        
	            echoalert('更新成功!');
	            echo "<script>window.location.href='leadermanage.php';</script>";
	            exit;
            }else {
                echo'<script type="text/javascript"> alert("更新失败!");window.history.go(-1); </script>';
	            exit;
            }
	    }
	}
} elseif($action == 'update') {
    $id = intval($_GET['id']);
    $id = max($id, 0);
    if(!empty($id)) {
        $query_news = mysql_query("select * from " . table('news') ." where id='$id' limit 1");
        $current_news = mysql_fetch_array($query_news , MYSQL_ASSOC);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/addnews.css" />
<title>领导班子管理</title>
<script charset="utf-8" src="kindedit/kindeditor.js"></script><script>        KE.show({                id : 'editor_id'        });</script>
</head>
<body>
    <div id="addnews">
        <p>添加领导班子</p>
        <form method="post" id="form" enctype="multipart/form-data" action="?action=add">
        	<input type="hidden" name="submit" value="true"/>
        	<?php if(!empty($current_news)){?>
        		<input type="hidden" name="id" value="<?php echo $current_news['id'];?>"/>
        		<input type="hidden" name="submit_update" value="true"/>
        	<?php } else {?>
        		<input type="hidden" name="submit_add" value="true"/>
        	<?php }?>
            <ul>
                <li class="li1">新闻标题：<input type="text" name="title" value="<?php echo $current_news['title'];?>"/></li>
                <li>编&nbsp;&nbsp;&nbsp;&nbsp;辑：<input type="text" name="birth" value="<?php echo $current_news['birth'];?>"/></li>
                <li>图片上传:<input type="file" name="pictitle"/></li>
                <li>新闻内容：</li>
                <li class="li2"><textarea id="editor_id"  name="content"><?php echo $current_news['content'];?></textarea></li>
                <li class="li3"><input type="submit" value="提交"/></li>
            </ul>
        </form>
    </div>
</body>
</html>
