<?php
require('inc/set.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户中心 - 管理已发信息 - <?php echo $web['sitename']; ?><?php echo $web['code_author']; ?></title>
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

  $yes='管理已发信息';
}

?>
    </td>
    <td width="100" valign="middle" align="right" class="pass"> 》</td>
    <td class="m_r" valign="top"><h4><?php echo $yes; ?>&nbsp;</h4>


<script language="javascript" type="text/javascript">
<!--
function get_checkbox(){
  var allCheckBox=document.getElementsByName("id[]");
  var article='';
  if(allCheckBox!=null && allCheckBox.length>0){
    for(var i=0;i<allCheckBox.length;i++){
      if(allCheckBox[i].checked==true && allCheckBox[i].disabled==false){
        article=allCheckBox[i].value;
        break;
      }
    }
  }
  return article;
}

//管理
function chk(obj,manageType){
  if(get_checkbox()==''){
    alert('数据为空或尚未点选！');
    return false;
  }
  if(confirm('确定'+obj.value+'吗？')){
    if(manageType=='edit')
      document.manageform.action='user_list_edit.php';
    else
      document.manageform.action='run.php?run=user_del&limit='+manageType+'';
    document.manageform.submit();
  }
  return false;
}

function allChoose(v1,v2){
  var a=document.getElementsByName("id[]");
    for(var i=0;i<a.length;i++){ if(a[i].checked==false) a[i].checked=v1; else a[i].checked=v2;
  }
}
-->
</script>
<form method="post" name="manageform">
<?php
if(isset($yes)){
  $you=explode('_',$cookie[1]);
  echo '欢迎：'.$cookie[0].' '.user_class(abs($cookie[1])).'<br />
      <a href="user.php">用户中心</a> | <a href="run.php?run=user_login&act=logout">退出</a> | 您上次访问是'.$you[1].'';


   require('inc/set_sql.php');
  //连接mysqkl数据库
  if($db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
    if(@mysql_select_db($sql['name'],$db)){
      mysql_query('SET NAMES '.$sql['char'].'');
      if($result=mysql_query('SELECT * FROM bbslistdata WHERE author_ip="'.$cookie[0].'" ORDER BY topdate',$db)){ //结果集
        $n=mysql_num_rows($result); //总记录数
        require('inc/function/get_page.php');
        $p=get_page($n); //页数
        $seek=$n-$web['pagesize']*($p-1);
        $end=$seek-$web['pagesize']>0?$seek-$web['pagesize']:0;
	  $step=0;
        for($i=$seek-1;$i>=$end;$i--){
          if(mysql_data_seek($result,$i)){
            if($row=mysql_fetch_assoc($result)){
		      $step++;
              $text.='
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr class="step'.($step%2).'">
    <td width="40"><input name="id[]" id="id[]" class="" type="checkbox" value="'.$row['id'].'" /></td>
    <td><a href="article.php?id='.$row['id'].'" target="_blank">'.$row['title'].'</a></td>
    <td width="100">'.$row['reply'].'|'.$row['views'].'</td>
    <td width="100" style="font-size:12px">'.$row['date'].'</td>
  </tr>
</table>
';
            }
          }
	    }
        mysql_free_result($result);
		unset($row);
        $result=mysql_query('SELECT topcount,topdate FROM bbsmember WHERE username="'.$cookie[0].'"',$db); //结果集
        $row=mysql_fetch_assoc($result);
        mysql_free_result($result);
        $date=gmdate('YmdHis',time()+(floatval($web['time_pos'])*3600));
		if($cookie[2]!='manager'){
          if(abs($row['topcount'])>0 && $row['topdate'] && $row['topdate']>$date){
            $topok1='<font color=#FF5500>你可以置顶的数量为'.$row['topcount'].'条，置顶期限至'.get_date($row['topdate']).'</font>';
          }else{
            $topok2='disabled';
		  }
		}
        $text.=get_page_foot($p,$n,'');
        $text.='
  <a href="void(0)" onclick="javascript:allChoose(true,true);return false;">全选</a>-
  <a href="void(0)" onclick="javascript:allChoose(true,false);return false;">反选</a>-
  <a href="void(0)" onclick="javascript:allChoose(false,false);return false;">不选</a>
<input name="act" type="button" value="编辑" onclick="chk(this,\'edit\')" />
<input name="act" type="button" value="删除" onclick="chk(this,\'del\')" />
<input name="act" type="button" value="刷新" onclick="chk(this,\'refresh\')" /> <font color=#FF5500>常来刷新让信息排名靠前</font><br />
<input name="act" type="button" value="置顶" onclick="chk(this,\'top\')" '.$topok2.'/> '.$topok1.'
<input name="act" type="button" value="取消置顶" onclick="chk(this,\'ctop\')" '.$topok2.'/>
';
	  }else{
        $text.='<br /><img src="images/i.gif" /> 数据库查无数据！';
	  }
    }else{
      $text.='<br /><img src="images/i.gif" /> 数据库['.$sql['name'].']连接不成功！';
    }
    mysql_close();
  }else{
    $text.='<br /><img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！';
  }
?>

<div class="re_author">信息记录</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr>
    <th width="40">&nbsp;</th>
    <th>标题</th>
    <th width="100">评论回复|阅览</th>
    <th width="100">时间</th>
  </tr>
</table>

<?php


  if($text){
    echo $text;
  }else{
    echo '<br /><img src="images/i.gif" /> 暂无数据！';
  }



}else{
  echo '欢迎你：Guest匿名用户<br /><a href="user_reg.php?'.basename($_SERVER['REQUEST_URI']).'"><b>先去创建帐号（非常简单）</b></a>或<a href="user_login.php?'.basename($_SERVER['REQUEST_URI']).'"><b>登录帐号</b></a>，以获得更多发表或管理权限';
}

?>
</form>    </td>
  </tr>
</table>
</div>
</div>
</body>
</html>
