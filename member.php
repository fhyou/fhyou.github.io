<?php
require('inc/set.php');
require('inc/set_sql.php');
require('inc/set_area.php');
require('inc/function/confirm_login.php');
require('inc/function/user_class.php');
if(confirm_login()){
  $you=explode('_',$cookie[1]);
  $writepower='发表帖子';
}else{
  $writepower='匿名发表';
}
//身份图像
function users_class($point,$power){
  global $web;
  if($power=='manager'){
    $image='<img src="images/manager.gif" align="texttop" alt="管理员">';
  }else{
    if($point>=0 && $point<=abs($web['class_iron'])){
        $image='<img src="images/iron_l.gif"><img src="images/iron.gif" width="1.5" height="10" alt="积分'.$point.'-铁级用户"><img src="images/iron_r.gif">';
    }elseif($point>abs($web['class_iron']) && $point<=abs($web['class_silver'])){
       $image='<img src="images/silver_l.gif"><img src="images/silver.gif" width="1.5" height="10" alt="积分'.$point.'-银级用户"><img src="images/silver_r.gif">';
    }elseif($point>abs($web['class_slive']) && $point<=abs($web['class_gold'])){
       $image='<img src="images/gold_l.gif"><img src="images/gold.gif" width="1.5" height="10" alt="积分'.$point.'-金级用户"><img src="images/gold_r.gif">';
    }else{
       $image='<img src="images/diamond_'.(ceil($point/abs($web['class_gold']))-1).'.gif" align="texttop" alt="积分'.$point.'-'.(ceil($point/abs($web['class_gold']))-1).'钻级用户">';
    }
  }
  return $image;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户列表 - <?php echo $web['sitename']; ?><?php echo $web['code_author']; ?></title>
<meta name="Description" content="<?php echo $web['description']; ?>" />
<meta name="keywords" content="<?php echo $web['keywords']; ?>" />
<link rel="stylesheet" type="text/css" href="css/<?php echo $web['cssfile']; ?>/style.css">
<script language="javascript" src="js/main.js" type="text/javascript"></script>

</head>
<body>
<?php include("menubody.php"); ?>
	
	<div class="rig_lm01">
		<div class="title">
			<img src="../images/listicon.jpg" class="icon" style="padding-top: 3px;">
				<h2>课后讨论</h2>
		
		</div>
	</div>

<iframe id="myfrime" name="myfrime" style="display:none"></iframe>
<div class="area">
  <div class="area_title"><img src="css/<?php echo $web['cssfile']; ?>/area_title.gif" align="absmiddle" /> <a href="forum.php">课后讨论首页</a> &gt; 用户列表</div>
  <?php
  require('inc/function/getarea.php');
  echo '<div class="area_content">'.getarea().'</div>';
?>
</div>

<div id="body">
  <div id="area">

    <div class="area_title"><a href="user_write.php?area_id=<?php echo $_REQUEST['area_id']; ?>" class="send"><?php echo $writepower; ?></a>
      <img src="css/<?php echo $web['cssfile']; ?>/area_title.gif" align="absmiddle" /> <?php echo '<a href="./forum.php">讨论广场</a> · <a href="./forum.php?list_type=ess">精华列表</a>'; ?> · <a href="member.php" style="color:#FF0000">用户列表</a></div>
    <div id="list_out">
      <table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <th style="padding-left:7px;">用户名</th>
          <th width="80">积分</th>
          <th width="60">发表数量</th>
          <th width="130" style="font-size:12px">注册时间</th>
          <th width="130" style="font-size:12px">最后访问</th>
          <th width="80">QQ</th>
          <th width="30">详细</th>
          <th width="30" style="padding-right:7px;">发信</th>
        </tr>
      </table>
      <?php
require('inc/function/get_page.php');

  //连接mysqkl数据库
  if($db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
    //选择数据库并判断
    if(@mysql_select_db($sql['name'],$db)){
      mysql_query('SET NAMES '.$sql['char'].'');
	  if($_REQUEST['username']){
	    $result=mysql_query('SELECT * FROM bbsmember WHERE username="'.$_REQUEST['username'].'"',$db); //结果集
	    if($row=mysql_fetch_assoc($result)){
		  $text='
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="padding-left:7px;"><a href="user_card.php?username='.urlencode($row['username']).'" target="_blank" title="点击查看发布人名片">'.$row['username'].'</a> '.users_class($row['point'],$row['power']).'&nbsp;</td>
    <td width="80">'.$row['point'].'&nbsp;</td>
    <td width="60">'.($row['writecount']>0?'<a href="user_article.php?username='.urlencode($row['username']).'" target="_blank">'.$row['writecount'].'</a>':0).'&nbsp;</td>
    <td width="130" style="font-size:12px">'.$row['regdate'].'&nbsp;</td>
    <td width="130" style="font-size:12px">'.$row['thisdate'].'&nbsp;</td>
	<td width="80">'.$row['qq'].'&nbsp;</td>
	<td width="30"><a href="user_card.php?username='.urlencode($row['username']).'" target="_blank" title="点击查看发布人名片"><img src="images/card.gif" /></a></td>
	<td width="30" style="padding-right:7px;"><a href="user_mess.php?username='.urlencode($row['username']).'" target="_blank" title="发短信"><img src="images/mail_to.gif" /></a></td>
  </tr>
</table>';
		}else{
		  $text='<br /><img src="images/i.gif" /> 查不到此用户！<a href="javascript:window.history.back()">返回</a>';
		}
	  }else{
        $result=mysql_query('SELECT * FROM bbsmember ORDER BY id DESC',$db); //结果集
        $n=mysql_num_rows($result); //总记录数
        $p=get_page($n); //页数
        $text='';
        $seek=$n-$web['pagesize']*($p-1);
        $end=$seek-$web['pagesize']>0?$seek-$web['pagesize']:0;
	    $step=0;
        for($i=$seek-1;$i>=$end;$i--){
          if(mysql_data_seek($result,$i)){
            $row=mysql_fetch_assoc($result);
		    $step++;
            $text.='
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr class="step'.($step%2).'">
    <td style="padding-left:7px;"><a href="user_card.php?username='.urlencode($row['username']).'" target="_blank" title="点击查看发布人名片">'.$row['username'].'</a> '.users_class($row['point'],$row['power']).'&nbsp;</td>
    <td width="80">'.$row['point'].'&nbsp;</td>
    <td width="60">'.($row['writecount']>0?'<a href="user_article.php?username='.urlencode($row['username']).'" target="_blank">'.$row['writecount'].'</a>':0).'&nbsp;</td>
    <td width="130" style="font-size:12px">'.$row['regdate'].'&nbsp;</td>
    <td width="130" style="font-size:12px">'.$row['thisdate'].'&nbsp;</td>
	<td width="80">'.$row['qq'].'&nbsp;</td>
	<td width="30"><a href="user_card.php?username='.urlencode($row['username']).'" target="_blank" title="点击查看发布人名片"><img src="images/card.gif" /></a></td>
	<td width="30" style="padding-right:7px;"><a href="user_mess.php?username='.urlencode($row['username']).'" target="_blank" title="发短信"><img src="images/mail_to.gif" /></a></td>
  </tr>
</table>';
          }
        }
	  }
      mysql_free_result($result);
	  if($result=mysql_query('SELECT other3 FROM bbsmember WHERE username="'.$cookie[0].'"',$db)){ //结果集
        $row3=mysql_fetch_assoc($result);
        mysql_free_result($result);
      }	
	}else{
      $text.='<br /><img src="images/i.gif" /> 数据库['.$sql['name'].']连接不成功！';
	}
    mysql_close();
  }else{
    $text.='<br /><img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！';
  }
  echo $text;
  echo get_page_foot($p,$n,'');



?>
    </div>
  </div>
  <div id="right">
    <div id="power">
      <?php
echo $writepower=='发表帖子'?'<div class="session">欢迎：'.$cookie[0].' '.user_class(abs($cookie[1])).'<br />
<a href="user_messbox.php">短信箱</a>（<span id="new_mess" title="最新来信">'.abs($row3['other3']).'</span>） | <a href="user.php">用户中心</a> | <a href="run.php?run=user_login&act=logout&location='.basename($_SERVER['REQUEST_URI']).'">退出</a></div>
您上次访问是'.$you[1].'':'<div class="session">欢迎你：匿名用户<br /><a href="user_login.php?'.basename($_SERVER['REQUEST_URI']).'" style="color:#FF5500">登录您的帐号</a> <a href="user_reg.php?'.basename($_SERVER['REQUEST_URI']).'">快速注册帐号</a></div>';
?>
    </div>
    <div class="area">
      <div class="area_title"><img src="css/<?php echo $web['cssfile']; ?>/area_title.gif" align="absmiddle" /> 用户搜索</div>
      <div class="area_content">
        <input name="username" id="usernamebox" type="text" size="20" />
        <input name="button" type="button" onclick="location.href='?username='+encodeURIComponent(document.getElementById('usernamebox').value)+''" value="搜索" />
      </div>
    </div>


  </div>
</div>

  </div>
</div>
</body>
</html>
