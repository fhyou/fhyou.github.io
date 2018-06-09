<?php
//发送短信
require('inc/function/confirm_login.php');

if(confirm_login()==false){
  err('<img src="images/i.gif" /> 提交被拒绝！<a href="user_reg.php"><b>先去注册（非常简单）</b></a>或<a href="user_login.php"><b>登录</b></a>，以获得发表权限');
}

$user=@explode('/',$_REQUEST['username']);
$user=array_filter((array)$user);
$user=array_unique((array)$user);
if(count($user)<1){
  err('<img src="images/i.gif" /> 参数出错！<br />问题分析：1、您可能未填写收信人；2、参数传递出错');
}


/* 发表*/

require('inc/function/filter.php');
$subject=filter1($_POST['subject']);
server_sbj($subject); //主题检测
$content=filter2($_POST['content']);
$content=preg_replace('/(<(img|a) [^>]+)'.preg_quote($web['path'],'/').'((data\/upload|images)\/[^>]+>)/i','${1}${3}',$content);
server_chk($content,$web['mess_wordcount']); //内容检测
$date=gmdate('YmdHis',time()+(floatval($web['time_pos'])*3600));

//时间|来信人|主题|内容|||已读状态
$line="".$date."|".$cookie[0]."|".$subject."|".$content."||| \n";


//连接mysqkl数据库
if(!$db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
  err('<img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！');
}
//选择数据库并判断
if(!@mysql_select_db($sql['name'],$db)){
  err('<img src="images/i.gif" /> 数据库['.$sql['name'].']连接不成功！');
}
mysql_query('SET NAMES '.$sql['char'].'');

$step=0;
foreach($user as $username){
  if($result=mysql_query('SELECT other2,other3 FROM bbsmember WHERE username="'.$username.'"',$db)){ //结果集
    if($row=mysql_fetch_assoc($result)){
      mysql_free_result($result);
      $messlist=array_filter(explode("\n",trim($line.$row['other2'])));
      $messlist=@array_slice($messlist,0,$web['mess_count']);
      $newline="".@implode("\n",$messlist)."\n";
	  //echo abs($row["other3"]);die;
      //mysql_query("ALTER TABLE bbsmember CHANGE `other2` `other2` TEXT");
      mysql_query("ALTER TABLE `bbsmember` MODIFY COLUMN `other2` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL");
      mysql_query("UPDATE bbsmember SET other2='".$newline."',other3=".(abs($row["other3"])+1)." WHERE username='".$username."'");
      if(mysql_affected_rows()>0) $step++;
    }else{
      $err.='<br />数据库无'.$username.'用户或无短信记录！';
    }
  }else{
    $err.='<br />数据库无'.$username.'用户或无短信记录！';
  }
  unset($row,$username,$messlist,$newline);
}

if($step>0){
  alert('<img src="images/ok.gif" /> 短信发布成功！'.$err.'','user_mess.php?username='.urlencode($_REQUEST["username"]).'');
}else{
  err('<img src="images/i.gif" /> 短信发布不成功！');
}
mysql_close();



?>



