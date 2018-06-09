<?php

//文章ID
if($_REQUEST['id']=='' || !is_numeric($_REQUEST['id'])){
  die('&#73;&#68;&#21442;&#25968;&#32570;&#22833;&#25110;&#20986;&#38169;&#65292;&#35831;&#20174;<a href="./">&#39318;&#39029;</a>&#20174;&#26032;&#24320;&#22987;');
}
require('inc/set.php');
require('inc/set_area.php');
require('inc/set_sql.php');
require('inc/function/confirm_login.php');
require('inc/function/user_class.php');

//登录否？
if(confirm_login()){
  $you=explode('_',$cookie[1]);
  $writepower='发表帖子';
}else{
  $writepower='匿名发表';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
if($db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
  if(@mysql_select_db($sql['name'],$db)){
    mysql_query('SET NAMES '.$sql['char'].'');
    if($result=mysql_query('SELECT * FROM bbslistdata WHERE id="'.$_REQUEST['id'].'"',$db)){ //结果集
      $row=mysql_fetch_assoc($result);
      mysql_query('UPDATE bbslistdata SET views='.(abs($row['views'])+1).' WHERE id="'.$_REQUEST['id'].'"',$db);
      mysql_free_result($result);
    }
    if($row['area_id']!=''){
      if($result=mysql_query('SELECT * FROM bbslistdata WHERE area_id="'.$row['area_id'].'" ORDER BY topdate DESC LIMIT 10',$db)){ //结果集
        while($row_=mysql_fetch_assoc($result)){
          $text_.='·<a href="article.php?id='.$row_['id'].'" target="_blank">'.$row_['title'].'</a><br />';
        }
        mysql_free_result($result);
      }
    }
    if($row['id']!=''){
	  if($result=mysql_query('SELECT * FROM bbsreply WHERE r_id="'.$_REQUEST['id'].'" ORDER BY date',$db)){ //结果集
	    $restep=1;
        while($row__=mysql_fetch_assoc($result)){
	      $restep++;
          $text__.='
		  '.($row__['text']=='**** **** 此条已删' || $row__['text']==''?'<div class="re_author" id="reply_'.$row__['id'].'"><a class="del_re">回帖已删</a>['.$restep.'楼] '.$row__['author_ip'].' - '.$row__['date'].'</div>':'<div class="re_author" id="reply_'.$row__['id'].'"><a href="run.php?run=admin_del&id='.$row__['id'].'&dataname=bbsreply" class="del_re">删除</a><a href="#postform" class="del_re">回复</a>['.$restep.'楼] '.$row__['author_ip'].' - '.$row__['date'].'</div><div class="text">'.$row__['text'].'</div>').'';
        }
		mysql_free_result($result);
      }
    }
	if($result=mysql_query('SELECT other3 FROM bbsmember WHERE username="'.$cookie[0].'"',$db)){ //结果集
      $row3=mysql_fetch_assoc($result);
      mysql_free_result($result);
    }	

    mysql_close();
  }/*else{
    $text='指定的数据库连接不成功！';
  }*/
}/*else{
  $text='数据库连接失败！';
}*/

?><title><?php echo $row['title']; ?><?php echo $web['code_author']; ?></title>
<meta name="Description" content="<?php echo $row['title']; ?> - <?php echo $web['description']; ?>" />
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

<div class="area">
  <div class="area_title"><img src="css/<?php echo $web['cssfile']; ?>/area_title.gif" align="absmiddle" /> <?php
list($area_id,$class_id)=@explode('_',$row['area_id']);
echo '<a href="forum.php">课后讨论首页</a> &gt; <a href="forum.php?area_id='.$area_id.'">'.$web['area'][$area_id][0].'</a> &gt;  <a href="forum.php?area_id='.$row['area_id'].'">'.$web['area'][$area_id][$class_id].'</a>'; ?></div>
</div>

<div id="body">
  <div id="area">
    <div class="area_title"><a href="#postform" class="send_r">回 复</a><a href="user_write.php?area_id=<?php echo $row['area_id']; ?>" class="send"><?php echo $writepower; ?></a>
        <div style="float:right">[<a href="user_list_edit.php?id=<?php echo $_REQUEST['id']; ?>">编辑</a>]</div>
      <img src="css/<?php echo $web['cssfile']; ?>/area_title.gif" align="absmiddle" /> [1楼] 主帖正文</div>
    <div id="list_out">
      <div class="text">
        <?php
echo '<h4>'.$row['title'].'</h4>'.$row['text'].'';
?>

      </div>
      <?php
echo $text__;
?>
    </div>
    <div class="area">
  <div class="area_title"><img src="css/<?php echo $web['cssfile']; ?>/area_title.gif" align="absmiddle" /> 评论或回复</a></div>
      <div class="area_content"><script language="JavaScript" type="text/javascript">
<!--
function postChk(theForm){
  var con=theForm.content.value.replace(/^\s+|\s+$/g,'');
  if(con==''){
    alert('留言内容不能为空白！');
	theForm.content.focus();
    return false;
  }
  if(con.length > 400){
    alert('请缩短你的留言内容至400字符以下。现在为'+con.length+'字符，大约得减'+(con.length-400)+'字符');
	theForm.content.focus();
    return false;
  }
  var im=theForm.imcode.value;
  if(imC=getCookie("regimcode")){
    if(imC!=im){
      alert('请准确填写验证码！');
      return false;
    }
  }
  return true;
}
-->
</script>
            <form id="postform" name="postform" method="post" action="run.php?run=user_reply" onsubmit="return postChk(this)">
              <textarea name="content" id="content" rows="6" style="width:500px"></textarea>
              <font color="#B8B8B8">限字符数：<?php echo $web['re_wordcount']; ?></font>
              <table width="350" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="200">输入验证码：
                    <input name="imcode" id="imcode" type="text" style="width:100px" /></td>
                  <td><iframe src="js/imcode.html" id="imFrame" name="imFrame" frameborder="0" width="100" height="24" scrolling="no"></iframe></td>
                </tr>
              </table>
              <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
              <input id="go" type="submit" name="go" value="提交评论回复" />
              <input name="reset" type="reset" value="重置" />
          </form></div>
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
  <div class="area_title"><img src="css/<?php echo $web['cssfile']; ?>/area_title.gif" align="absmiddle" /> 文章档案</div>
      <div class="area_content"><?php
	  require('inc/function/get_date.php');
echo '发布日期：'.$row['date'].'<br />
	  '.($row['topdate']>gmdate('YmdHis',time()+(floatval($web['time_pos'])*3600))?'置顶期至':'刷新日期').'：'.get_date($row['topdate']).'<br />
      发布人：'.(strstr($row['author_ip'],'.')?'匿名用户('.$row['author_ip'].')':'<a href="user_card.php?username='.urlencode($row['author_ip']).'" target="_blank" title="点击查看发布人名片">'.$row['author_ip'].'<img src="images/card.gif" /></a>').'<br />
      回复或评论：'.$row['reply'].'<br />
      阅览：'.$row['views'].'';

?>
      </div>
    </div>
    <div class="area">
  <div class="area_title"><img src="css/<?php echo $web['cssfile']; ?>/area_title.gif" align="absmiddle" /> <a href="forum.php?area_id=<?php echo $row['area_id']; ?>">本栏目更多信息</a></div>
      <div class="area_content"><?php echo $text_!=''?$text_:'暂无'; ?></div>
    </div>


  </div>
</div>
  </div>
</div>
</body>
</html>
