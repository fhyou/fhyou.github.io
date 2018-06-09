<?php
require('inc/set.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员中心 - 管理会员 - <?php echo $web['sitename']; ?><?php echo $web['code_author']; ?></title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $web['cssfile']; ?>/style.css">
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
	document.manageform.action='run.php?run=admin_del&dataname=bbsmember&limit='+manageType+'';
    document.manageform.submit();
  }
  return false;
}

function allChoose(v1,v2){
  var a=document.getElementsByName("id[]");
    for(var i=0;i<a.length;i++){ if(a[i].checked==false) a[i].checked=v1; else a[i].checked=v2;
  }
}

function goUserMess(){
  if(get_checkbox()==''){
    alert('数据为空或尚未点选！');
    return false;
  }
  document.manageform.action='user_mess.php';
  document.manageform.submit();
}
-->
</script>
<script language="javascript" type="text/javascript">
<!--
// 只允许输入数字
function isDigit(obj){
  if(!/^[\d\.]+$/.test(obj.value)){
    alert("你输入的值不对，只允许输入数字！");
    obj.value='';
  }
}
-->
</script>


<style type="text/css">
<!--
.list td { word-break:break-all; }
-->
</style>
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
require('inc/set_area.php');
require('inc/function/confirm_manager.php');
require('inc/function/user_class.php');
require('inc/function/get_page.php');
require('inc/function/getarea.php');

//身份图像
function users_class($point,$power){
  global $web;
  if($power=='manager'){
    $image='<img src="images/manager.gif" align="texttop" alt="管理员">';
  }else{
    if($point>=0 && $point<=abs($web['class_iron'])){
      $image='<img src="images/iron_l.gif"><img src="images/iron.gif" width="'.(ceil($point/(abs($web['class_iron'])/10)*3)+3).'" height="10" alt="积分'.$point.'-铁级用户"><img src="images/iron_r.gif">';
    }elseif($point>abs($web['class_iron']) && $point<=abs($web['class_silver'])){
      $image='<img src="images/silver_l.gif"><img src="images/silver.gif" width="'.(ceil($point/(abs($web['class_silver'])/10)*3)+3).'" height="10" alt="积分'.$point.'-银级用户"><img src="images/silver_r.gif">';
    }elseif($point>abs($web['class_slive']) && $point<=abs($web['class_gold'])){
      $image='<img src="images/gold_l.gif"><img src="images/gold.gif" width="'.(ceil($point/(abs($web['class_gold'])/10)*3)+3).'" height="10" alt="积分'.$point.'-金级用户"><img src="images/gold_r.gif">';
    }else{
      $image='<img src="images/diamond_'.(ceil($point/abs($web['class_gold']))-1).'.gif" align="texttop" alt="积分'.$point.'-'.(ceil($point/abs($web['class_gold']))-1).'钻级用户">';
    }
  }
  return $image;
}

function user_power($v){
  switch($v){
    case 'manager':
	return '管理员';
    break;
    case 'a':
	return '限制所有发表';
    break;
    case 't':
	return '限制发表主题';
    break;
    case 'r':
	return '限制回复';
    break;
    case 'u':
	return '限制上传';
    break;
  }
}


if(confirm_manager()){
  $manage='&manage=yes';
  require('inc/menu/admin_left_menu.txt');
  $yes='管理会员';
}

?>
    </td>
    <td width="100" valign="middle" align="right" class="pass"> 》</td>
    <td class="m_r" valign="top"><h4><?php echo $yes; ?>&nbsp;</h4>


<?php
unset($text);
if(isset($yes)){
  $you=explode('_',$cookie[1]);
  echo '欢迎：'.$cookie[0].' '.user_class(abs($cookie[1])).'<br />
<a href="user.php">用户中心</a> | <a href="run.php?run=user_login&act=logout">退出</a> | 您上次访问是'.$you[1].'';
?>

<form method="post" name="manageform" action="run.php?run=admin_del&dataname=bbsmember" onsubmit="if(get_checkbox()==''){alert ('请点选！');return false;}">
<div class="re_author">
用户列表&nbsp;&nbsp;&nbsp;&nbsp;直接抵达用户：<input name="username" type="text" size="20" value="<?php echo $_REQUEST['username']; ?>" />
<input type="button" onclick="location.href='?username='+encodeURIComponent(this.form.username.value)+''" value="找" />
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr>
    <th width="20">&nbsp;</th>
    <th>用户名</th>
    <th width="120">邮箱</th>
    <th width="80">权限</th>
    <th width="60">积分</th>
    <th width="30">发表数量</th>
    <th width="80">注册时间</th>
	<th width="30">置顶条数</th>
    <th width="100">置顶限期</th>
	<th width="50">QQ</th>
  </tr>
</table>
<?php
  require_once('inc/set_sql.php');
  //连接mysqkl数据库
  if($db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
    //选择数据库并判断
    if(@mysql_select_db($sql['name'],$db)){
      mysql_query('SET NAMES '.$sql['char'].'');
	  if($_REQUEST['username']){
	    $eval=' WHERE username LIKE "%'.$_REQUEST['username'].'%"';
	  }
	  if($result=mysql_query('SELECT * FROM bbsmember'.$eval.' ORDER BY id DESC',$db)){
        $n=mysql_num_rows($result); //总记录数
        $p=get_page($n); //页数
        $text='';
        $seek=$n-$web['pagesize']*($p-1);
        $end=$seek-$web['pagesize']>0?$seek-$web['pagesize']:0;
	  $step=0;
        for($i=$seek-1;$i>=$end;$i--){
          if(mysql_data_seek($result,$i)){
		    $step++;
            $row=mysql_fetch_assoc($result);
            $text.='
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
  <tr class="step'.($step%2).'">
    <td width="20"><input name="id[]" id="id[]" class="" type="checkbox" value="'.$row['id'].'" /></td>
    <td><input name="user[]" id="user[]" type="hidden" value="'.$row['username'].'" />'.$row['username'].''.users_class($row['point'],$row['power']).'&nbsp;</td>
    <td width="120">'.$row['email'].'&nbsp;</td>
    <td width="80">'.user_power($row['power']).'&nbsp;</td>
    <td width="60">'.$row['point'].'&nbsp;</td>
    <td width="30">'.$row['writecount'].'&nbsp;</td>
    <td width="80">'.$row['regdate'].'&nbsp;</td>
	<td width="30">'.$row['topcount'].'&nbsp;</td>
	<td width="100">'.$row['topdate'].'&nbsp;</td>
	<td width="50">'.$row['qq'].'&nbsp;</td>
  </tr>
</table>';
          }
        }
	  }
      mysql_free_result($result);
	}else{
      $text.='<br /><img src="images/i.gif" /> 数据库['.$sql['name'].']连接不成功！';
	}
    mysql_close();
  }else{
    $text.='<br /><img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！';
  }
  echo !empty($text)?$text:'查不到此用户！';
  echo get_page_foot($p,$n,'');
  echo '
  <a href="void(0)" onclick="javascript:allChoose(true,true);return false;">全选</a>-
  <a href="void(0)" onclick="javascript:allChoose(true,false);return false;">反选</a>-
  <a href="void(0)" onclick="javascript:allChoose(false,false);return false;">不选</a>
<input name="act" type="button" value="群发短信" onclick="goUserMess()" />
<input name="act" type="button" value="删除" onclick="chk(this,\'del\')" /><br />
<input name="act" type="button" value="设为管理员" onclick="chk(this,\'addadmin\')" />
<input name="act" type="button" value="取消管理员" onclick="chk(this,\'deladmin\')" /><br />

<input name="act" type="button" value="限制发表" onclick="chk(this,\'t\')" />
<input name="act" type="button" value="取消限制发表" onclick="chk(this,\'tt\')" /><br />
<input name="act" type="button" value="限制评论" onclick="chk(this,\'r\')" />
<input name="act" type="button" value="取消限制评论" onclick="chk(this,\'rr\')" /><br />
<input name="act" type="button" value="限制上传" onclick="chk(this,\'u\')" />
<input name="act" type="button" value="取消限制上传" onclick="chk(this,\'uu\')" /><br />
<input name="act" type="button" value="限制所有" onclick="chk(this,\'a\')" />
<input name="act" type="button" value="取消限制所有" onclick="chk(this,\'aa\')" /><br />

加赠积分：<input name="addpoint" onKeyUp="isDigit(this)" onfocus="this.form.delpoint.value=\'\'" type="text" size="2" /> <input type="submit" value="提交" />
减扣积分：<input name="delpoint" onKeyUp="isDigit(this)" onfocus="this.form.addpoint.value=\'\'" type="text" size="2" /> <input type="submit" value="提交" /> 注：扣减分不能大小用户当前积分<br />

批准：置顶<input name="topcount" onKeyUp="isDigit(this)" type="text" size="2" />条，置顶<input name="topdate" onKeyUp="isDigit(this)" type="text" size="2" />天
<input type="submit" value="提交" />
<input name="act" type="button" value="取消置顶权限" onclick="chk(this,\'ctop\')" />
';


}else{
  echo '<img src="images/i.gif" /> 请以基本管理员'.$web['manager'].'<a href="user_login.php?'.basename($_SERVER['REQUEST_URI']).'"><b>登录</b></a>，以获得管理权限';
}

?>
<br />
<br />
<br />
<br />
	</form>
    </td>
  </tr>
</table>

</div>
</div>
</body>
</html>
