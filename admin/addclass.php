<?php
/**
* Author:
* Email:@qq.com
* Date: 2011-5-28
* http://hi.baidu.com/
*/
define('GUY','true');
require '../common.inc.php';

if(!isset($_COOKIE['username'])){
	echo'<script type="text/javascript"> alert("非法登录!");top.location.href="login.php"; </script>';	
	exit;
}

if($_GET['action']=='addclass'){
	$ispic = 1;
	$_html=array();
	$_html['name']=$_POST['name'];
	if(empty($_html['name'])){
	    echo'<script type="text/javascript"> alert("分类名称不能为空!");window.history.go(-1); </script>';
        exit;	    
	}
    if(mbStrLenth($_html['name'])>6){
        echo'<script type="text/javascript"> alert("分类名称不能多于六个汉字!");window.history.go(-1); </script>';	
        exit;	    
	}
        $rs = mysql_query("select * from ".table("class")." where class = '".$_html['name']."'");
	    if(mysql_fetch_row($rs)){
	        echo'<script type="text/javascript"> alert("分类名称已存在!");window.history.go(-1); </script>';	
	        exit;
	    }
	
	$_html['ispiclist']=$_POST['checkpic'];
	if($_html['ispiclist'] == 'on'){
		$ispic = 2;
	}
	if($_POST['typeid']=='one'){
	    $menu_1 = mysql_query("select * from ".table('class')." where typeid=1");
	    global $_S;
	while($menuinfo = mysql_fetch_array($menu_1,MYSQL_ASSOC)){
	    if(in_array($menuinfo['id'],$_S['settings']['nav']['static_nav_noexists'])){
	        continue;
	    }
	    $menuinfoes[] = $menuinfo;
	}
	if(count($menuinfoes)>8){
	    echo'<script type="text/javascript"> alert("一级标题最多十个，您可以修改其它标题名称!");location.href="addclass.php"; </script>';	
        exit;	    
	}
	mysql_query("insert into " . table('class') . " (class,typeid,uptypeid,is_pic_list) values('{$_html['name']}',1,0,'{$ispic}')");
	echoalerthistory('添加成功');	
	}else{
	$id = intval($_POST['typeid']);
	$_result=mysql_query("select typeid from " . table('class') ." where id='{$id}'");	
	$_rows=mysql_fetch_array($_result,MYSQL_ASSOC);
	$_html['uptypeid']=intval($_POST['typeid']);
	$_html['typeid']=$_rows['typeid']+1;
	mysql_query("insert into " . table('class') . " (class,typeid,uptypeid,is_pic_list) values('{$_html['name']}','{$_html['typeid']}','{$_html['uptypeid']}','{$ispic}')");	
	echoalerthistory('添加成功');
	}

} elseif($_GET['action']=='modify'){
	if(empty($_POST['name'])){
		echoalerthistory('请填写新的分类名!');
	}
	$_html=array();
	$_html['name']=$_POST['name'];
	if(empty($_html['name'])){
	    echo "分类名称不能为空！"."<a href='./addclass.php'>返回</a>重新输入。";
        die;	    
	}
	$_html['ispiclist']=$_POST['checkpic'];

    if(strlen($_html['name'])>18){
        echo'<script type="text/javascript"> alert("分类名称不能多于六个汉字!");location.href="addclass.php"; </script>';	
        exit;	    
	}
        $rs = mysql_query("select * from ".table("class")." where class = '".$_html['name']."'");
	    if(mysql_fetch_row($rs)){
	        echo'<script type="text/javascript"> alert("分类名称已存在!");window.history.go(-1); </script>';	
	        exit;
	    }
	if($_POST['typeid']=='one'){
	    echoalerthistory('您没有选择要修改的分类名');	
	} else {
    	$_html['id']=intval($_POST['typeid']);
    	mysql_query("update " . table('class') . " set class='{$_html['name']}' where id='{$_html['id']}'");	
    	echoalerthistory('修改成功');
	}

} elseif($_GET['action']=='del'){
	$_html=array();
	if($_POST['typeid']=='one'){
	    echoalerthistory('您没有选择要修改的分类名');	
	} else {
    	$_html['id']=intval($_POST['typeid']);
    	delete($_html['id']);
    	echoalert('删除成功,请刷新页面');
    	echo "<script type='text/javascript'>window.location.href='addclass.php';</script>";
    	exit;
	}
}

/**
 * 删除分类信息
 * @param $id
 */
function delete($id) {
    //删除分类信息
    $idslist = is_array($id) ? $id : array($id);
    
    //检测是否删除系统默认的数据
    if(!empty($idslist)) {
        $query_class = mysql_query("select * from " . table('class') . " where id in(" . implode(',' , $idslist) . ")");
        while($class = mysql_fetch_array($query_class)) {
            if(!empty($class['is_system'])) {
                echoalerthistory('存在系统默认分类,不允许删除!');
                exit;
            }
        }
    }
    
    //初始化对应的分类
    $result_ids = $idslist;
    while(true && !empty($idslist)) {
        $query_class = mysql_query("select id,is_system from " . table('class') . " where uptypeid in(" . implode(',' , $idslist) . ')');
        $subidslist = array();
        while($class = mysql_fetch_array($query_class , MYSQL_ASSOC)) {
            if(!empty($class['is_system'])) {
                echoalerthistory('子分类中存在系统默认分类,不允许删除!');
                exit;
            } elseif(intval($class['id']) > 0) {
                $subidslist[] = intval($class['id']);
            }
        }
        //没有找到则退出
        if(empty($subidslist)) {
            break;
        } else {
            $idslist = $subidslist;
            $result_ids = array_unique(array_merge($result_ids , $subidslist));
        }
    }
    //删除class表中的信息
    if(!empty($result_ids)) {
        mysql_query("delete from " . table('class') . " where id in(" . implode(',' , $result_ids) . ") limit " . count($result_ids));
        //删除文章信息
        foreach($result_ids as $id) {
            $query_news = mysql_query("select id,pictitle from " . table('news') . " where uid='$id'");
            $news_id_list = $news_pic_list = array();
            while($news = mysql_fetch_array($query_news , MYSQL_ASSOC)) {
                $news_id_list[] = $news['id'];
                if(!empty($news['pictitle'])) {
                    $news_pic_list[] = $news['pictitle'];
                }
            }
            //删除文章内容
            if(!empty($news_id_list)) {
                mysql_query("delete from " . table('news') . " where id in(" . implode(',' , $news_id_list) . ") limit " . count($news_id_list));
            }
            //删除附件信息
            delete_pic($news_pic_list);
        }
    }
    return true;
}

/**
 * 删除文件中的附件信息
 * @param $pic_list
 */
function delete_pic($pic_list) {
    if(empty($pic_list)) {
        return false;
    }
    $pic_list = is_array($pic_list) ? $pic_list : array($pic_list);
    foreach($pic_list as $pic_path) {
        $pic_path = WEB_ROOT . "/" . $pic_path;
        if(!empty($pic_path) && is_file($pic_path)) {
            @unlink($pic_path);
        }
    }
    
    return true;
}


//function tree($_id,$_num){
//
//$_results=mysql_query("select id,class from " . table('class') ." where uptypeid='{$_id}'");
//while(!!$_row=mysql_fetch_array($_results,MYSQL_ASSOC)){
//echo "<option value='".$_row['class']."'>".str_repeat('　',$_num)."|-{$_row['class']}</option>";
//tree($_row['id'],$_num+1);
//}
//}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/addclass.css" />
<title>添加新闻分类</title>
<script language="javascript">
//	function checkmodify(){
//		var ids = document.getElementById("hiden").value;
//		var sel = document.getElementById("modifymenu");
//		var index=sel.selectedIndex; 
//		var id=sel.options[index].getAttribute("value");
//		var val = sel.options[index].innerHTML;
//		var flag = ids.indexOf(id);
//		var btn = document.getElementById("modifysubmit");
//		var input = document.getElementById("menuinput");
//		if(flag>=0){
//			btn.disabled=true;
//			btn.readOnly=true;
//			input.value=val;
//			input.disabled=true;
//			input.readOnly=true;
//			var text = document.getElementById("modifytext").innerHTML="系统占用,不能修改分类！";
//		}else{
//			input.value="";
//			btn.disabled=false;
//			btn.readOnly=false;
//			input.disabled=false;
//			input.readOnly=false;
//			var text = document.getElementById("modifytext").innerHTML="";
//		}
//	}

	function checkdel(){
		var ids = document.getElementById("hiden").value;
		var sel = document.getElementById("delmenu");
		var index=sel.selectedIndex; 
		var id=sel.options[index].getAttribute("value");
		var flag = ids.indexOf(id);
		var delbtn = document.getElementById("delsubmit");
		if(flag>=0){
			delbtn.disabled=true;
			delbtn.readOnly=true;
			var deltext = document.getElementById("deltext").innerHTML="系统占用,不能删除分类！";
		}else{
			delbtn.disabled=false;
			delbtn.readOnly=false;
			var deltext = document.getElementById("deltext").innerHTML="";
		}
	}
</script>
</head>

<body>
<?php 
    global $_G;
    $checkarr = implode(",",array_values($_G['static_ids']));
    echo "<input type='hidden' id='hiden' value='".$checkarr."'></input>";
?>

<div id="addclass">
<p>添加新闻分类(可无限分类 )</p>
<form method="post" action="?action=addclass">
<ul>
<li>添加分类名：<input type="text" name="name" /></li>
<li>图片类：<input type="checkbox" name="checkpic" />（选择后则为图片类，默认文字类）</li>
<li>所属　分类：<select name="typeid">
<option value="one">做为一级分类</option>
<?php
$_result=mysql_query("select id,class from " . table('class') ." where typeid=1");
while (!!$_rows=mysql_fetch_array($_result,MYSQL_ASSOC)) { ?>
<option value="<?php echo $_rows['id']?>"><?php echo $_rows['class']?></option>
<?php tree($_rows['id'],1);
}
?>

</select>
</li>
<li class="li1"><input type="submit" value="添加" /></li>
</ul>
</form>
</div>
<div  id="modify">
<p>修改新闻分类</p>
<form method="post" action="?action=modify">
<ul>
<li>修改分类名：<input type="text" id="menuinput" name="name" /><span id="modifytext" style="color:red;"></span></li>
<li>所属　分类：<select id="modifymenu" name="typeid"">
<option value="one">点击选择分类名</option>
<?php
$_result=mysql_query("select id,class from " . table('class') ." where typeid=1");
while (!!$_rows=mysql_fetch_array($_result,MYSQL_ASSOC)) { ?>
<option value="<?php echo $_rows['id']?>"><?php echo $_rows['class']?></option>
<?php tree($_rows['id'],1);
}
?>
</select>
</li>
<li><input type="submit" id="modifysubmit" value="修改" /></li>
</ul>
</form>
</div>
<div id="modify">
<p>删除新闻分类</p>
<form method="post" action="?action=del">
<ul>
<li>分类：<select id="delmenu" name="typeid" onchange="checkdel();">
<option value="one">点击选择分类名</option>
<?php
$_result=mysql_query("select id,class from " . table('class') ." where typeid=1");
while (!!$_rows=mysql_fetch_array($_result,MYSQL_ASSOC)) { ?>
<option value="<?php echo $_rows['id']?>"><?php echo $_rows['class']?></option>
<?php tree($_rows['id'],1);
}
?>
</select>
<span id="deltext" style="color:red;"></span></li>
<li><input type="submit" id="delsubmit" value="删除" onclick="return confirm('如果删除文章分类标题，此文章下的文章分类标题和文章也会被删除，您确认要删除吗？');"/></li>
</ul>
</form>
</div>
<?php 
mysql_free_result($_result);
mysql_close();
?>
</body>
</html>
