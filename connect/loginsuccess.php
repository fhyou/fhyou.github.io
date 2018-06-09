<?php 
setcookie("username", $_POST["username"], time()+3600);
?>

<html>
<head>
    <meta charset="utf-8" />
    <title>登陆中...</title>
</head>
<body>

<?php  
    include_once 'connect.php';
    header("Content-type: text/html; charset=utf-8");
    if(isset($_POST["submit"]) && $_POST["submit"] == "登陆")  
    {  
        $user = $_POST["username"];        
        
        $psw = $_POST["password"];  
        if($user == "" || $psw == "")  
        {  
            echo "<script>alert('请输入用户名或密码！'); history.go(-1);</script>";  
        }  
        else  
        {   
            $sql = "select username,password from user where username = '$_POST[username]' and password = '$_POST[password]'";  
            $result = mysql_query($sql);  
            $num = mysql_num_rows($result);  
            if($num)  
            {  
                $row = mysql_fetch_array($result);  //将数据以索引方式储存在数组中  
               
               
                
               echo "<script>alert('登陆成功！');setTimeout(function(){self.location.href='index.php';},0)</script>";  //提示后跳转
            }  
            else  
            {  
                echo "<script>alert('用户名或密码不正确！');history.go(-1);</script>";  
            }  
        }  
    }  
    else  
    {  
        echo "<script>alert('提交未成功！'); history.go(-1);</script>";  
    }  
    
?>  

</body>
</html>