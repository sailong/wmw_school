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
if(!isset($_COOKIE['username'])){
	echo'<script type="text/javascript"> alert("非法登录!");top.location.href="login.php"; </script>';	
	exit;
}
if($_GET['action']=='add'){
	$_html=array();
	$_html['pictitle'] = '';
	$_html['title'] = trim($_POST['title']);
	$_html['uid'] = $_POST['uid'];	
	$_html['birth'] = trim($_POST['birth']);
	$_html['content'] = trim($_POST['content']);
	
	//检测数据是否为空
    if(empty($_html['birth'])){
	    echo'<script type="text/javascript"> alert("文章编辑不能为空!");window.history.go(-1); </script>';	
	    exit;
	} elseif(empty($_html['title'])){
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

	if(trim($_POST['pic']) == 'ispic' ){
		if (isset( $_FILES['pictitle']['name'] ) && $_FILES['pictitle']['name'] != "" ){
		if($_html['uid'] == 8){
	        $up_init = array(
					'attachmentspath' => WEB_ROOT.'/uploadpic',
					'resize_width'=>'450',
					'resize_height'=>'282',
					'ifresize'=>'true'
					);
	    }else{    
		    $up_init = array(
					'attachmentspath' => WEB_ROOT.'/uploadpic',
					'resize_width'=>'208',
					'resize_height'=>'144',
					'ifresize'=>'true'
					);
	    }
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
		//检测图片信息是否存在
		if(empty($_html['pictitle'])){
			echo "<script>alert('请上传图片标题');window.history.go(-1);</script>";
			exit;
		}
	}

	$flag = mysql_query("insert into " . table('news') . "(title,uid,birth,content,date,pictitle) values('{$_html['title']}','{$_html['uid']}','{$_html['birth']}','{$_html['content']}',now(),'{$_html['pictitle']}')");	
	if($flag){
	    echoalert('添加成功!');
	}else{
	    echo'<script type="text/javascript"> alert("添加失败!!");window.history.go(-1); </script>';
	    exit;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/addnews.css" />
<title>添加新闻</title>
<script charset="utf-8" src="kindedit/kindeditor.js"></script>
<script>        KE.show({                id : 'editor_id'        });</script>
</head>
<body onload="ispic();">
<div id="addnews">
    <p>添加新闻</p>
    <form method="post" id="form" enctype="multipart/form-data" action="?action=add">
        <ul>
            <li>所属分类：<select id="uid" name="uid" onchange="ispic()">
                <?php 
                $_result=mysql_query("select id,class,is_pic_list from " . table('class') ." where typeid=1");
                
                while(!!$_rows=mysql_fetch_array($_result,MYSQL_ASSOC)){?>
                	<option value="<?php echo $_rows['id']?>" ispic="<?php echo $_rows['is_pic_list']?>"><?php echo $_rows['class']?></option>
                <?php
                trees($_rows['id'],1);
                }
                ?>
                </select>
            </li>
            <li class="li1">新闻标题：<input type="text" name="title" /></li>
            <li>编&nbsp;&nbsp;&nbsp;&nbsp;辑：<input type="text" name="birth" /></li>
            <li id="hid" style="display:none;"></li>
            <li >新闻内容：</li>
            <li class="li2"><textarea id="editor_id"  name="content" ></textarea></li>
            <li class="li3"><input type="submit" value="提交"/></li>
        </ul>
    </form>
</div>
<?php 
mysql_free_result($_result);
mysql_close();
?>
<script language="javascript">
function ispic(){
	var uid = document.getElementById("uid");
	var index=uid.selectedIndex;  
	var ispic=uid.options[index].getAttribute("ispic");  
	var val=uid.options[index].getAttribute("value"); 
	if(ispic==2&&val!=8){
		document.getElementById("hid").innerHTML="上传图片：<input type='file' id='pictitle' name='pictitle' /><input type='hidden' name='pic' value='ispic'><label style='color:red'>(允许类型:jpg , gif , png , bmp)</label>";
		document.getElementById("hid").style.display="block";
	}else if(ispic==2&&val==8){
		document.getElementById("hid").innerHTML="上传图片：<input type='file' id='pictitle' name='pictitle' /><input type='hidden' name='pic' value='ispic'><label style='color:red'>(允许类型:jpg , gif , png , bmp 图片大小：450*282)</label>";
		document.getElementById("hid").style.display="block";
	}else{
		document.getElementById("hid").innerHTML="";
		document.getElementById("hid").style.display="none";
	}	
}
</script>
</body>
</html>
