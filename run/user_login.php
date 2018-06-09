<?php
require('inc/function/filter.php');
require('inc/function/write_file.php');

function filter($text){
  if(!empty($text) && preg_match('/[\s\r\n\"\'\\\]+/',$text)){
    err('<img src="images/i.gif" /> 所填内容不能含 空格 及 \' " \ ');
  }
  $text=stripslashes($text);
  $text=htmlspecialchars($text);
  return $text;
}
 
$_POST=array_map('filter',$_POST);


//基础管理员任何故障下登录
function base_manager_login($name,$password){
  global $web,$nowdate,$loc;
    $name=str_replace('.','&#46;',$name);
    if($name==$web['manager'] && $password==$web['password']){
      $you['name']=$name;
      $you['id']='10000_'.$nowdate.'_'.mt_rand(100000,999999).'';
      $you['power']='manager';
	  if(!file_exists('power')){
	    @mkdir('power',0777);
	  }
	  if(file_exists('power')){
        write_file('power/'.urlencode($you['name']).'.php','<?php die(); ?>'.$you['id'].''); //登录密钥记录
	  }
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
      alert('<img src="images/ok.gif" /> 以基础管理员身份登录成功！欢迎您 '.$you['name'].'',$loc);
    }else{
      err('<img src="images/i.gif" /> 以基础管理员身份登录失败！原因分析：1、您输入的用户名或邮箱不对');
    }
}



$parameter='http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']),'/\\').'/';
$_REQUEST['location']=str_replace($parameter,'',$_REQUEST['location']);
if(file_exists(preg_replace('/(\?|#).*/','',$_REQUEST['location'])))
  $loc=$_REQUEST['location'];
else
  $loc='forum.php';


//退出
if($_REQUEST['act']=='logout'){
  echo '
  <script language="javascript" type="text/javascript">
  <!--
  var expiration=new Date((new Date()).getTime()-10000);
  document.cookie="usercookie="+""+"; expires="+expiration.toGMTString()+"; path="+"/"+";";
  /*
  if(top==self){
    location.href="user_login.php?'.$_REQUEST['loc'].'";
  }else{
    top.location.href=top.location.href.replace(/(#.*)$/,\'\');
  }
  */
  -->
  </script>';
  alert('<img src="images/ok.gif" /> 注销成功，欢迎再来',$loc);

//登录
}elseif($_POST['act']=='login'){


  if(($name=$_POST['username'])=='' || ($password=$_POST['password'])==''){
    err('<img src="images/i.gif" /> 用户名、密码都不能空！');
  }
  if(preg_match('/[\?\+\%\"\'\|\\\]+/',$name)){
    err('<img src="images/i.gif" /> 提交被拒绝！用户名请尽量用汉字、数字、英文及下划线组成，不能含 ? + % " \' | \ ');
  }
  if(strlen($name)<3 || strlen($name)>45){
    err('<img src="images/i.gif" /> 提交被拒绝！用户名——长度请控制在3-45个字符之内（汉字为3字符）');
  }
  if(!preg_match('/^[^\\\]{3,30}$/',$password)){
    err('<img src="images/i.gif" /> 提交被拒绝！密码——长度请控制在3-30个字符之内，请用数字、英文及下划线组成。');
  }
  
  if($_COOKIE['usercookie']){
    $cookie=@explode('|',$_COOKIE['usercookie']);
    err('<img src="images/i.gif" /> 您已经以 用户名：'.$cookie[0].' 登录过了！<br />要想更换用户名登录，请先<a href="run.php?run=user_login&act=logout&loc='.$loc.'">退出</a>');
  }




  $err='';


  //连接mysqkl数据库
  if($db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
    if(!@mysql_select_db($sql['name'],$db)){
      $err='数据库['.$sql['name'].']连接不成功！';
    }
  }else{
    $err='数据库['.$sql['host'].']连接不成功！';
  }

  $nowdate=gmdate('Y/m/d H:i:s',time()+(floatval($web['time_pos'])*3600));
  
  if($err==''){
    mysql_query('SET NAMES '.$sql['char'].'');

    if($_POST['logintype']=='email'){
      $result=mysql_query('SELECT * FROM bbsmember WHERE email="'.$name.'"',$db);
    }else{
      $name=str_replace('.','&#46;',$name);
      $result=mysql_query('SELECT * FROM bbsmember WHERE username="'.$name.'"',$db);
    }
	if($result){
      if($row=@mysql_fetch_assoc($result)){
	    if($row['password']==$password){
          mysql_free_result($result);
          //用js设置cookie，因为前面已有输出
          $row['point']=abs($row['point']+$web['loginadd']);
	      $you['name']=$row['username'];
	      $you['id']=''.$row['point'].'_'.$nowdate.'_'.mt_rand(100000,999999).'';
          $you['power']=$row['power'];
          if($row['power']=='manager'){
	        if(!file_exists('power')){
	          @mkdir('power',0777);
	        }
		    if(file_exists('power')){
              write_file('power/'.urlencode($you['name']).'.php','<?php die(); ?>'.$you['id'].''); //登录密钥记录
		    }
		  }
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
          mysql_query('UPDATE `bbsmember` SET `point`="'.$row['point'].'",`thisdate`="'.$nowdate.'" WHERE `username`="'.$row['username'].'"',$db); //更新最后访问时间
		  if(mysql_affected_rows()>0){
          }else{
            echo mysql_errno() . ": " . mysql_error() . "\n";
          }
          alert('<img src="images/ok.gif" /> 登录成功！欢迎您 '.$you['name'].'',$loc);
	    }else{
          err('<img src="images/i.gif" /> 密码不符！');
	    }
      }else{
        echo '<div id="output">登录失败！原因：<br />1、用户尚未注册；<br />2、表连接不成功或尚未建立</div>';
        base_manager_login($name,$password);
	  }
    }else{
      echo '<div id="output">登录失败！原因：<br />1、用户尚未注册；<br />2、表连接不成功或尚未建立</div>';
      base_manager_login($name,$password);
	}
    mysql_close();
  }else{
    base_manager_login($name,$password);
  }













//找回密码
}elseif($_POST['act']=='foundpassword'){
  if($cookie[0]==$web['manager'] || $cookie[2]=='manager'){
    err('<img src="images/i.gif" /> 禁止以管理员名义进行此项！');
  }
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

  //连接mysqkl数据库
  if(!$db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
    err('<img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！');
  }
  //选择数据库并判断
  if(!@mysql_select_db($sql['name'],$db)){
    err('<img src="images/i.gif" /> 数据库['.$sql['name'].']连接不成功！');
  }
  mysql_query('SET NAMES '.$sql['char'].'');
  
  $result=mysql_query('SELECT * FROM bbsmember WHERE username="'.$_POST['username'].'"',$db);
  if($result){
    if($row=mysql_fetch_assoc($result)){
	  if($row['password_question']!=$_POST['password_question']){
	    err('<img src="images/i.gif" /> 密码问题与注册时所填不符！');
	  }elseif($row['password_answer']!=$_POST['password_answer']){
	    err('<img src="images/i.gif" /> 密码答案不对！');
	  }elseif($row['email']!=$_POST['email']){
	    err('<img src="images/i.gif" /> 邮箱与注册邮箱不符！');
	  }else{
	    err('<img src="images/i.gif" /> 为你找回密码为['.$row['password'].'] 请前往<a href="user_login.php">登录</a>');
	  }

    }else{
      err('<img src="images/i.gif" /> 数据库查无结果！');
    }
  }else{
    err('<img src="images/i.gif" /> 用户名：'.$_POST['username'].' 数据库查无结果！');
  }

  mysql_close();






}else{
//   err('<img src="images/i.gif" /> 参数出错！');
}


?>



