<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if(PHP_VERSION<"4.1.0"){
  $_GET=&$HTTP_GET_VARS;
  $_POST=&$HTTP_POST_VARS;
  $_COOKIE=&$HTTP_COOKIE_VARS;
  $_SERVER=&$HTTP_SERVER_VARS;
  $_ENV=&$HTTP_ENV_VARS;
  $_FILES=&$HTTP_POST_FILES;
}



/* ----------【网站设置】能不用尽量不要用特殊符号，如 \ / : ; * ? ' < > | ，必免导致错误--------- */

//基本设置：
$web['sitehttp']='http://'.(!empty($_SERVER['HTTP_X_FORWARDED_HOST'])?$_SERVER['HTTP_X_FORWARDED_HOST']:$_SERVER['HTTP_HOST']).'/';  //站点网址
$web['path']=dirname(trim($web['sitehttp'],'/').$_SERVER['SCRIPT_NAME']).'/';  //路径
$web['sitename']='教师辅助教学系统';  //站点名称
$web['description']='教师辅助教学系统';  //站点描述
$web['keywords']='教师辅助教学系统';  //关键字
$web['manager']='admin';  //基础管理员名称
$web['password']='admin';  //基础管理员密码，注：系统出现一切故障时以基础管理员名称和密码为准
$web['cssfile']='blue';  //站点默认风格
$web['time_pos']='8北京，重庆，香港特别行政区，乌鲁木齐，台北';  //服务器时区调整

//控制设置：
$web['stop_reg']=0;  //禁止用户注册1禁止0不禁止
$web['pagesize']=30;  //每页显示数量
$web['list_wordcount']=50000;  //发表文章最多字符数
$web['re_wordcount']=5000;  //评论回复最多字符数
$web['mess_wordcount']=400;  //短信发表最多字符数
$web['mess_count']=100;  //站内短信最多保存条数

//发表设置
$web['guest_write']=1;  //1允许过客发表 0不允许
$web['class_iron']=500;  //铁级用户等级分标准
$web['class_silver']=1000;  //银级用户等级分标准
$web['class_gold']=5000;  //金级用户等级分标准，大于此数量的为砧级用户
$web['send_mx']=1;  //1每日发表限量 0不限量
$web['write_start']=0;           //发表权限起始分，如：禁止新人发表可设高一点分值
$web['up_start']=100;           //上传权限起始分
$web['link_start']=10;          //文章允许加链接起始分
//上传设置
$web['max_file_size'][15]=10000;  //限定上传图片尺寸（单位KB）
$web['max_file_size'][16]=10000;  //限定上传动画尺寸（单位KB）
$web['max_file_size'][17]=10000;  //限定上传影音文件尺寸（单位KB）
$web['max_file_size'][18]=10000;  //限定上传其它文件尺寸（单位KB）
$web['_17_type']='wav|wma|wmv|mid|midi|avi|mp3|mpg|mpeg|asf|asx|mov|rm|rmvb|ram|ra';  //设置允许上传的影音文件类型，用|分开写
$web['_18_type']='rar|zip|doc|xls|chm|hlp';  //设置允许上传的其它文件类型，用|分开写

$web['re_update']=1;  //1回复时将主题文章提前 0自然顺序
$web['loginadd']=2;  //用户登录加等级分数
$web['writeadd']=10;  //用户发表主题加等级分数

//图片处理设置
$web['water']=1;                //1图片加水印 0否
$web['spic_w']=60;  //缩略图宽
$web['spic_h']=45;  //缩略图高
$web['pic_markwords']='http://fanhongyou.com';  //水印文字
$web['pic_markfont']='arial.ttf';  //水印字体
$web['pic_quality']=100;  //上传图片质量


if($_COOKIE['162100bbs_style']){
  if(file_exists('css/'.$_COOKIE['162100bbs_style'].'/style.css')){
    $web['cssfile']=$_COOKIE['162100bbs_style'];
  }
}


?>