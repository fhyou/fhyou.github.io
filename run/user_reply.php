<?php



require('inc/function/user_ip.php');


if($_REQUEST['id']=='' || !is_numeric($_REQUEST['id'])){
  err('<img src="images/i.gif" /> ID参数为空或错误！');
}

require('inc/function/confirm_login.php');
if(confirm_login()==true){
  if($cookie[2]!='manager' && (strstr($cookie[2],'a') || strstr($cookie[2],'r'))){ //a所有，t主题
    err('<img src="images/i.gif" /> 提交被拒绝！您已经被限制评论或回复，具体请询问管理员，以获得权限');
  }
  $author=$cookie[0];
}else{
  require('inc/function/chk_ip.php');
  $author=user_ip();
  chk_ip($author);
}

/* 发表*/
require('inc/function/cutstr.php');
require('inc/function/filter.php');
$web['re_wordcount']=(is_numeric($web['re_wordcount']) && $web['re_wordcount']>0)?$web['re_wordcount']:5000;
$content=filter1($_POST['content']);
//$content=filter2(cutstr($_POST['content'],$web['re_wordcount']));
//die($content);
if($content==''){
  err('<img src="images/i.gif" /> 您提交的内容不能留空！');
}
$date=gmdate('Y/m/d H:i:s',time()+(floatval($web['time_pos'])*3600));

//题目ID|内容|日期|会员或IP
//连接mysqkl数据库
if(!$db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
  err('<img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！');
}
//选择数据库并判断
if(!@mysql_select_db($sql['name'],$db)){
  err('<img src="images/i.gif" /> 数据库['.$sql['name'].']连接不成功！');
}
mysql_query('SET NAMES '.$sql['char'].'');


/*判断垃圾信息重复*/
$cf=0;
if($result=@mysql_query('SELECT text FROM bbsreply WHERE r_id="'.$_REQUEST['id'].'" AND author_ip="'.$author.'"',$db)){
  $yijing=mysql_num_rows($result)+1; //你已回复记录数
  while($row=mysql_fetch_array($result)){
	similar_text($row['text'],$content,$percent2);
    if($percent2>=80){
	  $cf=1;
	  break;
	}
  }
  /*限制发布数量*/
  mysql_free_result($result);
  $web['reply_mx']=$web['reply_mx']?abs($web['reply_mx']):30;
  if($yijing>=$web['reply_mx']){
    err('<img src="images/i.gif" /> 该主题你回复得太多了，已达到'.$yijing.'条，歇歇吧！');
  }
  unset($row);
}
if($cf==1 && ($cookie[2]!='manager')){
  err('<img src="images/i.gif" /> 你已经发布过相似的文章！禁止信息重复');
}




if(abs($web['re_update'])==1){
  $topdate=gmdate('YmdHis',time()+(floatval($web['time_pos'])*3600));
  if($result=mysql_query('SELECT * FROM bbslistdata WHERE id="'.$_REQUEST['id'].'"',$db)){ //结果集
    $row=mysql_fetch_assoc($result);
    mysql_free_result($result);
  }  
  if($row['topdate']<$topdate){
    mysql_query('UPDATE bbslistdata SET reply=reply+1,topdate="'.$topdate.'" WHERE id="'.$_REQUEST['id'].'"',$db);
  }else{
    mysql_query('UPDATE bbslistdata SET reply=reply+1 WHERE id="'.$_REQUEST['id'].'"',$db);
  }
}else{
  mysql_query('UPDATE bbslistdata SET reply=reply+1 WHERE id="'.$_REQUEST['id'].'"',$db);
}


if(mysql_affected_rows()>0){
  mysql_query("INSERT INTO `bbsreply`(`r_id`,`text`,`date`,`author_ip`) values('".$_REQUEST['id']."','".$content."','".$date."','".$author."')");
  $the_id=mysql_insert_id();
  mysql_close();
  alert('<img src="images/ok.gif" /> 评论回复成功！','article.php?id='.$_REQUEST['id'].'&p='.$_REQUEST['p'].'#re_'.($the_id+1).'');
}else{
  mysql_close();
  err('<img src="images/i.gif" /> 评论回复不成功！可能是查不到此ID数据！可能传递出错');
}


?>



