<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>

<?
$_POST['ips']='173.204.91.82
170.146.35.244
72.175.231.189
78.225.222.57
72.175.231.189
180.110.118.231
';
//$_POST['ips']=trim($_POST['ips']);
$_POST['ips']=preg_replace("/[\r\n]+/","\n",$_POST['ips']);

if(!preg_match("/^\d+\.\d+\.\d+\.(\d+|\-)\n$/m",$_POST['ips'])){
  die('请填有效的IP数据！只能数字、.、换行符、-填写。用回车键分开IP，即一行填一个IP');
}



?>
</body>
</html>
