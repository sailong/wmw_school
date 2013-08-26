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

if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
	$_result=mysql_query("select * from " . table('news') ." where id='{$_GET['id']}'");
	$_rows=mysql_fetch_array($_result , MYSQL_ASSOC);
	define('ROWS',$_rows['uid']);
}

$id = max(intval($_GET['id']) , 0);
if(!empty($id)) {
    $query_news = mysql_query("select * from " . table('news') . " where id='$id' limit 1");
    $current_news = mysql_fetch_array($query_news , MYSQL_ASSOC);
    $uid = intval($current_news['uid']);
    if(!empty($uid)) {
        $current_class = mysql_fetch_array(mysql_query("select * from " . table('class') . " where id=$uid") , MYSQL_ASSOC);
        $ispic = $current_class['is_pic_list'] == IS_PIC_LIST ? true : false;
    }
}
//检测文章信息是否存在
if(empty($id) || empty($current_news)) {
    echo'<script type="text/javascript"> alert("文章信息不存在!");window.history.go(-1); </script>';	
    exit;
}

function tre($_id,$_num){
    $_resultt=mysql_query("select id,class from " . table('class') ." where uptypeid='{$_id}'");
    while(!!$_roww=mysql_fetch_array($_resultt,MYSQL_ASSOC)){
    	if(ROWS==$_roww['id']){
            echo "<option value='".$_roww['id']."' selected='selected'>".str_repeat('　',$_num)."|-{$_roww['class']}</option>";}else{
            echo "<option value='".$_roww['id']."'>".str_repeat('　',$_num)."|-{$_roww['class']}</option>";	
        }
        tre($_roww['id'],$_num+1);
    }
}

if($_GET['action']=='modify'){
	$_html = array();
	$_html['pictitle'] = '';
	$_html['title'] = trim($_POST['title']);
	$_html['uid'] = intval($_POST['uid']);	
	$_html['birth'] = trim($_POST['birth']);
	$_html['content'] = trim($_POST['content']);
	
    if(empty($_html['title'])){
	    echo '<script type="text/javascript"> alert("文章标题不能为空!");window.open("modifynews.php?id='. $id .'" , "main"); </script>';	
	    exit;
	} elseif(empty($_html['content'])){
	    echo '<script type="text/javascript"> alert("文章内容不能为空!");window.open("modifynews.php?id='. $id .'" , "main"); </script>';	
	    exit;
	}
	
	if(trim($_POST['pic']) == 'ispic' ) {
		if (isset( $_FILES['pictitle']['name'] ) && $_FILES['pictitle']['name'] != "" ){ 
			$up_init = array(
					'attachmentspath' => WEB_ROOT.'/uploadpic',
					'resize_width'=>'208',
					'resize_height'=>'144',
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
		} elseif(!empty($current_news['pictitle']) && checkpictitle($current_news['pictitle'])) {
		    $_html['pictitle'] = $current_news['pictitle'];
		}
		//保证图片信息不能为空!
		if(empty($_html['pictitle'])){
			echo "<script>alert('请上传图片标题');window.open('modifynews.php?id=$id' , 'main');</script>";
			exit;
		}
	} else {
	    unset($_html['pictitle']);
	}
	
	$flag = false;
	if(!empty($_html)) {
	    $setsql = $comma = "";
	    foreach($_html as $field=>$val) {
	        $setsql .= $comma . "`$field`='$val'";
	        $comma = ",";
	    }
	    $flag = mysql_query("update " . table('news') . " set $setsql where id='$id' limit 1");
	}
	if($flag){
	    echo '<script type="text/javascript"> alert("修改成功！");window.open("addlook.php" , "main");</script>';
	}else{
        echo'<script type="text/javascript"> alert("修改失败!!");window.open("addlook.php" , "main");</script>';
	    exit;    
	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/addnews.css" />
<title>修改新闻</title>
<script charset="utf-8" src="kindedit/kindeditor.js"></script><script>        KE.show({                id : 'editor_id'        });</script>
</head>
<body>
<div id="addnews">
<p>修改新闻</p>
<form method="post"  enctype="multipart/form-data" action="?action=modify&id=<?php echo $_GET['id'] ?>">
<ul>
<li>所属分类：<select name="uid"  onchange="ispic()">
<?php 
$_results=mysql_query("select id,class,is_pic_list from " . table('class') ." where typeid=1");

while(!!$_row=mysql_fetch_array($_results,MYSQL_ASSOC)){?>
	<option value="<?php echo $_row['id']?>" ispic="<?php echo $_row['is_pic_list']?>" <?php 
	if(ROWS==$_row['id']){
	echo 'selected="selected"';
	}
	?>><?php echo $_row['class']?></option>

<?php
tre($_row['id'],1);
}
?>
</select>
</li>
<li class="li1">新闻标题：<input type="text" name="title" value="<?php echo $_rows['title']?>"/></li>
<li>编&nbsp;&nbsp;&nbsp;&nbsp;辑：<input type="text" name="birth" value="<?php echo $_rows['birth']?>" /></li>
<?php
if($_GET['ispic'] == 2 || $ispic){
	echo "<li id='hid'>图片标题：<input type='file' id='pictitle' name='pictitle' /><input type='hidden' name='pic' value='ispic'><img id='pic_zoom' src='../{$_rows['pictitle']}'></li>";
} 
?>
<li >新闻内容：</li>
<li class="li2"><textarea id="editor_id"  name="content"><?php echo $_rows['content']?></textarea></li>
<li class="li3"><input type="submit" value="提交" /></li>
</ul>
<script type="text/javascript">
    var pic = document.getElementById('pic_zoom');
    var image = new Image;
    image.src = pic.src;
    if(image.width > 300) {
    	var scale = 300 * 1.0 / image.width;
    	pic.width = 300;
    	pic.height = pic.height * scale;
    } else if(image.height > 400) {
    	var scale_h = 400 * 1.0 / image.width;
    	pic.width = pic.width * scale_h;
    	pic.height = 400;
    }
</script>
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
	if(ispic==2){
		document.getElementById("hid").innerHTML="图片标题：<input type='file' id='pictitle' name='pictitle' /><input type='hidden' name='pic' value='ispic'>";
		document.getElementById("hid").style.display="block";
	}else{
		document.getElementById("hid").innerHTML="";
		document.getElementById("hid").style.display="none";
	}	
}
</script>
</body>
</html>
