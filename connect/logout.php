<?php
header("Content-type: text/html; charset=utf-8");
setcookie("username");
echo "<script>alert('注销成功！');setTimeout(function(){self.location.href='index.php';},0)</script>";  //提示后跳转
?>