<?php
require('inc/set.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员中心 - 管理跟帖评论 - <?php echo $web['sitename']; ?><?php echo $web['code_author']; ?></title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $web['cssfile']; ?>/style.css">
<script language="javascript" type="text/javascript">
<!--
function allChoose(v1,v2){
  var a=document.getElementsByName("id[]");
    for(var i=0;i<a.length;i++){ if(a[i].checked==false) a[i].checked=v1; else a[i].checked=v2;
  }
}
-->
</script>
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
  <div class="area_title"><img src="css/<?php echo $web['cssfile']; ?>/area_title.gif" align="absmiddle" /> <a href="forum.php">课后讨论首页</a> &gt; <a href="admin.php">管理员中心</a></div>
</div>



<table class="maintable" height="400" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="left_menu" valign="top">
<?php
require('inc/set_sql.php');
require('inc/set_area.php');
require('inc/function/confirm_manager.php');
require('inc/function/user_class.php');
require('inc/function/getarea.php');

if(confirm_manager()){
  $manage='&manage=yes';
  require('inc/menu/admin_left_menu.txt');
  $yes='管理跟帖评论';
}

?>
    </td>
    <td width="100" valign="middle" align="right" class="pass"> 》</td>
    <td class="m_r" valign="top"><h4><?php echo $yes; ?>&nbsp;</h4>


<?php
if(isset($yes)){
  $you=explode('_',$cookie[1]);
  echo '欢迎：'.$cookie[0].' '.user_class(abs($cookie[1])).'<br />
<a href="user.php">用户中心</a> | <a href="run.php?run=user_login&act=logout">退出</a> | 您上次访问是'.$you[1].'<br />
<br />
';

?>
<form action="run.php?run=admin_del&dataname=bbsreply" method="post" name="manageform" id="manageform">
<div class="re_author">信息记录</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr>
    <th width="20" align="center">&nbsp;</th>
    <th width="*" align="center">评论内容</th>
    <th width="110" align="center">评论作者</th>
    <th width="150" align="center">评论时间</th>
    <th width="55" align="center">主文ID</th>
  </tr>
</table>

<?php


  require('inc/function/get_page.php');
  if($db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
    if(@mysql_select_db($sql['name'],$db)){
      mysql_query('SET NAMES '.$sql['char'].'');
	  if($result=mysql_query('SELECT * FROM bbsreply ORDER BY id DESC',$db)){ //结果集
        $n=mysql_num_rows($result); //总记录数
        $p=get_page($n); //页数
        $text='';
        $seek=$n-$web['pagesize']*($p-1);
        $end=$seek-$web['pagesize']>0?$seek-$web['pagesize']:0;
	  $step=0;
        for($i=$seek-1;$i>=$end;$i--){
          if(mysql_data_seek($result,$i)){//<a href="run.php?run=admin_del&id='.$row['id'].'&dataname=bbsreply">×</a>
		    $step++;
            $row=mysql_fetch_assoc($result);
            $text.='
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr class="step'.($step%2).'">
    <td width="20" align="center"><input name="id[]" id="id[]" class="" type="checkbox" value="'.$row['id'].'" /></td>
    <td width="*"><a href="article.php?id='.$row['r_id'].'#reply_'.$row['id'].'" target="_blank">'.$row['text'].'</a></td>
    <td width="120" align="center">'.$row['author_ip'].'</td>
    <td width="150" align="center">'.$row['date'].'</td>
    <td width="55" align="center"><a href="article.php?id='.$row['r_id'].'" target="_blank">'.$row['r_id'].'</a></td>
  </tr>
</table>';
          }
        }
	    mysql_free_result($result);
      }
    }
    mysql_close();
  }

  if(!empty($text)){
    echo $text;
    echo get_page_foot($p,$n,'');
	echo '  <a href="void(0)" onclick="javascript:allChoose(true,true);return false;">全选</a>-
  <a href="void(0)" onclick="javascript:allChoose(true,false);return false;">反选</a>-
  <a href="void(0)" onclick="javascript:allChoose(false,false);return false;">不选</a>
<input type="submit" name="act" value="删除" />';
  }else{
    echo '<img src="images/i.gif" /> 评论数据为空或数据库连接未成功！';
  }
?>
    </form>


<?php

}else{
  echo '<img src="images/i.gif" /> 请以基本管理员'.$web['manager'].'<a href="user_login.php?'.basename($_SERVER['REQUEST_URI']).'"><b>登录</b></a>，以获得管理权限';
}

?>

    </td>
  </tr>
</table>

</div>
</div>
</body>
</html>
