<?php


require('inc/function/confirm_manager.php');
require('inc/function/write_file.php');


if(!(confirm_manager()==true && $cookie[0]==$web['manager'])){
  err('<img src="images/i.gif" /> 该命令必须以基本管理员'.$web['manager'].'身份登录！请重登录');
}

function filter($text){
  if(!empty($text) && preg_match('/[\s\r\n\"\'\\\]+/',$text)){
    err('<img src="images/i.gif" /> 所填内容不能含 空格 及 \' " \ ');
  }
  $text=stripslashes($text);
  $text=htmlspecialchars($text);
  return $text;
}

$_POST=@array_map('filter',$_POST);

if($_POST['dbtype']==''){
  err('<img src="images/i.gif" /> 服务器类型不能留空！');
}
if($_POST['dbhost']==''){
  err('<img src="images/i.gif" /> 服务器地址不能留空！');
}
if($_POST['dbname']==''){
  err('<img src="images/i.gif" /> 数据库名不能留空！');
}
if($_POST['dbuser']==''){
  err('<img src="images/i.gif" /> 数据库用户名不能留空！');
}
if($_POST['dbpsw']==''){
  err('<img src="images/i.gif" /> 数据库密码不能留空！');
}

//连接mysqkl数据库
if(!$db=@mysql_connect($_POST['dbhost'],$_POST['dbuser'],$_POST['dbpsw'])){
  echo mysql_errno() . ": " . mysql_error() . "\n";
  err('<img src="images/i.gif" /> 数据库['.$_POST['dbhost'].']连接不成功！请检查用户名或密码。如果是新建请点选前面“创建新数据库”');
}
if($_POST['create']==1){
  @mysql_query('CREATE DATABASE '.$_POST['dbname'].'',$db); //如果数据库不存在则创建
}
//选择数据库并判断
if(!@mysql_select_db($_POST['dbname'],$db)){
  err('<img src="images/i.gif" /> 数据库['.$_POST['dbname'].']连接不成功！请检查用户名或密码。如果是新建请点选前面“创建新数据库”');
}
mysql_query('SET NAMES '.$sql['char'].'');

write_file('inc/set_sql.php','<?php
//数据库配置文件
$sql[\'type\']=\''.$_POST['dbtype'].'\';
$sql[\'host\']=\''.$_POST['dbhost'].'\'; //localhost
$sql[\'user\']=\''.$_POST['dbuser'].'\'; //root
$sql[\'pass\']=\''.$_POST['dbpsw'].'\';
$sql[\'name\']=\''.$_POST['dbname'].'\';
$sql[\'char\']=\''.$_POST['dbchar'].'\'; //
?>');




err('<img src="images/ok.gif" /> 设置数据库成功！点击<a href="run.php?run=sql_table_set"><font color="red">建立数据库表</font></a>');


?>



