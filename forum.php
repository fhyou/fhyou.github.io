<?php
require ('inc/set.php');
require ('inc/set_sql.php');
require ('inc/set_area.php');
require ('inc/function/confirm_login.php');
require ('inc/function/user_class.php');

// 登录否？
if (confirm_login()) {
    $you = explode('_', $cookie[1]);
    $writepower = '发表帖子';
} else {
    $writepower = '匿名发表';
}

function getclass($area_id)
{
    global $web, $option, $manage;
    if (is_array($web['area'][$area_id]) && count($web['area'][$area_id]) > 1) {
        $text .= '<font color=#FF5500>细分栏目：</font>';
        foreach ((array) $web['area'][$area_id] as $i => $class) {
            if ($i != 0) {
                $option .= '<option value="' . $area_id . '_' . $i . '" >　　' . $class . '</option>';
                $text .= '<a href="?area_id=' . $area_id . '_' . $i . '' . $manage . '" class="class">' . $class . '</a> ';
            }
        }
        $text .= '<br />';
    }
    return $text;
}

// 传入管理参数
if ($_REQUEST['manage'] == 'yes') {
    require ('inc/function/confirm_manager.php');
    if (confirm_manager())
        $manage = '&manage=yes';
}
// 传入精华参数
if ($_REQUEST['list_type'] != 'ess') {
    $t1 = ' style="color:#FF0000"';
    $t2 = '';
} else {
    $t2 = ' style="color:#FF0000"';
    $t1 = '';
    $sqlt = ' AND good="good"';
}

// 版区ID
if ($_REQUEST['area_id']) {
    if (preg_match('/^\d+\_\d+$/', $_REQUEST['area_id'])) {
        list ($area_id, $class_id) = @explode('_', $_REQUEST['area_id']);
        if ($web['area'][$area_id][$class_id] == NULL) {
            die('&#26631;&#31614;&#38169;&#35823;&#65281;&#35831;&#20174;<a href="./">&#39318;&#39029;</a>&#37325;&#26032;&#24320;&#22987;');
        }
        $option = '<option value="' . $area_id . '" selected="selected">　' . $web['area'][$area_id][0] . '</option><option value="' . $_REQUEST['area_id'] . '" selected="selected">　　' . $web['area'][$area_id][$class_id] . '</option>';
        $title1 = $web['area'][$area_id][0] . ' &gt; ' . $web['area'][$area_id][$class_id];
        $title2 = '<a href="?area_id=' . $area_id . '&list_type=' . $_REQUEST['list_type'] . '' . $manage . '">' . $web['area'][$area_id][0] . '</a> &gt; ' . $web['area'][$area_id][$class_id];
        $artext = '';
        $arspit = '';
    } elseif (is_numeric($_REQUEST['area_id'])) {
        if ($web['area'][$_REQUEST['area_id']] == NULL) {
            die('&#26631;&#31614;&#38169;&#35823;&#65281;&#35831;&#20174;<a href="./">&#39318;&#39029;</a>&#37325;&#26032;&#24320;&#22987;');
        }
        $option = '<option value="' . $_REQUEST['area_id'] . '" selected="selected">　' . $web['area'][$_REQUEST['area_id']][0] . '</option>';
        $title1 = $web['area'][$_REQUEST['area_id']][0];
        $title2 = $web['area'][$_REQUEST['area_id']][0];
        $artext = getclass($_REQUEST['area_id']);
        $arspit = 'list($area_id,$class_id)=@explode("_",$row["area_id"]);$area_name=" <span class=\"ar\">[<a href=\"?area_id=".$area_id."_".$class_id."\">".$web["area"][$area_id][$class_id]."</a>]</span>";';
    } else {
        die('&#26631;&#31614;&#38169;&#35823;&#65281;&#35831;&#20174;<a href="./">&#39318;&#39029;</a>&#37325;&#26032;&#24320;&#22987;');
    }
} else {
    $title1 = '首页';
    $title2 = '所有栏目';
    require ('inc/function/getarea.php');
    $artext = getarea();
    $arspit = 'list($area_id,$class_id)=@explode("_",$row["area_id"]);$area_name=" <span class=\"ar\">[<a href=\"?area_id=".$area_id."_".$class_id."\">".$web["area"][$area_id][$class_id]."</a>]</span>";';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("menuhead.php"); ?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title1; ?> - <?php echo $web['sitename']; ?><?php echo $web['code_author']; ?></title>
<meta name="Description" content="<?php echo $web['description']; ?>" />
<meta name="keywords" content="<?php echo $web['keywords']; ?>" />
<link rel="stylesheet" type="text/css"
	href="css/<?php echo $web['cssfile']; ?>/style.css">
<?php
if (isset($manage)) {
    echo '
<style type="text/css">
<!--
.mm { display:inline; }
-->
</style>
';
}
?>
<script language="javascript" type="text/javascript">
<!--
var youVersion=9;
function getcss(color){
  if(color=='<?php echo $web['cssfile']; ?>'){
    setCookie('cssstyle','',-10);
  }else{
    document.getElementById('myfrime').src='run/user_style.php?color='+color+'';
  }
}

-->
</script>
	<script language="javascript" src="js/main.js" type="text/javascript"></script>

</head>
<body>

	<?php include("menubody.php"); ?>
	
	<div class="rig_lm01">
		<div class="title">
			<img src="images/listicon.jpg" class="icon" style="padding-top: 3px;">
				<h2>课后讨论</h2>
		
		</div>
	</div>


	<div class="area">
		<div class="area_title">
			<img src="css/<?php echo $web['cssfile']; ?>/area_title.gif"
				align="absmiddle" /> <a
				href="forum.php?<?php echo '&list_type='.$_REQUEST['list_type'].''.$manage.''; ?>">课后讨论首页</a> &gt; <?php echo $title2; ?></div>
  <?php echo $artext!=''?'<div class="area_content">'.$artext.'</div>':''; ?>
	</div>

	<div id="body">
		<div id="area">

			<form action="run.php?run=admin_del&dataname=bbslistdata"
				method="post" name="manageform" id="manageform">

				<div class="area_title">
					<a
						href="user_write.php?area_id=<?php echo $_REQUEST['area_id']; ?>"
						class="send"><?php echo $writepower; ?></a>
					<div style="float: right">
						[<a href="user_mess.php?username=<?php echo $web['manager']; ?>">给老师写信</a>]
					</div>
					<img src="css/<?php echo $web['cssfile']; ?>/area_title.gif"
						align="absmiddle" /> <?php echo '<a href="?area_id='.$_REQUEST['area_id'].''.$manage.'"'.$t1.'>讨论广场</a> · <a href="?area_id='.$_REQUEST['area_id'].'&list_type=ess'.$manage.'"'.$t2.'>精华列表</a>'; ?> · <a
						href="member.php">用户列表</a>
				</div>

				<div id="list_out">
					<table class="list" width="100%" border="0" cellspacing="0"
						cellpadding="0">
						<tr>
							<th class="click_reply">阅/回复</th>
							<th class="subject">标题</th>
							<th class="author">作者</th>
							<th class="redate_list">最后更新</th>
							<th class="wrdate_list">发表日期</th>
						</tr>
					</table>
<?php
if (isset($manage)) {
    require ('inc/function/get_manager_key.php');
}
?>
<?php

if ($db = @mysql_connect($sql['host'], $sql['user'], $sql['pass'])) {
    if (@mysql_select_db($sql['name'], $db)) {
        mysql_query('SET NAMES ' . $sql['char'] . '');
        if ($result = mysql_query('SELECT * FROM bbslistdata WHERE area_id LIKE "' . $_REQUEST['area_id'] . '%"' . $sqlt . ' ORDER BY topdate', $db)) { // 结果集
            $n = mysql_num_rows($result); // 总记录数
            require ('inc/function/get_page.php');
            require ('inc/function/get_date.php');
            $p = get_page($n); // 页数
            $seek = $n - $web['pagesize'] * ($p - 1);
            $end = $seek - $web['pagesize'] > 0 ? $seek - $web['pagesize'] : 0;
            $topdate = gmdate('YmdHis', time() + (floatval($web['time_pos']) * 3600));
            $todayli = get_date($topdate, 10);
            $step = 0;
            for ($i = $seek - 1; $i >= $end; $i --) {
                if (mysql_data_seek($result, $i)) {
                    if ($row = mysql_fetch_assoc($result)) {
                        $step ++;
                        eval($arspit);
                        $text_list .= '
 <table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr class="step' . ($step % 2) . '">
    <td class="click_reply">' . $row['views'] . '/' . $row['reply'] . '</td>
    <td class="subject"><input name="id[]" id="id[]" class="mm" type="checkbox" value="' . $row['id'] . '" />' . ($row['good'] == 'good' ? '<span style="color:#0033FF">[精华]</span>' : '') . '' . ($row['pic'] != '' ? '<img src="images/picye.gif" alt="文中含图" />' : '') . ($row['fil'] != '' ? '<img src="images/picfil.gif" alt="文中含影辑" />' : '') . ($row['enc'] != '' ? '<img src="images/picenc.gif" alt="文中含附件" />' : '') . '<a href="article.php?id=' . $row['id'] . '" title="' . $row['title'] . '" target="_blank">' . $row['title'] . '</a>' . $area_name . '</td>
    <td class="author">' . (preg_match('/\d+\.\d+\.\d+\.\d+/', $row['author_ip']) ? '<span title="匿名用户">(' . $row['author_ip'] . ')</span>' : '<a href="user_card.php?username=' . urlencode($row['author_ip']) . '" target="_blank" title="点击查看发布人名片">' . $row['author_ip'] . '<img src="images/card.gif" /></a>') . '</td>
    <td class="redate_list">' . ($row['topdate'] > $topdate ? '<span style="color:#FF5500">置顶至' . substr($row['topdate'], 4, 2) . '/' . substr($row['topdate'], 6, 2) . '</span>' : substr(get_date($row['topdate']), 5, 11)) . '</td> 
    <td class="wrdate_list' . ($todayli == $row['date'] ? ' todayli' : '') . '">' . substr($row['date'], 5, 5) . '</td>
  </tr>
</table> 
';
                        unset($row, $top_date, $upload_date);
                    }
                }
            }
            mysql_free_result($result);
        } else {
            $text .= '<div class="li"><img src="images/i.gif" /> 表连接不成功或尚未建立！</div>';
        }
        
        if ($result = mysql_query('SELECT other3 FROM bbsmember WHERE username="' . $cookie[0] . '"', $db)) { // 结果集
            $row3 = mysql_fetch_assoc($result);
            mysql_free_result($result);
        }
    } else {
        $text .= '<div class="li"><img src="images/i.gif" /> 指定的数据库连接不成功！</div>';
    }
    mysql_close();
} else {
    $text .= '<div class="li"><img src="images/i.gif" /> 数据库连接失败！</div>';
}

if ($text_list) {
    $text .= $text_list;
    $text .= get_page_foot($p, $n, '&area_id=' . $_REQUEST['area_id'] . '&list_type=' . $_REQUEST['list_type'] . '' . $manage . '');
} else {
    $text .= '<div class="li"><img src="images/i.gif" /> 暂无记录！</div>';
}

echo $text;

?>
</div>
			</form>
		</div>
		<div id="right">
			<div id="power"> 
<?php
echo $writepower == '发表帖子' ? '<div class="session">欢迎：' . $cookie[0] . ' ' . user_class(abs($cookie[1])) . '<br />
<a href="user_messbox.php">短信箱</a>（<span id="new_mess" title="最新来信">' . abs($row3['other3']) . '</span>） | <a href="user.php">用户中心</a> | <a href="run.php?run=user_login&act=logout&location=' . basename($_SERVER['REQUEST_URI']) . '">退出</a></div>
您上次访问是' . $you[1] . '' : '<div class="session">欢迎你：匿名用户<br /><a href="user_login.php?' . basename($_SERVER['REQUEST_URI']) . '" style="color:#FF5500">登录您的帐号</a> <a href="user_reg.php?' . basename($_SERVER['REQUEST_URI']) . '">快速注册帐号</a></div>';
?>
	
    </div>
			<div class="area">
				<div class="area_title">
					<img src="css/<?php echo $web['cssfile']; ?>/area_title.gif"
						align="absmiddle" /> 站内搜索
				</div>
				<div class="area_content">
					<form action="search.php?action=search" method="get"
						target="_blank">
						<input type="text" id="keyword" name="keyword"
							style="width: 228px;"
							onfocus="if(this.value=='关键词'){this.value=''}" value="关键词" /> <select
							name="area_id">
							<option value="all">所有类目</option>
          <?php echo $option; ?>
        </select> <input class="submit" type="submit" name="submit"
							value="搜索" /> <input type="hidden" name="action" value="search" />
					</form>
				</div>
			</div>

		</div>
	</div>


	</div>
	</div>
</body>
</html>
