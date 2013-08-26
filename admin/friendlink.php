<?php
header('content-type:text/html;charset=utf-8');
if(!defined('GUY')) {
    define('GUY' , true);
}

require '../common.inc.php';

$action = $_GET['action'];
$action = $action && in_array($action, array('add' , 'delete' , 'update')) ? $action : 'add';

if($action == 'add' && $_POST['submit_add']) {
    $name = trim(htmlspecialchars($_POST['name']));
    $link = trim($_POST['link']);
    $displayorder = intval($_POST['displayorder']);
    //保证链接的合法性
    $link = checkLink($link);
    
    $flag = true;
    if(empty($name)) {
        echoalert('友情链接名字不能为空!');
        echo '<script type="text/javascript">window.open("friendlink.php" , "main"); </script>';
	    exit; 
        $flag = false;
    } elseif(empty($link)) {
        echoalert('链接地址不能为空!');
        echo '<script type="text/javascript">window.open("friendlink.php" , "main"); </script>';
	    exit; 
        $flag = false;
    }
    if($flag) {
        $friendlinks = getFriendlinks();
        $svalue = array();
        if(!empty($friendlinks['svalue'])) {
            $svalue = $friendlinks['svalue'];
        }
        $svalue[] = array('name' => $name , 'link' => $link , 'displayorder' => $displayorder);
        
        if(count($svalue) > 100) {
            echoalert('友情链接最多100个!');
            echo '<script type="text/javascript">window.open("friendlink.php" , "main"); </script>';
	        exit; 
        } else {
            $svalue = orderFriendlink($svalue);
            $svalue = !empty($svalue) ? @serialize($svalue) : "";
            //更新记录
            mysql_query("replace into " . table('settings') . " set skey='friendlinks' , svalue='$svalue'");
            echoalert('添加成功!');
            echo '<script type="text/javascript">window.open("friendlink.php" , "main"); </script>';
	        exit; 
        }
    }
} elseif($action == 'delete' && $_POST['submit_delete']) {
    $id = intval($_POST['id']);
    $md5 = trim($_POST['md5']);
    $md5_key = substr(time() , 0 , 7) . $id;
    
    $flag = true;
    if($md5 !== md5($md5_key)) {
        echoalert('非法操作!');
        echo '<script type="text/javascript">window.open("friendlink.php" , "main"); </script>';
	    exit; 
        $flag = false;
    }
    if($flag) {
        $friendlinks = getFriendlinks();
        $svalue = $friendlinks['svalue'];
        if(!empty($svalue)) {
            if(isset($svalue[$id])) {
                unset($svalue[$id]);
            }
        }
        $svalue = !empty($svalue) ? @serialize($svalue) : "";
        //更新记录
        mysql_query("replace into " . table('settings') ." set skey='friendlinks' , svalue='$svalue'");
        echoalert('删除成功!');
        echo '<script type="text/javascript">window.open("friendlink.php" , "main"); </script>';
	    exit; 
    }
} elseif($action == 'update' && $_POST['submit_update']) {
    $id = intval($_POST['id']);
    $md5 = trim($_POST['md5']);
    
    $name = trim(htmlspecialchars($_POST['name']));
    $link = trim($_POST['link']);
    $displayorder = intval($_POST['displayorder']);
    $md5_key = substr(time() , 0 , 7) . $id;
    
    //链接检测
    $link = checkLink($link);
    
    $flag = true;
    if($md5 !== md5($md5_key)) {
        echoalert('非法操作!');
        echo '<script type="text/javascript">window.open("friendlink.php" , "main"); </script>';
	    exit; 
        $flag = false;
    } elseif(empty($name)) {
        echoalert('友情链接名字不能为空!');
        echo '<script type="text/javascript">window.open("friendlink.php" , "main"); </script>';
	    exit; 
        $flag = false;
    } elseif(empty($link)) {
        echoalert('链接地址不能为空!');
        echo '<script type="text/javascript">window.open("friendlink.php" , "main"); </script>';
	    exit; 
        $flag = false;
    }
    if($flag) {
        $friendlinks = getFriendlinks();
        $svalue = $friendlinks['svalue'];
        if(isset($svalue[$id])) {
            $svalue[$id] = array('name' => $name , 'link' => $link , 'displayorder' => $displayorder);
            $svalue = orderFriendlink($svalue);
        }
        $svalue = !empty($svalue) ? @serialize($svalue) : "";
        //更新记录
        mysql_query("replace into " . table('settings') . " set skey='friendlinks' , svalue='$svalue'");
        
        echoalert('更新成功!');
        echo '<script type="text/javascript">window.open("friendlink.php" , "main"); </script>';
	    exit; 
    }
}
//获取当前要显示的友情链接
$friendlinks_result = getFriendlinks();
$current_links = & $friendlinks_result['svalue'];
//追加MD5值
if(!empty($current_links)) {
    foreach($current_links as $key=>& $val) {
        $val['md5'] = md5(substr(time() , 0 , 7) . $key);
    }
}

/**
 * 对友情链接按照优先顺序进行排序
 * @param $links
 */
function orderFriendlink($links = array()) {
    if(empty($links) || !is_array($links)) {
        return false;
    }
    $displayorder = array();
    foreach($links as $key=>$value) {
        $displayorder[$key] = $value['displayorder'];
    }
    array_multisort($displayorder , SORT_DESC , $links);
    
    return !empty($links) ? $links : false;
}
//检测链接
function checkLink($link) {
    if(empty($link)) {
        return false;
    }
    $link = str_replace(" " , "" , $link);
    $pattern = "/[a-zA-Z]+:\/\/[^\s]*/";
    if(preg_match($pattern , $link , $matches)) {
        return $matches[0];
    } else {
        return "http://" . $link;
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>友情链接管理</title>
<link rel="stylesheet" href="css/friendlinks.css" type="text/css" />
<style>
 body{font-size:12px;}
</style>
<script language="javascript" type="text/javascript">

function showDiv(){
    document.getElementById('popDiv').style.display='block';
    document.getElementById('popIframe').style.display='block';
    document.getElementById('bg').style.display='block';
}
function closeDiv(){
    document.getElementById('popDiv').style.display='none';
    document.getElementById('bg').style.display='none';
    document.getElementById('popIframe').style.display='none';
}

function openDiv(){
    document.getElementById('popDiv1').style.display='block';
    document.getElementById('popIframe1').style.display='block';
    document.getElementById('bg1').style.display='block';
}
function guanDiv(){
    document.getElementById('popDiv1').style.display='none';
    document.getElementById('bg1').style.display='none';
    document.getElementById('popIframe1').style.display='none';
}

function queDiv(){
	document.getElementById('popDiv2').style.display='block';
	document.getElementById('popIframe2').style.display='block';
	document.getElementById('bg2').style.display='block';
}
function quDiv(){
    document.getElementById('popDiv2').style.display='none';
    document.getElementById('bg2').style.display='none';
    document.getElementById('popIframe2').style.display='none';
}
</script>
</head>

<body>
<!-- 增加友情链接 -->
<div id="popDiv" class="mydiv" style="display:none;">
     <form action="?action=add" method="post" id="formadd">
       <input type="hidden" name="submit_add" value="true"/>
       <p>名称：<input type="text" name="name"/></p>
       <p>链接：<input type="text" name="link"/></p>
       <p>显示顺序：<input type="text" name="displayorder"/></p>
       <p>
          <a href="javascript:;" onclick="javascript:closeDiv();document.getElementById('formadd').submit();return false;" class="change">确定</a>
          <a href="javascript:closeDiv();" class="delet">取消</a>
       </p>
     </form>
</div>
<div id="bg" class="bg" style="display:none;"></div>
<iframe id='popIframe' class='popIframe' frameborder='0' ></iframe>

<!-- 更新友情链接 -->
<div id="popDiv2" class="mydiv" style="display:none;">
     <form action="?action=update" method="post" id="submitupdate">
       <input type="hidden" name="submit_update" value="true"/>
       <input type="hidden" name="id" id="up_id"/>
       <input type="hidden" name="md5" id="up_md5"/>
       <p>名称：<input type="text" name="name" id="up_name"/></p>
       <p>链接：<input type="text" name="link" id="up_link"/></p>
       <p>显示顺序：<input type="text" name="displayorder" id="up_displayorder"/></p>
       <p>
         <a href="javascript:;" onclick="javascript:quDiv(); document.getElementById('submitupdate').submit(); return false;" class="change">确定</a>
         <a href="javascript:quDiv()" class="delet">取消</a>
       </p>
     </form>
</div>
<div id="bg2" class="bg" style="display:none;"></div>
<iframe id='popIframe2' class='popIframe' frameborder='0' ></iframe>

<!-- 删除友情链接 -->
<div id="popDiv1" class="mydiv" style="display:none;">
  <form action="?action=delete" method="post" id="submitdelete">
  	<input type="hidden" name="submit_delete" value="true"/>
  	<input type="hidden" name="id" id="delete_id"/>
  	<input type="hidden" name="md5" id="delete_md5"/>
  	<p>确定删除</p>
  	<p>
  	  <a href="javascript:;" onclick="javascript:guanDiv();document.getElementById('submitdelete').submit(); return false;" class="change">确定</a>
  	  <a href="javascript:guanDiv()" class="delet">取消</a>
  	</p>
  </form>
</div>
<div id="bg1" class="bg" style="display:none;"></div>
<iframe id='popIframe1' class='popIframe' frameborder='0' ></iframe>
<div class="big">

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#0099CC" class="title">
  <tr>
    <td height="30">友情链接管理</td>
  </tr>
</table>

<div style="overflow-x:auto;overflow-y:auto;">
    <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#d1d1d1">
      <tr>
        <td width="260" height="30" align="center" bgcolor="#d1d1d1">名称</td>
        <td width="360" align="center" bgcolor="#d1d1d1">链接</td>
        <td width="100" align="center" bgcolor="#d1d1d1">显示顺序</td>
        <td align="center" bgcolor="#d1d1d1">操作</td>
      </tr>
      <?php if(!empty($current_links)) {
                foreach($current_links as $id=>$link) {
      ?>
      <tr>
        <td height="30" align="center" bgcolor="#FFFFFF"><?php echo $link['name'];?></td>
        <td align="center" bgcolor="#FFFFFF"><a style="" href="<?php echo $link['link'];?>" target="_blank"><?php echo $link['link'];?></a></td>
        <td align="center" bgcolor="#FFFFFF"><?php echo $link['displayorder'];?></td>
        <td align="center" bgcolor="#FFFFFF">
        	<a href="javascript:;" 
        	   onclick="javascript:openDiv();
        	   document.getElementById('delete_id').value=<?php echo $id;?>;
        	   document.getElementById('delete_md5').value='<?php echo $link['md5'];?>';
        	   return false;" class="delet">删除</a>
        	<a href="javascript:;" 
        	   onclick="javascript:queDiv();
        	   document.getElementById('up_id').value='<?php echo $id;?>'; 
        	   document.getElementById('up_md5').value='<?php echo $link['md5'];?>'; 
        	   document.getElementById('up_name').value='<?php echo $link['name'];?>';
        	   document.getElementById('up_link').value='<?php echo $link['link'];?>';
        	   document.getElementById('up_displayorder').value='<?php echo $link['displayorder'];?>';
        	   return false;" class="change" style="margin-left:10px;">更新</a>
        </td>
      </tr>
      <?php }?>
      <?php } else {?>
      	<tr>
          	<td colspan="4">
          		<p style="color:red; padding:20px;">暂时没有友情链接!</p>
          	</td>
      	</tr>
      <?php }?>
    </table>
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="add_link">
  <tr>
    <td height="40"><p><a href="javascript:showDiv()" style="width:110px;">增加友情链接</a></p></td>
  </tr>
</table>
</div>
</body>
</html>