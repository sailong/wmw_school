<?php
/**
* Author:
* Email:@qq.com
* Date: 2011-6-1
* http://hi.baidu.com/
*/
define('GUY','true');
require '../common.inc.php';
if(!isset($_COOKIE['username'])){
	echo'<script type="text/javascript"> alert("非法登录!");top.location.href="login.php"; </script>';	
	exit;
}

$action = trim($_GET['action']);
$action = $action && in_array($action , array('add' , 'modify')) ? $action : "";

if($action == 'add'){
	if(empty($_POST['admin']) || empty($_POST['password'])){
		echoalerthistory('账号/密码不得为空!');
		exit;
	}
	if(checkAdmin($_POST['admin'])) {
	    echoalert('该用户名已经存在!');
	    echo '<script type="text/javascript">window.open("addadmin.php" , "main");</script>';
	    exit;
	}
	$_html = array();
	$_html['admin'] = trim($_POST['admin']);
	$_html['password'] = md5(trim($_POST['password']));	
	
	mysql_query("insert into " . table('admin') . "(admin,password) values('{$_html['admin']}','{$_html['password']}')");	
	echoalerthistory('添加成功!');
} elseif($action == 'modify'){
	if(empty($_POST['admin']) || empty($_POST['password'])){
		echoalerthistory('账号/密码不得为空!');
		exit;
	}	

	if($_POST['admin'] != $_COOKIE['username']){
		echoalerthistory('你只能修改自己的账号和密码!');
		exit;		
	} elseif(checkAdmin($_POST['admin'])) {
	    echoalerthistory('用户名已经存在!');
		exit;
	}
	$_html = array();
	$_html['admin'] = trim($_POST['admin']);
	$_html['password'] = md5(trim($_POST['password']));	
		
	@mysql_query("update " . table('admin') . " set admin='{$_html['admin']}',password='{$_html['password']}' 
	where admin='{$_COOKIE['username']}' ")or die('修改有错误!');
	echoalerthistory('修改成功!');
}

/**
 * 检测用户是否同名
 * @param $username
 * @return 存在返回true,否则false
 */
function checkAdmin($username) {
    if(empty($username)) {
        return false;
    }
    $admin_user = mysql_fetch_array(mysql_query("select * from " . table('admin') . " where admin='$username' limit 1"));
    return !empty($admin_user) ? true : false;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/addadmin.css"/>
<title>添加/修改管理员</title>
</head>

<body>
<div id="addadmin">
<p>添加管理员</p>
<form method="post" action="?action=add">
<ul>
<li>账号：<input type="text" name="admin" /></li>
<li>密码：<input type="password" name="password" /></li>
<li><input type="submit" value="添加" /></li>
</ul>
</form>
</div>
<div id="modify">
<p>修改管理员</p>
<form method="post" action="?action=modify">
<ul>
<li>账号：<input type="text" name="admin" value="<?php echo $_COOKIE['username']?>" /></li>
<li>密码：<input type="password" name="password" /></li>
<li><input type="submit" value="修改" /></li>
</ul>
</form>
</div>
<?php 
mysql_close();
?>
</body>
</html>
