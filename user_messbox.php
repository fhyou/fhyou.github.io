<?php
require('inc/set.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户中心 - 收信箱 - <?php echo $web['sitename']; ?><?php echo $web['code_author']; ?></title>
<meta name="Description" content="<?php echo $web['description']; ?>" />
<meta name="keywords" content="<?php echo $web['keywords']; ?>" />
<link rel="stylesheet" type="text/css" href="css/<?php echo $web['cssfile']; ?>/style.css">
<link rel="stylesheet" type="text/css" href="css/editor.css">
<script language="javascript" type="text/javascript" src="js/main.js"></script>
<style type="text/css">
<!--
#zhaiyao { padding:5px 7px 5px 30px; border:1px #CECECE solid; font-size:14px; color:#132CFB; }
-->
</style></head>

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
if(confirm_login()){
  require('inc/menu/user_left_menu.txt');

  $yes='收信箱';
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
    document.manageform.action='run.php?run=user_delmess';
    document.manageform.submit();
  }
  return false;
}

function allChoose(v1,v2){
  var a=document.getElementsByName("id[]");
    for(var i=0;i<a.length;i++){ if(a[i].checked==false) a[i].checked=v1; else a[i].checked=v2;
  }
}

//预览
function openZhaiyao(obj,sid){
    try{
	  var zy=document.getElementById('zhaiyao');
	  var pp=zy.parentNode;
	  pp.removeChild(zy);
	}catch(err){}
	var tdobj=obj;
    var t=tdobj.offsetTop;
    while(tdobj=tdobj.offsetParent){
      t+=tdobj.offsetTop;
    }
    obj.id='zhaiyaoopen';
    document.getElementById('lastFrame').src='user_mess_get.php?obj='+obj.id+'&id='+sid+'&t='+t+'';
}

-->
</script>
<iframe id="lastFrame" name="lastFrame" frameborder="0" style="display:none"></iframe>
<form method="post" name="manageform">

<?php
if(isset($yes)){
  $you=explode('_',$cookie[1]);
  echo '欢迎：'.$cookie[0].' '.user_class(abs($cookie[1])).'<br />
      <a href="user.php">用户中心</a> | <a href="run.php?run=user_login&act=logout">退出</a> | 您上次访问是'.$you[1].'';
?>

<div class="re_author">信息记录</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr>
    <th width="40">&nbsp;</th>
    <th>标题</th>
    <th width="120">发信人</th>
    <th width="130">时间</th>
    <th width="40">状态</th>
  </tr>
</table>
<?php
require('inc/set_sql.php');
require('inc/function/get_date.php');
//连接mysqkl数据库
if($db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
  if(@mysql_select_db($sql['name'],$db)){
    mysql_query('SET NAMES '.$sql['char'].'');
    if($result=mysql_query('SELECT other2,other3 FROM bbsmember WHERE username="'.$cookie[0].'"',$db)){ //结果集
      $row=mysql_fetch_assoc($result);
      mysql_free_result($result);
	  //echo $row['other2'];die;
      $messlist=array_filter(explode("\n",trim($row['other2'])));
	  $newnum=$row['other3'];
	  unset($row);
	  $n=count($messlist); //总记录数
	  
      if($n>0){
        require('inc/function/get_page.php');
        $p=get_page($n); //页数
		//print_r($messlist);echo $newnum.'AA';die;
	  $step=0;
        foreach($messlist as $key=>$line){ //时间|来信人|主题|内容|||已读状态
		    $step++;
		  $row=@explode("|",$line);
		  if($row[6]=='o'){
		    $read_y_n='已读';
		  }else{
   		    $read_y_n='未读';
		  }
          $text.='
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr class="step'.($step%2).'">
    <td width="40"><input name="id[]" id="id[]" class="" type="checkbox" value="'.$row[0].'" /></td>
    <td><a href="javascript:void(0)" onclick="openZhaiyao(this,\''.$row[0].'\');return false;">'.$row[2].'</td>
    <td width="120"><a href="user_card.php?username='.urlencode($row[1]).'">'.$row[1].'</a></td>
    <td width="130" style="font-size:12px">'.get_date($row[0]).'</td>
    <td width="40" id="read'.$row[0].'">'.$read_y_n.'</td>
  </tr>
</table><span id="show'.$row[0].'"></span>
';
		  unset($row);
	    }
        $text.=get_page_foot($p,$n,'');
		
        $text.='
  <a href="void(0)" onclick="javascript:allChoose(true,true);return false;">全选</a>-
  <a href="void(0)" onclick="javascript:allChoose(true,false);return false;">反选</a>-
  <a href="void(0)" onclick="javascript:allChoose(false,false);return false;">不选</a>
<input name="act" type="button" value="删除" onclick="chk(this,\'delmess\')" />
';
        $read_arr=preg_grep("/\|\s*$/",$messlist);
		$read_n=count($read_arr);
		//echo count($read_n);die;
        if($read_n!=$newnum){
          mysql_query("UPDATE bbsmember SET other3=".$read_n." WHERE username='".$cookie[0]."'");
		}
	  }else{
        $text.='<br /><img src="images/i.gif" /> 数据库查无数据！';
	  }
    }else{
      $text.='<br /><img src="images/i.gif" /> 数据库查无记录！';
    }
    mysql_close();
  }else{
    $text.='<br /><img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！';
  }
}else{
  $text.='<br /><img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！';
}

echo $text;

?>
<?php
}else{
  echo '欢迎你：匿名用户<br /><a href="user_reg.php?'.basename($_SERVER['REQUEST_URI']).'"><b>先去创建帐号（非常简单）</b></a>或<a href="user_login.php?'.basename($_SERVER['REQUEST_URI']).'"><b>登录帐号</b></a>，以获得更多发表或管理权限';
}

?>
</form>    </td>
  </tr>
</table>


</div>
</div>
</body>
</html>
