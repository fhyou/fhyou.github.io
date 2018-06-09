<?php
require('inc/function/write_file.php');
require('inc/function/cutstr.php');
require('inc/function/filter.php');

function filter($text){
  if(!empty($text) && preg_match('/[\s\r\n\"\'\\\]+/',$text)){
    err('<img src="images/i.gif" /> 所填内容不能含 空格 及 \' " \ ');
  }
  $text=stripslashes($text);
  $text=htmlspecialchars($text);
  return $text;
}

function trim_all($text){
  return cutstr(strtolower(filter($text)),180);
}
 
$_POST=array_map('trim_all',$_POST);

$parameter='http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']),'/\\').'/';
$_REQUEST['location']=str_replace($parameter,'',$_REQUEST['location']);
if(file_exists(preg_replace('/(\?|#).*/','',$_REQUEST['location'])))
  $loc=$_REQUEST['location'];
else
  $loc='./';


if($_POST['act']=='reg'){

  if($web['stop_reg']==1){
    err('<img src="images/i.gif" /> 当前系统设置为：禁止新用户注册');
  }
  if(isset($_COOKIE['usercookie'])){
    $cookie=@explode('|',$_COOKIE['usercookie']);
    err('<img src="images/i.gif" /> 您已经以 用户名：'.$cookie[0].' 登录过了！<br />要想更换用户名登录，请先<a href="run.php?run=user_login&act=logout&loc='.$loc.'">退出</a>');
  }
  
  if(preg_match('/[\?\+\%\"\'\|\\\]+/',$_POST['username'])){
    err('<img src="images/i.gif" /> 提交被拒绝！用户名请尽量用汉字、数字、英文及下划线组成，不能含 ? + % " \' | \ ');
  }
  if(strlen($_POST['username'])<3 || strlen($_POST['username'])>45){
    err('<img src="images/i.gif" /> 提交被拒绝！用户名——长度请控制在3-45个字符之内（汉字为3字符）');
  }
  if(preg_match('/admin|manager|管理员|版主|斑竹|访客|系统欢迎信|一站搜|162100|furuijinzhao|yzsou|操/i',$_POST['username'])){
    err('<img src="images/i.gif" /> 提交被拒绝！用户名未获通过');
  }
  $_POST['username']=str_replace('.','&#46;',$_POST['username']);

  if(!preg_match('/^[^\\\]{3,30}$/',$_POST['password'])){
    err('<img src="images/i.gif" /> 提交被拒绝！密码——长度请控制在3-30个字符之内。不能是 \ ');
  }
  if($_POST['password_again']==''){
    err('<img src="images/i.gif" /> 密码重输不能留空！');
  }
  if($_POST['password']!=$_POST['password_again']){
    err('<img src="images/i.gif" /> 两次密码输得不一样！');
  }
  fil();
  
 //****************************************************************************************

  //连接mysqkl数据库
  if(!$db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
    echo mysql_errno() . ": " . mysql_error() . "\n";
    err('<img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！');
  }
  //选择数据库并判断
  if(!@mysql_select_db($sql['name'],$db)){
    echo mysql_errno() . ": " . mysql_error() . "\n";
    err('<img src="images/i.gif" /> 数据库['.$sql['name'].']连接不成功！');
  }
  mysql_query('SET NAMES '.$sql['char'].'');
  
  if($result=mysql_query("SELECT * FROM bbsmember WHERE username='".$_POST['username']."'",$db)){
    $n=mysql_num_rows($result);
	if($n>0)
      err('<img src="images/i.gif" /> 此用户名已被注册！');
  }
  if($result=mysql_query("SELECT * FROM bbsmember WHERE email='".$_POST['email']."'",$db)){
    $n=mysql_num_rows($result);
	if($n>0)
      err('<img src="images/i.gif" /> 此邮箱已有人使用！');
  }
  $messline="".gmdate("YmdHis",time()+(floatval($web["time_pos"])*3600))."|".$web['manager']."|欢迎加入".$web["sitename"]."！|欢迎加入！请保持持续热情支持".$web["sitename"]."，并欢迎积极发表、反馈问题。||| \n";
  mysql_query("ALTER TABLE `bbsmember` MODIFY COLUMN `other2` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL");
  //mysql_query("UPDATE bbsmember SET other2='".$messline."',other3=1 WHERE username='".$you['name']."'");
  $result=@mysql_query('INSERT INTO `bbsmember`(`username`,`password`,`email`,`password_question`,`password_answer`,`point`,`writecount`,`regdate`,`thisdate`,`qq`,`other2`,`other3`) values("'.$_POST['username'].'","'.$_POST['password'].'","'.$_POST['email'].'","'.$_POST['password_question'].'","'.$_POST['password_answer'].'","'.$web['writeadd'].'","0","'.($thisdate=gmdate('Y/m/d H:i:s',time()+(floatval($web['time_pos'])*3600))).'","'.$thisdate.'","'.$_POST['qq'].'","'.$messline.'",1)');

  if(mysql_affected_rows()>0){
    $you['name']=$_POST['username'];
    $you['id']=''.$web['writeadd'].'_'.$thisdate.''; // 最后登录时间_积分
    $you['power']='';
    //用js设置cookie，因为前面已有输出
    echo '
      <script language="javascript" type="text/javascript">
      <!--
	  //
	  '.(($_POST['save_cookie']==1) ? '
      var expiration=new Date((new Date()).getTime()+1209600*1000);
      document.cookie="usercookie="+encodeURIComponent(\''.@implode('|',$you).'\')+"; expires="+expiration.toGMTString()+"; path=/;";' : '
      document.cookie="usercookie="+encodeURIComponent(\''.@implode('|',$you).'\')+"; path=/;";').'
      -->
      </script>';
	  
	  
	  
    alert('<img src="images/ok.gif" /> 注册成功！欢迎您 '.$you['name'].'',$loc);
  }else{
    echo mysql_errno() . ": " . mysql_error() . "\n";
    err('<img src="images/i.gif" /> 注册不成功！');
  }
  mysql_free_result($result);
  mysql_close();


//修改注册信息
}elseif($_POST['act']=='regfilemodify'){
  require('inc/function/confirm_login.php');
  if(confirm_login()==false){
    err('<img src="images/i.gif" /> 系统检测你未登录，没有权限！');
  }
  if($cookie[0]==$web['manager'] || $cookie[2]=='manager'){
    require('inc/function/confirm_manager.php');
    if(confirm_manager()==false){
      err('<img src="images/i.gif" /> 系统检测你管理员密钥不符，没有权限！');
    }
  }

  if(is_array($_FILES['uploadfile']) && $_FILES['uploadfile']['size']){
    //上传路径
    $upload_dir='data/upload';
    if(!file_exists($upload_dir)){
      $err.='<br />头像无法上传，上传目录data/upload不存在';
    }else{
      @chmod($upload_dir,0777);
      $inis = ini_get_all();
      $uploadmax=$inis['upload_max_filesize'];
/*
[global_value] => 2M
[local_value] => 2M
[access] => 6
*/
//print_r($_FILES['uploadfile']);
      if($_FILES['uploadfile']['size']>$web['max_file_size'][15]*1024){
        $err.='<br />头像上传不成功！上传的文件请小于'.$web['max_file_size'][15].'KB';
      }else{
        if(!preg_match('/\.(jpg|gif|jpeg)$/i',$_FILES['uploadfile']['name'],$matches)){
          $err.='<br />头像上传不成功！请选择一个有效的文件：允许的格式有（jpg|gif|jpeg）';
        }else{
          //文件命名
          if(@move_uploaded_file($_FILES['uploadfile']['tmp_name'],$upload_dir.'/'.urlencode($cookie[0]).'.jpg')){
            $err.='<br />头像上传成功';
	      }else{
            $err.='<br />头像上传不成功';
	      }
		}
	  }
	}
  }

  //连接mysqkl数据库
  if(!$db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
    echo mysql_errno() . ": " . mysql_error() . "\n";
    err('<img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！');
  }
  //选择数据库并判断
  if(!@mysql_select_db($sql['name'],$db)){
    echo mysql_errno() . ": " . mysql_error() . "\n";
    err('<img src="images/i.gif" /> 数据库['.$sql['name'].']连接不成功！');
  }
  mysql_query('SET NAMES '.$sql['char'].'');

  if($_POST['password_new']!=''){
    if($_POST['password']==''){
      err('<img src="images/i.gif" /> 请输入原密码！');
	}
    $result=mysql_query("SELECT password FROM bbsmember WHERE username='".$cookie[0]."'",$db);
	if($row=@mysql_fetch_assoc($result)){
      if($_POST['password']!=$row['password']){
        err('<img src="images/i.gif" /> 原密码输入错误！与档案不符');
	  }
	}else{
	  $err.='<br />密码修改失败（数据库读取失败）';
	}
	mysql_free_result($result);
    if(!preg_match('/^[^\\\]{3,30}$/',$_POST['password_new'])){
      err('<img src="images/i.gif" /> 提交被拒绝！密码——长度请控制在3-30个字符之内。不能是 \ ');
    }
    if($_POST['password_again']!=$_POST['password_new']){
      err('<img src="images/i.gif" /> 提交被拒绝！确认密码，请确保两次密码都输入且一样');
	}
    $pppp='password=\''.$_POST['password_new'].'\',';
  }
  fil();

  if($_POST['handtel']!='' && !preg_match('/[\d-]{1,20}/',$_POST['handtel'])){
    err('<img src="images/i.gif" /> 移动电话请添11位数字！');
  }
  if($_POST['hometel']!='' && !preg_match('/[\d-]{1,20}/',$_POST['hometel'])){
    err('<img src="images/i.gif" /> 办公电话请添“区号-数字（20位以内）”格式！');
  }
  if($_POST['zip']!='' && !preg_match('/[\d-]{6}/',$_POST['zip'])){
    err('<img src="images/i.gif" /> 邮编请添数字（6位）！');
  }
  if($_POST['qq']!='' && !preg_match('/[\d-]{1,20}/',$_POST['qq'])){
    err('<img src="images/i.gif" /> QQ请添数字（20位以内）！');
  }
  mysql_query("UPDATE bbsmember SET ".$pppp."
email='".$_POST['email']."',
password_question='".$_POST['password_question']."',
password_answer='".$_POST['password_answer']."',
realname='".$_POST['realname']."',
sex='".$_POST['sex']."',
birthday='".$_POST['birthday']."',
handtel='".$_POST['handtel']."',
hometel='".$_POST['hometel']."',
company='".$_POST['company']."',
address='".$_POST['address']."',
zip='".$_POST['zip']."',
qq='".$_POST['qq']."',
sign='".$_POST['sign']."' WHERE username='".$cookie[0]."'");


  if(mysql_affected_rows()>0){
    alert('<img src="images/ok.gif" /> 修改成功！'.$err.'','user_file.php');
  }else{
    echo mysql_errno() . ": " . mysql_error() . "\n";
    err('<img src="images/i.gif" /> 修改不成功！可能你未做改动。'.$err.'');
  }
  mysql_close();




}



function fil(){
  if($_POST['password_question']==''){
    err('<img src="images/i.gif" /> 密码问题不能留空！');
  }
  if($_POST['password_answer']==''){
    err('<img src="images/i.gif" /> 密码答案不能留空！');
  }
  if($_POST['email']==''){
    err('<img src="images/i.gif" /> email不能留空！');
  }
  if(!preg_match('/^[\w\.]+@[\w\.]+\.[\w\.]+$/',$_POST['email'])){
    err('<img src="images/i.gif" /> email填：xxx@xxx.xxx(.xx) 格式');
  }
  if(isset($_COOKIE['regimcode'])){
    if(!is_numeric($_POST['imcode']))
      err('<img src="images/i.gif" /> 验证码非数字！');
    if($_POST['imcode']!=$_COOKIE['regimcode'])
      err('<img src="images/i.gif" /> 验证码不符！');
  }


}


?>



