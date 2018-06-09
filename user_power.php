<?php
require('inc/set.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户中心 - 了解我的权限  - <?php echo $web['sitename']; ?><?php echo $web['code_author']; ?></title>
<meta name="Description" content="<?php echo $web['description']; ?>" />
<meta name="keywords" content="<?php echo $web['keywords']; ?>" />
<link rel="stylesheet" type="text/css" href="css/<?php echo $web['cssfile']; ?>/style.css">
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
require('inc/set_sql.php');
require('inc/function/confirm_login.php');
require('inc/function/user_class.php');
require('inc/function/get_date.php');
if(confirm_login()){
  require('inc/menu/user_left_menu.txt');

  $yes='了解我的权限';
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

<div class="re_author">系统参数说明</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="150">上传权限起始分</td>
    <td><strong><?php echo $web['up_start']; ?></strong>&nbsp;</td>
  </tr>
  <tr>
    <td width="150">发布链接起始分</td>
    <td><strong><?php echo $web['link_start']; ?></strong>&nbsp;</td>
  </tr>
  <tr>
    <td width="150">上传图片限定尺寸</td>
    <td><strong><?php echo $web['max_file_size'][15]; ?></strong> KB</td>
  </tr>
  <tr>
    <td width="150">上传动画限定尺寸</td>
    <td><strong><?php echo $web['max_file_size'][16]; ?></strong> KB</td>
  </tr>
  <tr>
    <td width="150">上传影音文件限定尺寸</td>
    <td><strong><?php echo $web['max_file_size'][17]; ?></strong> KB</td>
  </tr>
  <tr>
    <td width="150">上传其它文件限定尺寸</td>
    <td><strong><?php echo $web['max_file_size'][18]; ?></strong> KB</td>
  </tr>
  <tr>
    <td width="150">每日上传数量</td>
    <td>=等级分÷20</td>
  </tr>
</table>

<div class="re_author">我的档案权限</div>

<?php
  if($db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
    if(@mysql_select_db($sql['name'],$db)){
      mysql_query('SET NAMES '.$sql['char'].'');
      if($result=mysql_query("SELECT * FROM bbsmember WHERE username='".$cookie[0]."'",$db)){ //结果集
	    $row=mysql_fetch_assoc($result);
        mysql_free_result($result);
	  }else{
	    $err.='<br /><img src="images/i.gif" /> 出错！数据库中查无此用户！';
	  }
    }else{
      $err.='<br /><img src="images/i.gif" /> 数据库['.$sql['name'].']连接不成功！';
    }
    mysql_close();
  }else{
    $err.='<br /><img src="images/i.gif" /> 数据库['.$sql['name'].']连接不成功！';
  }
  if(!isset($err)){
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="150">我的积分</td>
    <td><strong><?php echo $row['point']; ?></strong>&nbsp;</td>
  </tr>
  <tr>
    <td width="150">发布权限</td>
    <td><strong><?php echo ($row['power']!='manager' && (strstr($row['power'],'a') || strstr($row['power'],'t')))?'×（原因：被关闭）':'√'; ?></strong>&nbsp;</td>
  </tr>
  <tr>
    <td width="150">评论权限</td>
    <td><strong><?php echo ($row['power']!='manager' && (strstr($row['power'],'a') || strstr($row['power'],'r')))?'×（原因：被关闭）':'√'; ?></strong>&nbsp;</td>
  </tr>
  <tr>
    <td width="150">上传权限</td>
    <td><strong><?php
	 if($row['power']!='manager' && (strstr($row['power'],'a') || strstr($row['power'],'u'))){
	   echo '×（原因：被关闭）';
	 }else{
	   if($row['point']>=$web['up_start']){
	     echo '√'; 
	   }else{
	     echo '×（原因：积分未到）';
	   }
	 }
	 ?></strong>&nbsp;</td>
  </tr>
  <tr>
    <td width="150">每日上传数量</td>
    <td><strong><?php echo ceil($row['point']/20); ?></strong> 如果有发布、上传权限&nbsp;</td>
  </tr>
  <tr>
    <td width="150">发布链接</td>
    <td><strong><?php echo ($row['point']<$web['link_start'])?'×':'√'; ?></strong> 如果有发布权限&nbsp;</td>
  </tr>
  <tr>
    <td width="150">置顶权限</td>
    <td><strong><?php echo abs($row['topcount']); ?></strong>条&nbsp;</td>
  </tr>
  <tr>
    <td width="150">置顶限期</td>
    <td><strong><?php
	    $nowdate=gmdate('YmdHis',time()+(floatval($web['time_pos'])*3600));
 echo abs($row['topdate'])>$nowdate?get_date($row['topdate']):'（无效）'; ?></strong>&nbsp;</td>
  </tr>
</table>




<?php
  }else{
    echo $err!=''?'<br /><img src="images/i.gif" /> 发现错误信息：'.$err:'';
  }



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
