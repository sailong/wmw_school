<?php
/**
* Author:
* Email:@qq.com
* Date: 2011-5-30
* http://hi.baidu.com/
*/
define('GUY','true');
require '../common.inc.php';
if(!isset($_COOKIE['username'])){
	echo'<script type="text/javascript"> alert("非法登录!");top.location.href="login.php"; </script>';	
	exit;
}

$action = $_GET['action'];
$action = $action && in_array($action , array('layout')) ? $action : 'layout';

if(!empty($_POST['layout_submit'])) {
    //获取页面提交的参数列表
    $renamearr = $_POST['renamearr'];
    $display_ids = $_POST['display_ids'];
    //检测重名的分类
    if(!empty($renamearr)) {
        //去掉没有提交
        foreach($renamearr as $key=>$val) {
            if(empty($val)) {
                unset($renamearr[$key]);
            } else {
                $renamearr[$key] = trim(htmlspecialchars($val));
            }
        }
        if(!empty($renamearr)) {
            foreach($renamearr as $id=>$class) {
                mysql_query("update " . table('class') . " set class='$class' where id='$id' limit 1");
            }
        }
    }
    //保存首页显示的分类模块
    if(!empty($display_ids)) {
        $display_ids = array_unique($display_ids);
        sort($display_ids , SORT_NUMERIC);
        $displayorders = implode("," , $display_ids);
        mysql_query("replace into " . table('settings') . " set `skey`='layoutorders',`svalue`='$displayorders'");
    }
    echoalert('操作成功!');
}

//获取当前设置的布局数据
$query_settings = mysql_query("select * from " . table('settings') . " where skey='layoutorders' limit 1");
$layoutorders = mysql_fetch_array($query_settings, MYSQL_ASSOC);
$checkids_arr = !empty($layoutorders['svalue']) ? explode(',' , $layoutorders['svalue']) : false;

//显示所有的分类列表
$query = mysql_query("select * from " . table('class') ." where typeid>='2' order by id asc");
$classlist = array();
while($class = mysql_fetch_array($query , MYSQL_ASSOC)) {
    if(!empty($checkids_arr) && in_array($class['id'] , $checkids_arr)) {
        $class['checked'] = true;
    }
    $classlist[$class['id']] = $class;
}
//固定设置的分类id值
$staticids = array_values($_G['static_ids']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/addsystem.css" />
<title>首页布局</title>
<style type="text/css">
	.list {margin-left:20px; border:1px solid #373737; width:100%; overflow:hidden; background:#DDD; padding:20px;}
		.list table th,.list table td {padding:5px; text-align:left; font-size:12px;}
</style>
<script src="js/jquery.js" language="javascript"></script>
<script type="text/javascript">
	function checkbox_max(){
		$num = 0;
		var checkboxes = document.getElementsByName("display_ids[]");
		for(var $i=0;$i<checkboxes.length;$i++){
			if(checkboxes[$i].checked==true){
				$num++;
				if($num>5){
					alert("前台显示的自定义标题最多五个！");
					checkboxes[$i].checked=false;
					break;
				}
			}
		}
		var btn = document.getElementById("btnsub");
		if($num<1){
			alert("请至少选中一个");
			btn.disabled=true;
			btn.readOnly=true;
		}else{
			btn.disabled=false;
			btn.readOnly=false;
		}
		
	}
</script>

</head>
    <body>
    	<form id="form1" name="form1" method="post" action="?action=layout">
        	<div class="list">
        	  <table width="950px" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <th width="45">ID</th>
                  <th width="320">名称</th>
                  <th width="446">重命名</th>
                  <th width="135">首页显示</th>
                </tr>
                <?php 
                    foreach($classlist as $class) {
                ?>
                <tr>
                  <td><?php echo $class['id'];?></td>
                  <td><?php echo $class['class'];?></td>
                  <td>重命名为:<input type="text" name="renamearr[<?php echo $class['id'];?>]" size="50"/></td>
                  <td>
                  <?php if(in_array($class['id'] , $staticids)) {?>
                  	系统默认(支持重命名)
                  <?php } else {?>
                  	<input type="checkbox" <?php if($class['checked']) {?>checked="checked"<?php }?> onclick="checkbox_max();" name="display_ids[]" value="<?php echo $class['id'];?>"/>
                  <?php }?>
                  </td>
                </tr>
                <?php }?>
                <tr>
                	<td colspan="4">
                		<div style="width:200px; margin:0 auto;">
                			<input type="submit" value="提交" id="btnsub" name="layout_submit"/>
                			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                			<input type="reset" value="重置"/>
                		</div>
                	</td>
                </tr>
              </table>
            </div>
		</form>
    </body>
</html>
