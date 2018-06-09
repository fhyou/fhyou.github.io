

<?php
require('inc/set.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录<?php echo $web['code_author']; ?></title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $web['cssfile']; ?>/style.css">
<script language="javascript" type="text/javascript">

window.onload=function(){
  document.loginform.location.value=location.href.replace(/^[^\?]+(\?(.+))?/i,'$2');
}
function forPassword(){
  var str_n=prompt('寻找密码——请输入用户名：','');
  if(str_n){
    location.href='user_forpassword.php?username='+encodeURIComponent(str_n)+'';
  }
}


</script>
<script language="javascript" type="text/javascript">

function postChk(theForm){
  if(theForm.username.value.replace(/^(\s+|　+)|(\s+|　+)$/,'')==''){
    alert('用户名不能留空！');
	theForm.username.focus();
    return false;
  }
  if(theForm.password.value.replace(/^(\s+|　+)|(\s+|　+)$/,'')==''){
    alert('密码不能留空！');
	theForm.password.focus();
    return false;
  }
}

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
  <div class="area_title"><img src="css/<?php echo $web['cssfile']; ?>/area_title.gif" align="absmiddle" /> <a href="forum.php">课后讨论首页</a> &gt; 登录</div>
</div>

<form action="run.php?run=user_login" method="post" name="loginform" id="loginform" onsubmit="return postChk(this)">
  <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="left_menu" valign="top">
         欢迎回来！登录后您可以有更多发表权限，例如：
        <ol>
            <li>可以上传图片</li>
          <li>可以发布链接</li>
          <li>通过发表可以积攒积分，以获得更多其他权限。</li>
          <li><a href="user_power.php">有关会员权限详细说明，可点击这里。</a></li>
        </ol></td>
      <td width="100" valign="middle" align="right" class="pass"> 》</td>
    <td class="m_r" valign="top"><h4>填写</h4>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="100"><select name="logintype" id="logintype">
                  <option value="username" selected="selected">用户名登录</option>
                  <option value="email">用邮箱登录</option>
              </select></td>
              <td><input name="username" type="text" onkeydown="if(event.keyCode==32){alert('用户名不能有空格');return false;}" style="width:170px" /></td>
            </tr>
            <tr>
              <td width="100">密码</td>
              <td><input name="password" type="password" onkeydown="if(event.keyCode==32){alert('密码不能有空格');return false;}" style="width:170px" /></td>
            </tr>
            <tr>
              <td width="100">&nbsp;</td>
              <td><input name="save_cookie" type="checkbox" value="1" />
                两周不用再登录 <a href="#" onclick="forPassword();return false;">忘记密码</a></td>
            </tr>
            <tr>
              <td width="100">&nbsp;</td>
              <td><input name="submit" type="submit" value="登录" />
                  <input name="reset" type="reset" value="重置" />
				  <a href="user_reg.php">现在注册</a></td>
            </tr>
        </table></td>
    </tr>
  </table>
  <input name="act" type="hidden" value="login" />
  <input name="location" type="hidden" />
</form>

</div>
</div>
</body>
</html>