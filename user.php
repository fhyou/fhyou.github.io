<?php

require('inc/set.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户中心 - <?php echo $web['sitename']; ?><?php echo $web['code_author']; ?></title>
<meta name="Description" content="<?php echo $web['description']; ?>" />
<meta name="keywords" content="<?php echo $web['keywords']; ?>" />
<link rel="stylesheet" type="text/css" href="css/<?php echo $web['cssfile']; ?>/style.css">
</head>

<body>
<?php include("menubody.php"); ?>
	
	<div class="rig_lm01">
		<div class="title">
			<img src="images/listicon.jpg" class="icon" style="padding-top: 3px;">
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
if(confirm_login()){
  require('inc/menu/user_left_menu.txt');

   $yes='用户信息管理';
}

?>
    </td>
    <td width="100" valign="middle" align="right" class="pass"> 》</td>
    <td class="m_r" valign="top"><h4><?php echo $yes; ?>&nbsp;</h4>


<?php
if(isset($yes)){
  echo file_exists('data/upload/'.urlencode($cookie[0]).'.jpg')?'<a href="data/upload/'.urlencode(urlencode($cookie[0])).'.jpg" target="_blank"><img src="data/upload/'.urlencode(urlencode($cookie[0])).'.jpg" width="75" height="100" /></a><br />':'<img src="images/photo.jpg" width="75" height="100" /><br />';
  $you=explode('_',$cookie[1]);
  echo '欢迎：'.$cookie[0].' '.user_class(abs($cookie[1])).'<br />
      <a href="user.php">用户中心</a> | <a href="run.php?run=user_login&act=logout">退出</a> | 您上次访问是'.$you[1].'<br />
	  <<建议完善自己的名片，以便让更多人关注';


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
