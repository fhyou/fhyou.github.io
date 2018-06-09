<?php
require('inc/set.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户中心 - 修改已发信息 - <?php echo $web['sitename']; ?><?php echo $web['code_author']; ?></title>
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

require('inc/set_sql.php');
require('inc/set_area.php');
require('inc/function/confirm_login.php');
require('inc/function/user_class.php');

function getclass($area_id){
  global $web;
  if(is_array($web['area'][$area_id]) && count($web['area'][$area_id])>1){
    $text.='<font color=#FF5500>细分栏目：</font>';
    foreach((array)$web['area'][$area_id] as $i=>$class){
      if($i!=0){
        $text.='<a href="?area_id='.$area_id.'_'.$i.'&id='.$_REQUEST['id'].'" class="class">'.$class.'</a> ';
	  }
    }
    $text.='<br />';
  }
  return $text;
}

function getarea(){
  global $web;
  foreach((array)$web['area'] as $i=>$area){
    $text.='<h6><a href="?area_id='.$i.'&id='.$_REQUEST['id'].'">'.$area[0].'</a></h6>　';
    foreach((array)$area as $j=>$class){
      if($j!=0){
        $text.='<a href="?area_id='.$i.'_'.$j.'&id='.$_REQUEST['id'].'" class="class">'.$class.'</a> ';
      }
    }
    $text.='<br />';
  }
  return $text;
}


if($_REQUEST['area_id']){
  if(preg_match('/^\d+\_\d+$/',$_REQUEST['area_id'])){
    list($area_id,$class_id)=@explode('_',$_REQUEST['area_id']);
	if($web['area'][$area_id][$class_id]==NULL){
	  echo '<h6>所有栏目</h6><br />';
	  echo getarea();
    }else{
      echo $type='<h6><a href="?&id='.$_REQUEST['id'].'">所有栏目</a></h6> &gt; <h6><a href="?area_id='.$area_id.'&id='.$_REQUEST['id'].'">'.$web['area'][$area_id][0].'</a></h6> &gt; '.$web['area'][$area_id][$class_id];
	  $yes='现在可以发表了';
    }
  }elseif(is_numeric($_REQUEST['area_id'])){
	if($web['area'][$_REQUEST['area_id']]==NULL){
	  echo '<h6>所有栏目</h6><br />';
	  echo getarea();
    }else{
	  echo '<h6><a href="?&id='.$_REQUEST['id'].'">所有栏目</a></h6> &gt; <h6><a href="?area_id='.$_REQUEST['area_id'].'&id='.$_REQUEST['id'].'">'.$web['area'][$_REQUEST['area_id']][0].'</a></h6><br />';
      echo getclass($_REQUEST['area_id']);
	}
  }else{
    echo getarea();
  }
}else{
  echo '<h6>所有栏目</a></h6><br />';
  echo getarea();
}


?>
    </td>
    <td width="100" valign="middle" align="right" class="pass"> 》</td>
    <td class="m_r" valign="top"><h4><?php echo $yes?$yes:'修改已发信息 &gt; 重新选择类目'; ?>&nbsp;</h4>


<?php
if(confirm_login()){
  $you=explode('_',$cookie[1]);
  echo '欢迎：'.$cookie[0].' '.user_class(abs($cookie[1])).'<br />
      <a href="user.php">用户中心</a> | <a href="run.php?run=user_login&act=logout">退出</a> | 您上次访问是'.$you[1].'';


  $_REQUEST['id']=array_unique((array)$_REQUEST['id']);
  $_REQUEST['id']=$_REQUEST['id'][0];
  if($_REQUEST['id'] && is_numeric($_REQUEST['id'])){

    //连接mysqkl数据库
    if($db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
      //选择数据库并判断
      if(@mysql_select_db($sql['name'],$db)){
        mysql_query('SET NAMES '.$sql['char'].'');
        if($result=mysql_query("SELECT * FROM bbslistdata WHERE id='".$_REQUEST["id"]."'",$db)){ //结果集
          //mysql_data_seek($result,$_REQUEST['id']);
          $row=mysql_fetch_assoc($result);
          mysql_free_result($result);
		  if($row['author_ip']!=$cookie[0]){
            $err.='<br /><img src="images/i.gif" /> 经查你与该文作者不符！';
		  }

		}else{
          $err.='<br /><img src="images/i.gif" /> 数据库查无此记录！';
		}
	  }else{
        $err.='<br /><img src="images/i.gif" /> 数据库['.$sql['name'].']连接不成功！';
      }
      mysql_close();
	}else{
      $err.='<br /><img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！';
    }
  }else{
    $err.='<br /><img src="images/i.gif" /> 文章参数出错！问题分析：1、您可能未选择文章；2、参数传递出错';
  }
  if(!isset($err) && isset($yes)){
?>

<?php

?>
<iframe id="lastFrame" name="lastFrame" frameborder="0" style="display:none"></iframe>
<script language="javascript" type="text/javascript">
<!--
subjT='<?php echo trim($row['title']); ?>';
contT='<?php echo $row['text']; ?>';
var liMaxCount=<?php echo (is_numeric($web['list_wordcount']) && $web['list_wordcount']>0)?$web['list_wordcount']:50000; ?>;
var formU="run.php?run=user_edit&id=<?php echo $_REQUEST['id']; ?>";
var formF='\
<br /><br /><b><u><span style="color:#FF6600">*</span> 类目：</u></b><br /><br />\
<?php echo $type; ?> <a href="user_list_edit2.php?id=<?php echo $_REQUEST['id']; ?>"> [ 改变类目 ]</a>\
<input type="hidden" name="area_id" value="<?php echo $_REQUEST['area_id']; ?>" />';
document.write('<'+'sc'+'ript language="javascript" src="js/editor.js" type="text/javascript"></'+'sc'+'ript>');
-->
</script>

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
