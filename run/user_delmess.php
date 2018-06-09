<?php
//发送短信
require('inc/function/confirm_login.php');

if(confirm_login()==false){
  err('<img src="images/i.gif" /> 提交被拒绝！<a href="user_reg.php"><b>先去注册（非常简单）</b></a>或<a href="user_login.php"><b>登录</b></a>，以获得发表权限');
}

$_REQUEST['id']=array_unique((array)$_REQUEST['id']);

if(count($_REQUEST['id'])<1)
  err('<img src="images/i.gif" /> 参数出错！<br />问题分析：<br />1、您可能未选择<br />2、参数传递出错');


//连接mysqkl数据库
if(!$db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
  err('<img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！');
}
//选择数据库并判断
if(!@mysql_select_db($sql['name'],$db)){
  err('<img src="images/i.gif" /> 数据库['.$sql['name'].']连接不成功！');
}
mysql_query('SET NAMES '.$sql['char'].'');

if($result=mysql_query("SELECT other2,other3 FROM bbsmember WHERE username='".$cookie[0]."'",$db)){ //结果集
  if($row=mysql_fetch_assoc($result)){
    mysql_free_result($result);
    $messlist=explode("\n",trim($row['other2']));
	$newnum=$row['other3'];
	unset($row);
	$messlist=preg_replace('/^('.implode('|',$_REQUEST['id']).')\|.+/','',$messlist);
    $messlist=array_filter($messlist);
        $read_arr=preg_grep("/\|\s*$/",$messlist);
		$read_n=count($read_arr);
		//echo count($read_n);die;
        if($read_n!=$newnum){
          $eval=',other3=".$read_n."'; //更新未读短信
		}
    $newline="".@implode("\n",$messlist)."\n";



    //mysql_query("ALTER TABLE bbsmember CHANGE `other2` `other2` TEXT");
    mysql_query("ALTER TABLE `bbsmember` MODIFY COLUMN `other2` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL");
    mysql_query("UPDATE bbsmember SET other2='".$newline."'".$eval." WHERE username='".$cookie[0]."'");
    $access=mysql_affected_rows();
    if($access>0){
      alert('<img src="images/ok.gif" /> 短信删除成功！','user_messbox.php');
    }else{
      echo mysql_errno() . ": " . mysql_error() . "\n";
      err('<img src="images/i.gif" /> 短信删除不成功！');
    }
  }else{
    err('<img src="images/i.gif" /> 数据库无'.$cookie[0].'用户或无短信记录！');
  }
}else{
  err('<img src="images/i.gif" /> 数据库无'.$cookie[0].'用户或无短信记录！');
}

mysql_close();



?>



