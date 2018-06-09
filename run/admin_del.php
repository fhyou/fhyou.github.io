<?php

require('inc/function/confirm_manager.php');
if(confirm_manager()==false)
  err('<img src="images/i.gif" /> 请以管理员身份重新<a href="user_login.php">登录</a>！');
$_REQUEST['id']=array_unique((array)$_REQUEST['id']);
$dataname=$_REQUEST['dataname'];

if(count($_REQUEST['id'])<1)
  err('<img src="images/i.gif" /> 参数出错！<br />问题分析：<br />1、您可能未选择<br />2、参数传递出错');



if($dataname=='data/ip'){
  foreach($_REQUEST['id'] as $each){
    if(file_exists('data/ip/'.$each)){
      @unlink('data/ip/'.$each);
    }
  }
  alert('<img src="images/ok.gif" /> 执行成功！','admin_ip.php');
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

$limit=$_REQUEST['limit'];

if($dataname=='bbsmember'){
  $eval='$result=mysql_query(\'SELECT username,power FROM bbsmember WHERE id="\'.$each.\'"\');
  if($row=mysql_fetch_assoc($result)){
    if($row["username"]==$web["manager"] && $row["power"]=="manager"){
	  err(\'<img src="images/i.gif" /> \'.$row[\'username\'].\'该用户为基本管理员！\');
	}
  }';
  if($limit=='t' || $limit=='r' || $limit=='u' || $limit=='a'){
    $eval.='
	$nowpower=$limit=="a"?"a":str_replace($limit,"",$row["power"]).$limit;
    mysql_query(\'UPDATE \'.$dataname.\' SET power="\'.$nowpower.\'" WHERE id="\'.$each.\'"\');
    ';
  }elseif($limit=='tt' || $limit=='rr' || $limit=='uu' || $limit=='aa'){
    $eval.='
	$nowpower=$limit=="aa"?"":str_replace(substr($limit,0,1),"",$row["power"]);
    mysql_query(\'UPDATE \'.$dataname.\' SET power="\'.$nowpower.\'" WHERE id="\'.$each.\'"\');
    ';
  }elseif($limit=='del'){
    $eval.='
    mysql_query(\'DELETE FROM \'.$dataname.\' WHERE id="\'.$each.\'"\');
    ';
  }elseif($limit=='addadmin'){
    $eval.='
    mysql_query(\'UPDATE \'.$dataname.\' SET power="manager" WHERE id="\'.$each.\'"\');
    ';
  }elseif($limit=='deladmin'){
    $eval.='
    mysql_query(\'UPDATE \'.$dataname.\' SET power="" WHERE id="\'.$each.\'"\');
    ';
  }elseif(abs($_POST['topcount'])>0 && abs($_POST['topdate'])>0){
    $topdate=abs($_POST['topdate'])>=$web['top_expires']?abs($_POST['topdate']):$web['top_expires'];
    $gotodate=gmdate('YmdHis',time()+(floatval($web['time_pos'])*3600)+$topdate*24*60*60);
    $eval.='
    mysql_query(\'UPDATE \'.$dataname.\' SET topcount="\'.abs($_POST[\'topcount\']).\'",topdate="\'.$gotodate.\'" WHERE id="\'.$each.\'"\');
    ';
  }elseif($limit=='ctop'){
    $eval.='
    mysql_query(\'UPDATE \'.$dataname.\' SET topcount="",topdate="" WHERE id="\'.$each.\'"\');
    ';
  }elseif(abs($_POST['addpoint'])>0){
    $eval.='
    mysql_query(\'UPDATE \'.$dataname.\' SET point=point+\'.abs($_POST[\'addpoint\']).\' WHERE id="\'.$each.\'"\');
    ';
  }elseif(abs($_POST['delpoint'])>0){
    $eval.='
    mysql_query(\'UPDATE \'.$dataname.\' SET point=point-\'.abs($_POST[\'delpoint\']).\' WHERE id="\'.$each.\'" AND point>=\'.abs($_POST[\'delpoint\']).\'\');
    ';
  }else{
    err('<img src="images/i.gif" /> 命令错误！');
  }
}elseif($dataname=='bbsreply'){
  $eval='
  mysql_query(\'UPDATE \'.$dataname.\' SET text="**** **** 此条已删" WHERE id="\'.$each.\'"\');
  ';
}elseif($dataname=='bbslistdata'){
  if($limit=='ess'){
    $eval='
    mysql_query(\'UPDATE \'.$dataname.\' SET good="good" WHERE id="\'.$each.\'"\');
    ';
  }elseif($limit=='cess'){
    $eval='
    mysql_query(\'UPDATE \'.$dataname.\' SET good="" WHERE id="\'.$each.\'"\');
    ';
  }elseif($limit=='top'){
    $gotodate=gmdate('YmdHis',time()+(floatval($web['time_pos'])*3600)+$web['top_expires']*24*3600);
    $eval='
    mysql_query(\'UPDATE \'.$dataname.\' SET topdate="\'.$gotodate.\'" WHERE id="\'.$each.\'"\');
    ';
  }elseif($limit=='ctop'){
    $nowdate=gmdate('YmdHis',time()+(floatval($web['time_pos'])*3600));
    $eval='
    mysql_query(\'UPDATE \'.$dataname.\' SET topdate="\'.$nowdate.\'" WHERE id="\'.$each.\'"\');
    ';
  }elseif($limit=='del'){
    $delmember_arr=array();
    /*if($each<=5){
	  $err_.=\'<br />&#73;&#68;&#21069;&#53;&#26465;&#30340;&#20449;&#24687;&#20026;&#31243;&#24207;&#20316;&#32773;&#23459;&#20256;&#24086;&#65292;&#35831;&#20445;&#30041;&#65292;&#35831;&#25903;&#25345;&#65292;&#24863;&#35874;&#20351;&#29992;&#35813;&#31243;&#24207;&#65292;&#27426;&#36814;&#20809;&#20020;&#104;&#116;&#116;&#112;&#58;&#47;&#47;&#100;&#111;&#119;&#110;&#108;&#111;&#97;&#100;&#46;&#49;&#54;&#50;&#49;&#48;&#48;&#46;&#99;&#111;&#109;&#26356;&#26032;&#21319;&#32423;&#65281;\';
	}else{*/
    $eval='
    $result=mysql_query(\'SELECT author_ip FROM \'.$dataname.\' WHERE id="\'.$each.\'"\');
    $row=mysql_fetch_assoc($result);
	$delmember_arr[$row[\'author_ip\']]+=10;
    mysql_free_result($result);
    unset($row);
    mysql_query(\'DELETE FROM \'.$dataname.\' WHERE id="\'.$each.\'"\');
    ';
  }elseif($limit=='chanto'){
    require('inc/set_area.php');
    if(!preg_match('/^\d+\_\d+$/',$_POST['to_id'])){
	  err('<img src="images/i.gif" /> 目标栏目ID不存在或出错');
	}
    list($a_id,$c_id)=@explode('_',$_POST['to_id']);
	if($web['area'][$a_id][$c_id]==NULL){
	  err('<img src="images/i.gif" /> 目标栏目不存在或出错');
	}
    $eval.='
    mysql_query(\'UPDATE \'.$dataname.\' SET area_id="\'.$_POST[\'to_id\'].\'" WHERE id="\'.$each.\'"\');
    ';
  }else{
    err('<img src="images/i.gif" /> 命令错误！');
  }
}else{
  err('<img src="images/i.gif" /> 数据库名错误！');
}




foreach($_REQUEST['id'] as $each){
  if(is_numeric($each)){
    eval($eval);
  }
}
//print_r($delmember_arr);
//die();
if(mysql_affected_rows()>0){
  if($delmember_arr){
    foreach($delmember_arr as $member=>$delpoint){
	  mysql_query('UPDATE bbsmember SET point=point-'.$delpoint.' WHERE username="'.$member.'" AND point>='.$delpoint.'');
	}
  }
  alert('<img src="images/ok.gif" /> 执行成功！',$_SERVER['HTTP_REFERER']);
}else{
  err('<img src="images/i.gif" /> 执行数据不成功！');

}
mysql_close();


?>



