<?php
require('inc/set.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户中心 - 发送短信 - <?php echo $web['sitename']; ?><?php echo $web['code_author']; ?></title>
<meta name="Description" content="<?php echo $web['description']; ?>" />
<meta name="keywords" content="<?php echo $web['keywords']; ?>" />
<link rel="stylesheet" type="text/css" href="css/<?php echo $web['cssfile']; ?>/style.css">
<link rel="stylesheet" type="text/css" href="css/editor.css">
<script language="javascript" type="text/javascript" src="js/main.js"></script>
</head>

<body>
<?php include("menubody.php"); ?>
	
	<div class="rig_lm01">
		<div class="title">
			<img src="../images/listicon.jpg" class="icon" style="padding-top: 3px;">
				<h2>课后讨论</h2>
		
		</div>
	</div>


<div class="area">
  <div class="area_title"><img src="css/<?php echo $web['cssfile']; ?>/area_title.gif" align="absmiddle" /> <a href="forum.php">课后讨论首页</a> &gt; <a href="user.php">用户中心</a></div>
</div>



<table class="maintable" height="400" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="left_menu" valign="top">

        <?php
require('inc/function/confirm_login.php');
require('inc/function/user_class.php');
require('inc/function/get_date.php');
if(confirm_login()){
  require('inc/menu/user_left_menu.txt');

  $yes='发送短信';
}

?>
    </td>
    <td width="100" valign="middle" align="right" class="pass"> 》</td>
    <td class="m_r" valign="top"><h4><?php echo $yes; ?>&nbsp;</h4>


<?php
if(isset($yes)){
  $you=explode('_',$cookie[1]);
  echo '欢迎：'.$cookie[0].' '.user_class(abs($cookie[1])).'<br />
      <a href="user.php">用户中心</a> | <a href="run.php?run=user_login&act=logout">退出</a> | 您上次访问是'.$you[1].'';
?>
<?php

$_REQUEST['user']=array_unique((array)$_REQUEST['user']);

if(count($_REQUEST['user'])>0){
  $userarr=@implode('/',$_REQUEST['user']);
}else{
  $userarr=$_REQUEST['username'];
}


?>






<br />
<br />
<iframe id="lastFrame" name="lastFrame" frameborder="0" style="display:none"></iframe>
<script language="javascript" type="text/javascript">
<!--
var liMaxCount=<?php echo $web['mess_wordcount']; ?>;
var formU="run.php?run=user_mess";
var formF='<br /><br /><b><u><span style="color:#FF6600">*</span> 收信人：</u></b><br /><br /><input type="text" name="username" value="<?php echo $userarr; ?>" size="50" /> <a href="member.php" target="_blank">查看用户列表</a>';
document.write('<'+'sc'+'ript language="javascript" src="js/editor.js" type="text/javascript"></'+'sc'+'ript>');
-->
</script>

<?php
}else{
  echo '欢迎你：匿名用户<br /><a href="user_reg.php?'.basename($_SERVER['REQUEST_URI']).'"><b>先去创建帐号（非常简单）</b></a>或<a href="user_login.php?'.basename($_SERVER['REQUEST_URI']).'"><b>登录帐号</b></a>，以获得更多发表或管理权限';
}

?>
    </td>
  </tr>
</table>

</div>
</div>

</body>
</html>
