<?php

require('inc/function/confirm_manager.php');
require('inc/function/get_date.php');
if(!(confirm_manager()==true && $cookie[0]==$web['manager'])){
  err('<img src="images/i.gif" /> 该命令必须以基本管理员'.$web['manager'].'身份登录！请重登录');
}


//连接mysqkl数据库
if(!$db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
  echo mysql_errno() . ": " . mysql_error() . "\n";
  err('<img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！');
}
//选择数据库并判断
if(!@mysql_select_db($sql['name'],$db)){
  @mysql_query('CREATE DATABASE IF NOT EXISTS '.$sql['name'].'',$db); //如果数据库不存在则创建
  if(!@mysql_select_db($sql['name'],$db)){
    err('<img src="images/i.gif" /> 数据库['.$sql['name'].']连接不成功！');
  }
}
if(version_compare(mysql_get_server_info(), '4.1.0', '>=')){
  $char=' DEFAULT CHARSET='.$sql['char'].'';
}

mysql_query('SET NAMES '.$sql['char'].'');

//判断表是不是已存在
if($result=@mysql_query('SELECT * FROM bbslistdata',$db)){
  $out.='<br />信息库表[bbslistdata]已存在！是否：<a href="run.php?run=sql_table_del&table=bbslistdata" onclick="return confirm(\'确定删除信息库表[bbslistdata]么？\')" target="_blank">删除信息库表</a><br />';
  mysql_free_result($result);
}else{
  //建表语句（列表）
  //题目|内容|图|影片|附件|日期|浏览|类目|会员或IP|GUEST密码|置顶截止日期|精华
  $topdate=gmdate('YmdHis',time()+(floatval($web['time_pos'])*3600));
  $date=get_date($topdate,10);
  $table_born='CREATE TABLE `bbslistdata`(
`id` int(10) NOT NULL auto_increment,
`title` varchar(200) NOT NULL default "",
`text` text NOT NULL,
`pic` varchar(40) NOT NULL default "",
`fil` varchar(40) NOT NULL default "",
`enc` varchar(40) NOT NULL default "",
`date` varchar(40) NOT NULL default "",
`reply` int(10) NOT NULL default 0,
`views` int(10) NOT NULL default 0,
`area_id` varchar(40) NOT NULL default "",
`author_ip` varchar(40) NOT NULL default "",
`guestpsw` varchar(40) NOT NULL default "",
`topdate` varchar(40) NOT NULL default "",
`good` varchar(40) NOT NULL default "",
`other1` varchar(200) NOT NULL default "",
`other2` varchar(200) NOT NULL default "",
`other3` varchar(200) NOT NULL default "",
`other4` varchar(200) NOT NULL default "",
PRIMARY KEY (`id`)
) ENGINE=MyISAM'.$char.';';


  if(mysql_query($table_born)){ //创建表并判断
    mysql_query($table_into);
    $out.='<br />信息库表[bbslistdata]创建成功！';
  }else{
    echo mysql_errno() . ": " . mysql_error() . "\n";
    $out.='<br />信息库表[bbslistdata]创建失败！';
  }
}
unset($table_into,$table_into);





if($result=@mysql_query('SELECT * FROM bbsreply',$db)){
  $out.='<br />回帖表[bbsreply]已存在！是否：<a href="run.php?run=sql_table_del&table=bbsreply" onclick="return confirm(\'确定删除回帖表[bbsreply]么？\')" target="_blank">删除回帖表</a><br />';
  mysql_free_result($result);
}else{
  //建表语句（评论或回复）
  //题目ID|内容|日期|会员或IP
  $date=gmdate('Y/m/d H:i:s',time()+(floatval($web['time_pos'])*3600));
  $table_born='CREATE TABLE `bbsreply`(
`id` int(10) NOT NULL auto_increment,
`r_id` varchar(40) NOT NULL default "",
`text` text NOT NULL,
`date` varchar(40) NOT NULL default "",
`author_ip` varchar(40) NOT NULL default "",
`other1` varchar(200) NOT NULL default "",
`other2` varchar(200) NOT NULL default "",
`other3` varchar(200) NOT NULL default "",
`other4` varchar(200) NOT NULL default "",
PRIMARY KEY (`id`)
) ENGINE=MyISAM'.$char.';';
  $table_into='INSERT INTO `bbsreply`(`id`,`r_id`,`text`,`date`,`author_ip`) values
(1,"1","欢迎光临","'.$date.'","'.$web['manager'].'");

';

  if(mysql_query($table_born)){ //创建表并判断
    mysql_query($table_into);
    $out.='<br />回帖表[bbsreply]创建成功！';
  }else{
    echo mysql_errno() . ": " . mysql_error() . "\n";
    $out.='<br />回帖表[bbsreply]创建失败！';
  }
}
unset($table_into,$table_into);



if($result=@mysql_query('SELECT * FROM bbsmember',$db)){
  $out.='<br />用户表[bbsmember]已存在！是否：<a href="run.php?run=sql_table_del&table=bbsmember" onclick="return confirm(\'确定删除用户表[bbsmember]么？\')" target="_blank">删除用户表</a><br />';
  mysql_free_result($result);
}else{
  //建表语句（会员）
  //ID|用户名|密码|邮箱|密码问题|密码答案|权力|积分|发表数量|注册日期|最后访问
  //真名|性别|年龄|手机|座机|公司|地址|邮编|QQ|网址|置顶条数|置顶限期|other1日发表限量|other2短信箱|other3新短信数
  $table_born='CREATE TABLE `bbsmember`(
`id` int(10) NOT NULL auto_increment,
`username` varchar(200) NOT NULL default "",
`password` varchar(200) NOT NULL default "",
`email` varchar(200) NOT NULL default "",
`password_question` varchar(200) NOT NULL default "",
`password_answer` varchar(40) NOT NULL default "",
`power` varchar(40) NOT NULL default "",
`point` int(10) NOT NULL default 0,
`writecount` int(10) NOT NULL default 0,
`regdate` varchar(40) NOT NULL default "",
`thisdate` varchar(40) NOT NULL default "",
`realname` varchar(40) NOT NULL default "",
`sex` varchar(40) NOT NULL default "",
`birthday` varchar(40) NOT NULL default "",
`handtel` varchar(20) NOT NULL default "",
`hometel` varchar(20) NOT NULL default "",
`company` varchar(200) NOT NULL default "",
`address` varchar(200) NOT NULL default "",
`zip` varchar(20) NOT NULL default "",
`qq` varchar(20) NOT NULL default "",
`sign` varchar(200) NOT NULL default "",
`topcount` varchar(20) NOT NULL default "",
`topdate` varchar(40) NOT NULL default "",
`other1` varchar(200) NOT NULL default "",
`other2` text NOT NULL,
`other3` varchar(200) NOT NULL default "",
`other4` varchar(200) NOT NULL default "",
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1'.$char.';';
  $table_into='INSERT INTO `bbsmember`
(`id`,`username`,`password`,`email`,`power`,`point`,`writecount`,`regdate`,`thisdate`,`other2`) values
(1,"'.$web['manager'].'","'.$web['password'].'","162100.com@163.com","manager",10000,0,"'.($thisdate=gmdate('Y/m/d H:i:s',time()+(floatval($web['time_pos'])*3600))).'","'.$thisdate.'","");

';

  if(mysql_query($table_born)){ //创建表并判断
    mysql_query($table_into);
    $out.='<br />用户表[bbsmember]创建成功！';
  }else{
  echo mysql_errno() . ": " . mysql_error() . "\n";
    $out.='<br />用户表[bbsmember]创建失败！';
  }
}
unset($table_into,$table_into);



err('<img src="images/i.gif" /> '.$out.'<a href="admin.php">进入admin.php</a>');


?>



