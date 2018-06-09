<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="0">
<title>载入预览</title>
</head>
<body>
<?php include("menubody.php"); ?>
	
	<div class="rig_lm01">
		<div class="title">
			<img src="../images/listicon.jpg" class="icon" style="padding-top: 3px;">
				<h2>课后讨论</h2>
		
		</div>
	</div>

<script language="javascript" type="text/javascript">
<!--
    var pM=parent.document.getElementById('show<?php echo $_REQUEST['id']; ?>');//
	if(pM!=null){
      try{
        pM.innerHTML='<div id="zhaiyao">\
<?php
require('inc/set.php');
require('inc/set_sql.php');
require('inc/function/get_date.php');
require('inc/function/confirm_login.php');

if(confirm_login()){
//连接mysqkl数据库
  if($db=@mysql_connect($sql['host'],$sql['user'],$sql['pass'])){
    if(@mysql_select_db($sql['name'],$db)){
      mysql_query('SET NAMES '.$sql['char'].'');
      if($result=mysql_query('SELECT other2,other3 FROM bbsmember WHERE username="'.$cookie[0].'"',$db)){ //结果集
        $row=mysql_fetch_assoc($result);
        mysql_free_result($result);
	    //echo $row['other2'];die;
        $messlist=array_filter(explode("\n",trim($row['other2'])));
	    $n=count($messlist); //总记录数
        if($n>0){
          foreach($messlist as $key=>$line){ //时间|来信人|主题|内容|||已读状态
		    if(substr($line,0,14)==$_REQUEST['id']){
		      $row=@explode("|",$line);
              $text=$row[3];
			  $read=$row[6];
		      break;
			}
		  }
	    }
		$text=$text?$text:'未取到数据';
		if(isset($read) && $read!='o'){
		  $messlist[$key]=preg_replace('/\|\s*$/','|o',$messlist[$key]);
          $newline="".@implode("\n",$messlist)."\n";
		  $newnum=abs($row["other3"])-1>0?abs($row["other3"])-1:0;
          //mysql_query("ALTER TABLE bbsmember CHANGE `other2` `other2` TEXT");
		  mysql_query("ALTER TABLE `bbsmember` MODIFY COLUMN `other2` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL");
          mysql_query("UPDATE bbsmember SET other2='".$newline."',other3=".$newnum." WHERE username='".$cookie[0]."'");
		}
	  }else{
        $text.='<img src="images/i.gif" /> 数据库查无数据！';
	  }
    }else{
      $text.='<img src="images/i.gif" /> 数据库查无记录！';
    }
    mysql_close();
  }else{
    $text='<img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！';
  }
}else{
  $text='<img src="images/i.gif" /> 数据库['.$sql['host'].']连接不成功！';
}

echo $text;

?></div>';
        parent.document.getElementById('read<?php echo $_REQUEST['id']; ?>').innerHTML='已读';
      }catch(err){
	    pM.innerHTML+='<br />　　<font class="zhaiyao">预览载入失败！</font>';
	  }
	}else{
	  pM.innerHTML+='<br />　　<font class="zhaiyao">预览载入失败！</font>';
	}
	parent.window.scrollTo(0,<?php echo $_REQUEST['t']; ?>);
-->
</script>

    
</div>
</div>
</body>
</html>
