<?php
require('inc/set.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>搜索 - <?php echo $web['sitename']; ?><?php echo $web['code_author']; ?></title>
<meta name="Description" content="<?php echo $web['description']; ?>" />
<meta name="keywords" content="<?php echo $web['keywords']; ?>" />
<link rel="stylesheet" type="text/css" href="css/<?php echo $web['cssfile']; ?>/style.css">

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
  <div class="area_title"><img src="css/<?php echo $web['cssfile']; ?>/area_title.gif" align="absmiddle" /> <a href="forum.php">课后讨论首页</a> &gt; 搜索</div>
</div>

<table class="maintable" height="400" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="left_menu" valign="top"><h4>请输入关键字</h4><?php

//处理关键字
function process_input(){
  $str=preg_replace('/[^\x80-\xffa-zA-Z0-9]+/',' ',$_GET['keyword']);//\u4e00-\u9fa5
  $str=trim($str);
  $str=str_replace(' ','|',$str);
  return $str;
}
//加亮标题中的关键字
function red_keyword_title($str){
  global $keyword;
  return @preg_replace('/('.$keyword.')/i','<span style="color:#FF6600">$1</span>',$str);
}
//加亮标题中的关键字
function red_keyword_text($str){
  global $keyword;
  if(preg_match('/('.$keyword.')/i',$str,$matches)){
    $str=cutstr(strstr($str,$matches[1]),300);
  }
  return @preg_replace('/('.$keyword.')/i','<span style="color:#FF6600">$1</span>',$str);
}

function get_option(){
  global $web;
  require('inc/set_area.php');
  $option.='<option value="all" id="option_all">所有类目</option>';
  foreach((array)$web['area'] as $i=>$area){
    $option.='<option value="'.$i.'" id="option_'.$i.'">　'.$area[0].'</option>';
    foreach((array)$area as $j=>$class){
      if($j!=0){
        $option.='<option value="'.$i.'_'.$j.'" id="option_'.$i.'_'.$j.'">　　'.$class.'</option> ';
      }
    }
  }
  return $option;
}

require('inc/set_sql.php');
require('inc/function/get_date.php');
require('inc/function/cutstr.php');
$come_id=$_REQUEST['area_id'];
if($_REQUEST['area_id']){
  if(preg_match('/^\d+\_\d+$/',$_REQUEST['area_id'])){
    list($area_id,$class_id)=@explode('_',$_REQUEST['area_id']);
	if($web['area'][$area_id][$class_id]==NULL){
      $come_id='';
    }
  }elseif(is_numeric($_REQUEST['area_id'])){
	if($web['area'][$_REQUEST['area_id']]==NULL){
      $come_id='';
	}
  }else{
    $come_id='';
  }
}else{
  $come_id='';
}

$keyword=process_input();
$key=str_replace('|','%',$keyword);
?><form action="search.php?action=search" method="get"><input type="text" id="keyword" name="keyword" style="width:200px;" onfocus="if(this.value=='关键词'){this.value=''}" value="<?php echo $keyword?$_GET['keyword']:'关键词'; ?>" /><br />
  <select name="area_id">
    <?php echo get_option(); ?>
  </select>
  <input class="submit" type="submit" name="submit" value="搜索" />
  <input type="hidden" name="action" value="search" /></form>
<br />
<br />
<br />
<br />

    </td>
    <td width="100" valign="middle" align="right" class="pass"> 》</td>
    <td class="m_r" valign="top"><h4>结果&nbsp;</h4>
	<div class="re_author">搜索结果</div>
<?php

if($keyword==''){ //检验关键字
  die('<div class="li"><img src="images/i.gif" /> 请输入字、句等有效的查询字符！</div></body></html>');
}
if(($strlength=strlen($keyword)) && ($strlength>100 || $strlength<3)){
  die('<div class="li"><img src="images/i.gif" /> 关键字长度请大于等于3且少于100字符！</div></body></html>');
}
if($db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
  if(@mysql_select_db($sql['name'],$db)){
    mysql_query('SET NAMES '.$sql['char'].'');
    if($result=mysql_query('SELECT id,title,text,author_ip,date FROM bbslistdata WHERE area_id LIKE "'.$come_id.'%" AND (title LIKE "%'.$key.'%" || text LIKE "%'.$key.'%") ORDER BY topdate',$db)){ //结果集
      $n=mysql_num_rows($result); //总记录数
      require('inc/function/get_page.php');
      $p=get_page($n); //页数
      $seek=$n-$web['pagesize']*($p-1);
      $end=$seek-$web['pagesize']>0?$seek-$web['pagesize']:0;
	  for($i=$seek-1;$i>=$end;$i--){
        if(mysql_data_seek($result,$i)){
          if($row=mysql_fetch_assoc($result)){
            $text_result.='
<h4><a href="article.php?id='.$row['id'].'" target="_blank">'.red_keyword_title($row['title']).'</a></h4>
<div class="text">'.red_keyword_text($row['text']).'<br />
<div style="color:green">作者：'.(strstr($row['author_ip'],'.')?'匿名用户('.$row['author_ip'].')':'<a href="user_card.php?username='.urlencode($row['author_ip']).'" target="_blank" title="点击查看发布人名片">'.$row['author_ip'].'<img src="images/card.gif" /></a>').' - 发布日期：'.$row['date'].'</div></div>

';
          }
        }
      }
      mysql_free_result($result);
	}else{
      $err.='<div class="li"><img src="images/i.gif" /> 表连接不成功或尚未建立！</div>';
	}
  }else{
    $err.='<div class="li"><img src="images/i.gif" /> 指定的数据库连接不成功！</div>';
  }
  mysql_close();
}else{
  $err.='<div class="li"><img src="images/i.gif" /> 数据库连接失败！</div>';
}

if($text_result){
  $text.=$text_result;
  $text.=get_page_foot($p,$n,'&keyword='.urlencode($keyword).'');
}else{
  $text.='<div class="li"><img src="images/i.gif" /> 暂未搜索到数据</div>'.$err.'';
}

echo $text;




?>
    </td>
  </tr>
</table>
<br />
<br />
<br />
<br />
<script language="JavaScript" type="text/javascript">
<!--
try{
  document.getElementById("option_<?php echo $_REQUEST['area_id']; ?>").selected='selected';
}catch(err){
}
-->
</script>
</div>
</div>
</body>
</html>
