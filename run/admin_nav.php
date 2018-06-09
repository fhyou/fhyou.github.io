<?php


  //页脚
require('inc/function/confirm_manager.php');
require('inc/function/filter.php');
require('inc/function/write_file.php');
  if(!(confirm_manager()==true && $cookie[0]==$web['manager'])){
    err('<img src="images/i.gif" /> 该命令必须以基本管理员'.$web['manager'].'身份登录！请重登录');
}


$file=$_GET['act']=='announce'?'inc/require/announce.txt':'inc/require/nav.txt';
if($_POST['filter']=='yes'){
  $content=''.filter2($_POST['content']).'';
}else{
  if(!get_magic_quotes_gpc()){
    $content=addslashes($_POST['content']);
  }else{
    $content=$_POST['content'];
  }
  $content=stripslashes($content);
}

write_file($file,$content);
alert('<img src="images/ok.gif" /> 发布成功！','admin_nav.php');


?>



