<?php
require ('inc/set.php');
?>

<html>
<head>
<?php include("menuhead.php"); ?>
</head>
<body>

<?php include("menubody.php"); ?>

		<div class="rig_lm01">
		<div class="title">
			<img src="images/listicon.jpg" class="icon" style="padding-top: 3px;">
			<h2>课堂延伸</h2>
		</div>
	</div>
<?php
header("Content-type: text/html; charset=utf-8");
$PW = 'admin'; // 管理密码
$TT = '课后资料下载'; // 页面标题
$NPP = 10; // 每页显示文件个数
$TF = array(
    'txt',
    'doc',
    'docx',
    'ppt',
    'xls',
    'mdb',
    'rar',
    'zip',
    'jpg',
    'gif',
    'png',
    'bmp',
    'mp3',
    'mp4',
    'wma',
    'rm',
    'wmv',
    'avi',
    '3pg',
    'htm',
    'html',
    'mht',
    'css',
    'php',
    'asp',
    'aspx',
    'jsp',
    'chm',
    'exe'
); // 允许下载的文件类型
$VF = array(
    'index.php'
); // 不允许显示和下载的文件名称
$TD = array(
    'uploadfile'
); // 要显示的文件夹
$TDN = array(
    '文件浏览'
); // 文件夹的描述，和上边对应
$TDP = array(
    ''
); // 文件夹的访问密码，和上边对应，不需要访问密码的文件夹就设置为空
$CS = false; // 显示时是否去掉文件名的后缀，true为去掉后缀，false为不去掉后缀
$UAF = true;
// 是否允许上传任何类型的文件，true为可以上传任何文件，false只能上传规定的后缀文件
class DataDownload
{
    // 管理密码
    var $passWord;
    
    // 页面标题
    var $title;
    
    // 每页显示文件数
    var $numPerPage;
    
    // 数组，允许下载的文件类型，小写字母
    var $tarFiles = array();
    
    // 数组，不允许显示和下载的文件名称
    var $voidFiles = array();
    
    // 数组，要显示的文件夹
    var $tarDirs = array();
    
    // 数组，文件夹的描述，和上边的数组对应
    var $tarDirsName = array();
    
    // 数组，文件夹的访问密码，和上边的数组对应，不需要访问密码的文件夹就设置为空
    var $tarDirsPwd = array();
    
    // 输出时去掉文件名后缀，true or false
    var $cutSuffix;
    
    // 是否允许上传任何类型的文件，如果为false，则只能上传规定的后缀文件
    var $upAnyFile;
    
    // 数组，存放目标文件的文件名
    var $files = array();
    
    // 数组，存放目录的名称
    var $dirs = array();
    
    // 数组，存放文件列表
    var $fileDown = array();
    
    // 存放储存文件数目
    var $fileNum;
    
    // 数组，分页信息
    var $pages = array();
    
    // 待输出文件数组
    var $outputFiles = array();
    
    // 待输出文件数目
    var $opFilesNum;
    
    // 目录名
    var $dir;
    
    // 目录ID
    var $dirId;
    
    // 用于判断是否需要密码
    var $needsPwd = false;
    
    // 外接样式表文件
    var $CSSFile;
    
    // 当前版本
    var $Version = 'Version 1.0';
    // 文件名称
    var $tarFilesType = array(
        'txt' => 'txt文本',
        'doc' => 'word文档',
        'docx' => 'word文档',
        'ppt' => '幻灯片',
        'xls' => 'Excel文档',
        'mdb' => '数据库',
        'rar' => 'RAR文档',
        'zip' => 'ZIP文档',
        'jpg' => 'JPG图片',
        'gif' => 'GIF图片',
        'png' => 'PNG图片',
        'bmp' => 'BMP图片',
        'mp3' => 'MP3音频',
        'mp4' => 'MP4音频',
        'wma' => 'WMA音频',
        'rm' => 'RM音频',
        'wmv' => 'WMV视频',
        'avi' => 'AVI视频',
        '3pg' => '3GP视频',
        'htm' => 'WEB文档',
        'html' => 'WEB文档',
        'mht' => 'WEB文档',
        'css' => '样式表',
        'php' => 'PHP文件',
        'asp' => 'ASP文件',
        'aspx' => 'ASP.NET文件',
        'jsp' => 'JSP文件',
        'chm' => 'CHM文档',
        'exe' => 'EXE文件'
    );
    
    // 读取目录前的操作，初始化检查目录
    function checkDir()
    {
        $this->dirId = (int) $_GET['dir'];
        $this->dir = isset($this->tarDirs[$this->dirId]) ? $this->tarDirs[$this->dirId] : $this->tarDirs[0];
        if (substr($this->dir, - 1) != '/') {
            $this->dir .= '/';
        }
        if (! is_dir($this->dir)) {
            $this->dir = './';
        }
        if ($this->tarDirsPwd[$this->dirId] != '') {
            $this->needsPwd = true;
        }
    }
    // 读取指定目录内的文件和目录，返回给$files和$dirs
    function readData()
    {
        $hd = opendir(rawurldecode($this->dir));
        while (false !== ($file = readdir($hd))) {
            if (is_dir($file) && '.' != $file && '..' != $file) {
                $this->dirs[] = rawurlencode($file);
            }
            if (in_array($this->getSuffix($file), $this->tarFiles) || count($this->tarFiles) == 0) {
                if (! in_array($file, $this->voidFiles) && ! is_dir($file)) {
                    $this->files[] = $file;
                }
            }
        }
    }
    
    // 返回文件名的后缀，并转为小写字母
    function getSuffix($fileName)
    {
        $pos = strrpos($fileName, '.');
        $suffix = strtolower(substr($fileName, $pos + 1, strlen($fileName) - $pos - 1));
        return $suffix;
    }
    
    // 返回后缀类型
    function recSuffix($suffix)
    {
        return (isset($this->tarFilesType[$suffix])) ? $this->tarFilesType[$suffix] : $suffix;
    }
    
    // 取得截去后缀的文件名
    function getfName($fileName)
    {
        $pos = strrpos($fileName, '.');
        return substr($fileName, 0, $pos);
    }
    
    // 取得目标文件的基本信息
    function getFileInfo()
    {
        $this->fileNum = sizeof($this->files);
        if ($this->fileNum == 0) {
            return false;
        }
        for ($i = 0; $i < $this->fileNum; $i ++) {
            $this->fileDown['name'][$i] = $this->files[$i];
            $this->fileDown['size'][$i] = filesize($this->dir . $this->files[$i]);
            $this->fileDown['date'][$i] = filemtime($this->dir . $this->files[$i]);
            $this->fileDown['suffix'][$i] = $this->getSuffix($this->files[$i]);
        }
    }
    
    // 得到指定文件的大小
    function getfSize($bytes)
    {
        $bytes = $bytes / 1024;
        $bytes > 1024 ? $size = number_format($bytes / 1024, 2) . 'mb' : $size = ceil($bytes) . 'kb';
        if ($bytes < 1024) {
            $size = ceil($bytes) . 'kb';
        } elseif ($bytes >= 1024 && $bytes < 1024 * 10) {
            $size = number_format($bytes / 1024, 2) . 'mb';
        } elseif ($bytes >= 1024 * 10 && $bytes < 1024 * 100) {
            $size = number_format($bytes / 1024, 1) . 'mb';
        } else {
            $size = ceil($bytes / 1024) . 'mb';
        }
        return $size;
    }
    
    // 格式化目标文件的基本信息
    function fmtFiles()
    {
        for ($i = 0; $i < $this->opFilesNum; $i ++) {
            $this->outputFiles[$i]['Fullname'] = $this->fileDown['name'][$i];
            $this->outputFiles[$i]['name'] = $this->cutSuffix ? $this->getfName($this->fileDown['name'][$i]) : $this->fileDown['name'][$i];
            $this->outputFiles[$i]['size'] = $this->getfSize($this->fileDown['size'][$i]);
            $this->outputFiles[$i]['date'] = date('y年m月d日', $this->fileDown['date'][$i]);
            $this->outputFiles[$i]['suffix'] = $this->recSuffix($this->fileDown['suffix'][$i]);
        }
    }
    
    // 将文件以指定方式排序
    function sortArray($order, $type = 'date')
    {
        $types = array(
            'date',
            'size',
            'suffix',
            'name'
        );
        if (in_array($type, $types)) {
            $types[array_search($type, $types)] = $types[0];
            $types[0] = $type;
        }
        ($order == 'asc') ? array_multisort($this->fileDown[$types[0]], SORT_ASC, $this->fileDown[$types[1]], SORT_ASC, $this->fileDown[$types[2]], SORT_ASC, $this->fileDown[$types[3]], SORT_ASC) : array_multisort($this->fileDown[$types[0]], SORT_DESC, $this->fileDown[$types[1]], SORT_DESC, $this->fileDown[$types[2]], SORT_DESC, $this->fileDown[$types[3]], SORT_DESC);
    }
    
    // 处理分页信息
    function getPageInfo($page)
    {
        $this->pages['max'] = (int) ceil($this->fileNum / $this->numPerPage);
        $this->pages['now'] = $page;
        if ((int) $page <= 0) {
            $this->pages['now'] = 1;
        }
        if ((int) $page > $this->pages['max']) {
            $this->pages['now'] = $this->pages['max'];
        }
        $this->pages['pre'] = $this->pages['now'] == 1 ? '' : $this->pages['now'] - 1;
        $this->pages['next'] = $this->pages['now'] == $this->pages['max'] ? '' : $this->pages['now'] + 1;
        $this->cutArray();
    }
    
    // 截取输出数组
    function cutArray()
    {
        $start = ($this->pages['now'] - 1) * $this->numPerPage;
        $this->fileDown['name'] = array_slice($this->fileDown['name'], $start, $this->numPerPage);
        $this->fileDown['size'] = array_slice($this->fileDown['size'], $start, $this->numPerPage);
        $this->fileDown['date'] = array_slice($this->fileDown['date'], $start, $this->numPerPage);
        $this->fileDown['suffix'] = array_slice($this->fileDown['suffix'], $start, $this->numPerPage);
        $this->opFilesNum = sizeof($this->fileDown['name']);
    }
    
    // 下载文件
    function downloadFile($filename)
    {
        if (in_array(basename(rawurldecode($filename)), $this->voidFiles)) {
            echo '<div style=\'color: red;text-align: center;\'>不允许下载的文件!</div>';
            return false;
        }
        if (! file_exists($filename)) {
            echo '<div style=\'color: red;text-align: center;\'>不存在的文件!</div>';
            return false;
        }
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        readfile(rawurldecode($filename));
    }
    
    // 删除文件
    function deleteFile($filename)
    {
        $username = $_COOKIE['usercookie'];
        $cookie = @explode('|', $username);
        if (($cookie[2] == 'manager') != true) {
            header("Location: ?");
            return false;
        }
        if (! file_exists($filename)) {
            echo "<script type='text/javascript'>alert('文件不存在！');window.opener.refresh();</script>";
            return false;
        }
        if (in_array(basename(rawurldecode($filename)), $this->voidFiles)) {
            echo "<script type='text/javascript'>alert('不可以删除的文件！');window.close();</script>";
            return false;
        }
        if (@unlink($filename)) {
            echo "<script type='text/javascript'>alert('文件 “" . basename($filename) . " ” 删除成功！');opener.window.history.go(0);window.close();</script>";
        } else {
            echo "<script type='text/javascript'>alert('文件 “" . basename($filename) . " ” 删除失败，没有权限！');window.close();</script>";
        }
    }
    
    // 上传文件
    function uploadFile()
    {
        $username = $_COOKIE['usercookie'];
        $cookie = @explode('|', $username);
        if ($cookie[2] == 'manager') {
            $this->dir = isset($this->tarDirs[$_POST['dirid']]) ? $this->tarDirs[$_POST['dirid']] : $this->tarDirs[0];
            if (substr($this->dir, - 1) != '/') {
                $this->dir .= '/';
            }
            if (! is_dir($this->dir)) {
                $this->dir = './';
            }
            if ($_FILES['file']['error'] == 0) {
                
                if (! $this->upAnyFile && in_array($this->getSuffix($_FILES['file']['name']), $this->tarFiles) == false && count($this->tarFiles) != 0) {
                    echo "<script type='text/javascript'>alert('文件 “" . $_FILES['file']['name'] . "” 上传失败，非法的文件类型！');window.location='?dir=" . $_POST['dirid'] . "'</script>";
                }
                if (file_exists($this->dir . $_FILES['file']['name'])) {
                    echo "<script type='text/javascript'>alert('文件 “" . $_FILES['file']['name'] . "” 上传失败，原文件已经存在！');window.location='?dir=" . $_POST['dirid'] . "'</script>";
                }
                
                if (copy($_FILES['file']['tmp_name'], $this->dir . $_FILES['file']['name'])) {
                    
                    echo "<script type='text/javascript'>alert('文件 “" . $_FILES['file']['name'] . "” 上传成功！');window.location='?dir=" . $_POST['dirid'] . "'</script>";
                } else {
                    echo "<script type='text/javascript'>alert('文件 “" . $_FILES['file']['name'] . "” 上传失败，可能原因：目录操作的权限不足。');window.location='?dir=" . $_POST['dirid'] . "'</script>";
                }
            } else {
                echo "<script type='text/javascript'>\n";
                echo "alert('文件上传失败\\n";
                echo "可能原因:\\n";
                echo "1.上传文件大小超过限制\\n";
                echo "2.目录的操作权限不足\\n";
                echo "3.上传的是一个空文件\\n";
                echo "返回的错误代码:" . $_FILES['file']['error'] . "');";
                echo "window.location='?dir=" . $_POST['dirid'] . "'</script>";
            }
        }
    }
    
    // 输出样式表
    function showCSS()
    {
        if (file_exists($this->CSSFile)) {
            echo "<link rel='stylesheet' style='text/css' href='" . $this->CSSFile . "' />";
        } else {
            print("<style type=\"text/css\">
<style>
body {
	font-size: 12px;
	font-family: Verdana, Times, serif;
}
                
#title {
	text-indent: 20px;
	margin-bottom: 3px;
	padding-bottom: 2px;
	color: #999999;
	font-size: 14px;
}

#uldir {
	text-indent: 5px;
	font-weight: 700;
}
#box {
	width: 860px;
}
#box a {
	padding-top: 3px;
	padding-bottom: 3px;
	color: #666666;
}
#files {
	float: right;
}
#contain {
	font-size: 14px;
	border: 1px solid #CECFDE;
	padding: 3px;
	width: 856px;
	margin: auto;
}
#contain ul {
	overflow: hidden;
// 	display: table-header-group;
	margin: 2px;
	padding:0px;
// 	height:8%;
              
}
#contain li {
	display:block;
	float: left;
	overflow: hidden;
	text-indent: 12px;
	list-style-type: none;
	line-height: 160%;
}
#contain #c_title li {
	font-weight: 700;
}
.t1  {
	width:57%;
	margin-right: 0;
             
}
.t2  {
	width: 13%;
	margin-right: 0;
}
.t3  {
	width: 10%;
	margin-right: 0;
}
.t4  {
	width: 17%;
	margin-right: 0;
}
#contain  #c_title   .t1, #contain  #c_title   .t2, #contain #c_title .t3, #contain #c_title .t4 {
	text-align: center;
	text-indent: 0;
	padding: 0;
	margin-right: 2px;
	background-color: #DEDBD6;
	border-right: 1px solid #9C9E9C;
	border-bottom: 1px solid #9C9E9C;
}
#contain #c_title .t4 {
	margin-right: 0;
}
#contain .u1 li {
	background-color: #F7F7F7;
}
#contain .u2 li{
	background-color: #FFFBFF;
}
#contain .u3 {
	width: 657px;
	cursor: hand;
	color: #666666;
	border-top: 1px solid #999999;
	border-bottom: 1px solid #999999;
	background-color: #CCCCCC;
}
#topbar {
	margin: auto;
	padding: 5px;
	width: 856px;
	background: #EAEAEA;
	border: 1px solid #CECFDE;
	margin-bottom: 5px;
}
#logined {
	margin: auto;
	padding: 5px;
	width: 856px;
	color: #666;
	border: 1px solid #CECFDE;
}
#topbar select {
	width: 120px;
	font-size: 12px;
	font-family: Verdana, \"Times New Roman\", Times, serif;
}
#topbar span {
	color: #666666;
	font-size: 12px;
	font-weight: 700;
	margin-right: 10px;
	text-decoration: underline;
}
#topbar span#tip {
	color: #DD0000;
	font-size: 12px;
	font-weight: 700;
	text-decoration: none;
}
#bottombar {
	text-align: center;
	margin: auto;
	margin-top: 5px;
	padding: 5px;
	width: 856px;
	background: #EAEAEA;
	border: 1px solid #CECFDE;
}
#bottombar select {
	width: 80px;
	font-size: 12px;
	font-family: Verdana, \"Times New Roman\", Times, serif;
}
#bottombar span {
	color: #666666;
	font-size: 12px;
	font-weight: 700;
}
#bottombar a {
	text-decoration: none;
	color: #DD0000;
}

#login {
	border: 10px solid #CECFDE;
	padding-left: 3px;
	padding-top: 10px;
	padding-right: 3px;
	padding-bottom: 10px;
	width: 660px;
	margin: auto;
}
#login form {
	margin: 0;
	padding: 0;
}
#login input.pwd{
	width: 80px;
	height: 12px;
	color: #999999;
	border: 1px dashed #999999;
}
#login input.sbmit{
	width: 80px;
	height: 18px;
	font-family: Verdana, Times, serif;
	background: #FFFFFF;
	border: 1px solid #AAAAAA;
}
#sorry {
	text-align: center;
	color: #DD0000;
	height: 30px;
}
#adminli a {
	color: #999999;
}
#upload form.form1 {
	float: right;
	margin: 0;
	padding: 0;
	width: 300px;
}
input.input1  {
         float: right;  
                width: 50px;
                right-margin:10px;
}
                
#upload form input.file1 {
                float: right;
	color: blue;
     width: 200px;
}
.clearfloat { /* 此类应当放在 div 或 break 元素上，而且该元素应当是完全包含浮动的容器关闭之前的最后一个元素 */
	clear:both;
	height:0;
	font-size: 1px;
	line-height: 0px;
}

</style>
");
        }
    }

    
    // 输出文件列表
    function showFiles()
    {
        $username = $_COOKIE['usercookie'];
        $cookie = @explode('|', $username);
        echo "<div id='contain'>\n";
        echo "<ul id='c_title'>\n";
        echo "<li class=\"t1\">名称</li>\n";
        echo "<li class=\"t2\">类型</li>\n";
        echo "<li class=\"t3\">大小</li>\n";
        echo "<li class=\"t4\">日期</li>\n";
        echo "</ul>\n";
        if ($this->opFilesNum != 0) {
            for ($i = 0; $i < $this->opFilesNum; $i ++) {
                $c = $i % 2 == 0 ? 'u1' : 'u2';
                $clickN = 'javascript:window.location=\'?filename=' . rawurlencode($this->dir . $this->outputFiles[$i]['Fullname']) . '\'';
                $clickA = 'javascript:if(event.altKey){deletef(\'' . $this->outputFiles[$i]['Fullname'] . '\',\'' . rawurlencode($this->dir . $this->outputFiles[$i]['Fullname']) . '\');}else{window.location=\'?filename=' . rawurlencode($this->dir . $this->outputFiles[$i]['Fullname']) . '\'}';
                $click = $cookie[2] == 'manager' ? $clickA : $clickN;
                echo '<ul class="' . $c . '" onmouseover="this.className=\'u3\';" onmouseout="this.className=\'' . $c . '\'" title=" 点击下载 ' . $this->outputFiles[$i]['name'] . ' " onclick="' . $click . '">';
                echo '<li class="t1">' . $this->outputFiles[$i]['name'] . '</li>';
                echo '<li class="t2">' . $this->outputFiles[$i]['suffix'] . '</li>';
                echo '<li class="t3">' . $this->outputFiles[$i]['size'] . '</li>';
                echo '<li class="t4">' . $this->outputFiles[$i]['date'] . '</li>';
                echo '</ul>';
                echo "\n";
            }
            echo "<br class=\"clearfloat\" />\n";
        } else {
            echo "<p id='sorry'>抱歉，暂时没有文件提供下载</p>\n";
        }
        echo "</div>\n";
    }
    
    // 输出管理员登录页脚说明
    function showLoginedAdmin()
    {
        echo "<div id='logined'>\n";
        echo "<div>操作说明：</div>\n";
        echo "<div>1、删除文件：按住“ALT”键点击要删除的文件</div>\n";
        echo "<div>2、上传文件：最大能上传大小为" . ini_get('upload_max_filesize') . "的文件</div>\n";
        if ($this->upAnyFile) {
            echo "<div>3、可上传任何类型的文件，但上传后不符合下载后缀的文件是无法显示的。</div>\n";
        } else {
            $upLoad = count($this->tarFiles) == 0 ? '所有类型' : implode('，', $this->tarFiles);
            echo "<div>3、可上传的文件类型有：" . $upLoad . "</div>\n";
        }
        echo "</div>\n";
    }
    
    // 输出上传表单
    function showUploadForm()
    {
        echo "<div id='upload'>";
        
        echo "<form class='form1' action=\"?upload\" method=\"post\" enctype=\"multipart/form-data\">";
        echo "<input class='input1' type='submit' value='上传' />";
        echo "<input type='file' name='file' class='file1' />";
        echo "<input  type='hidden' name='dirid' value='" . $this->dirId . "' />";
        
        echo "</form>";
        echo "</div>";
    }
    
    // 输出目录导航条
    function showTopbar()
    { // debug
        $username = $_COOKIE['usercookie'];
        $cookie = @explode('|', $username);
        echo "<div id='topbar'>\n";
        if ($cookie[2] == 'manager') {
            $this->showUploadForm();
        }
        echo "当前位置:<span>" . $this->tarDirsName[$this->dirId] . "</span>\n";
        echo "<select onchange=\"javascript:window.location=this.value;\">\n";
        echo "<option>文件排序</option>\n";
        echo "<option value='?type=date&amp;order=asc&amp;dir=" . $this->dirId . "'>日期 升序</option>\n";
        echo "<option value='?type=date&amp;order=desc&amp;dir=" . $this->dirId . "'>日期 降序</option>\n";
        echo "<option value='?type=size&amp;order=asc&amp;dir=" . $this->dirId . "'>大小 升序</option>\n";
        echo "<option value='?type=size&amp;order=desc&amp;dir=" . $this->dirId . "'>大小 降序</option>\n";
        echo "<option value='?type=name&amp;order=asc&amp;dir=" . $this->dirId . "'>名称 升序</option>\n";
        echo "<option value='?type=name&amp;order=desc&amp;dir=" . $this->dirId . "'>名称 降序</option>\n";
        echo "<option value='?type=suffix&amp;order=asc&amp;dir=" . $this->dirId . "'>类型 升序</option>\n";
        echo "<option value='?type=suffix&amp;order=desc&amp;dir=" . $this->dirId . "'>类型 降序</option>\n";
        echo "</select>\n";
        echo "</div>\n";
    }
    
    // 输出页面导航条
    function showBottombar()
    {
        echo "<div id='bottombar'>\n";
        echo "[ 文件数目:<span>" . $this->fileNum . "</span>个 每页显示:<span>" . $this->numPerPage . "</span>个 ]\n";
        echo "[ 当前第<span>" . $this->pages['now'] . "</span>页 共<span>" . $this->pages['max'] . "</span>页 ]\n";
        $this->Paging($_SERVER['argv'][0]);
        echo "</div>\n";
    }
    
    // 输出分页
    function Paging($ReQuest)
    {
        if ($this->pages['max'] == 0) {
            $this->pages['max'] = 1;
        }
        $ReQuest = isset($ReQuest) ? preg_replace("/page=\d{0,}&{0,1}/", '', $ReQuest) : '';
        if ($ReQuest != '')
            $ReQuest = '&amp;' . $ReQuest;
        echo $this->pages['pre'] == '' ? " [上一页] " : "<a href=\"?page=" . $this->pages['pre'] . $ReQuest . "\" title=\" 上一页 \"> [上一页] </a>\n";
        echo "<select onchange=\"javascript:window.location='?page='+this.value\">\n";
        for ($i = 1; $i <= $this->pages['max']; $i ++) {
            echo "<option value='" . $i . $ReQuest . "'";
            if ($this->pages['now'] == $i) {
                echo " selected='selected'";
            }
            echo ">第" . $i . "页</option>\n";
        }
        echo "</select>\n";
        echo $this->pages['next'] == '' ? " [下一页] \n" : "<a href=\"?page=" . $this->pages['next'] . $ReQuest . "\" title=\" 下一页 \"> [下一页] </a>\n";
    }

    
    // 输出head区域HTML代码
    function showHeader()
    {
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
        echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
        echo "<head>\n";
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
        echo "<title>" . $this->title . "</title>\n";
        $this->showCSS();
        $this->showJS();
        echo "</head>\n";
    }
    
    // 输出JAVASCRIPT代码
    function showJS()
    {
        $username = $_COOKIE['usercookie'];
        $cookie = @explode('|', $username);
        if ($cookie[2] == 'manager') {
            echo "<script type='text/javascript'>\n";
            echo "function deletef(filename, filepath) {\n";
            echo "question=confirm('确实要删除文件 “'+filename+'” 吗？');\n";
            echo "if(question==true) {\n";
            echo "window.open('?delete='+filepath);\n";
            echo "}\n";
            echo "else {\n";
            echo "return false;\n";
            echo "}\n";
            echo "}\n";
            echo "</script>\n";
        }
    }
    
    // 输出body区域HTML代码
    function showBody()
    {
        $username = $_COOKIE['usercookie'];
        $cookie = @explode('|', $username);
        echo "<body>\n";
        echo "<div id='box'>\n";
        echo "<div id='files'>\n";
        $username = $_COOKIE['usercookie'];
        $cookie = @explode('|', $username);
        if ($cookie[2] == 'manager') {

            $this->readData();
            $this->getFileInfo();
                    if ($this->fileNum > 0) {
                $this->sortArray($_GET['order'], $_GET['type']);
                $this->getPageInfo($_GET['page']);
                $this->fmtFiles();
            }
            $this->showTopbar();
            $this->showFiles();
            $this->showBottombar();
            $this->showLoginedAdmin();
           
        } else {

            $this->readData();
            $this->getFileInfo();
                    if ($this->fileNum > 0) {
                $this->sortArray($_GET['order'], $_GET['type']);
                $this->getPageInfo($_GET['page']);
                $this->fmtFiles();
            }
            $this->showTopbar();
            $this->showFiles();
            $this->showBottombar();
            
        }
        echo "</div>\n";
        echo "</div>\n";
        echo "</body>\n";
        echo "</html>\n";
    }
    
    // 输出HTML代码
    function showHTML()
    {
        $this->showHeader();
        $this->checkDir();
        $this->showBody();
    }

    function DataDownload($passWord, $title, $numPerPage, $tarFiles, $voidFiles, $tarDirs, $tarDirsName, $tarDirsPwd, $cutSuffix, $upAnyFile)
    {
        $this->passWord = & $passWord;
        $this->title = & $title;
        $this->numPerPage = & $numPerPage;
        $this->tarFiles = & $tarFiles;
        $this->voidFiles = & $voidFiles;
        $this->tarDirs = & $tarDirs;
        $this->tarDirsName = & $tarDirsName;
        $this->tarDirsPwd = & $tarDirsPwd;
        $this->cutSuffix = & $cutSuffix;
        $this->upAnyFile = & $upAnyFile;
    }
}

error_reporting(0);
$dD = new DataDownload($PW, $TT, $NPP, $TF, $VF, $TD, $TDN, $TDP, $CS, $UAF);
if ($_GET['filename']) {
    $dD->downloadFile($_GET['filename']);
} else {
    session_start();
    if ($_GET['delete']) {
        $dD->deleteFile($_GET['delete']);
    } elseif (isset($_GET['upload'])) {
        $dD->uploadFile();
    } elseif (isset($_GET['logindir'])) {
        $dD->loginDir();
    
    } else {
        $dD->showHTML();
    }
}
?>

		

</div>
	</div>
</body>
</html>
