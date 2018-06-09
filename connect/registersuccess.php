<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>跳转中.......</title>
</head>
<body> 
<?php 
    include_once 'connect.php';
    header("Content-type: text/html; charset=utf-8");
    if(isset($_POST["submit"]) && $_POST["submit"] == "确认")  
    {  
        $user = $_POST["username"];  
        $psw = $_POST["password"];        
        $psw_confirm = $_POST["confirm"];  
        date_default_timezone_set('PRC');//设置中国时区
        $createtime=date("y-d-m H:i:s");//获取注册时间，也就是数据写入到用户表的时间
        if($user == "" || $psw == "" || $psw_confirm == "")  
        {  
            echo "<script>alert('请确认信息完整性！'); history.go(-1);</script>";  
        }  
        else  
        {  
            if($psw == $psw_confirm)  
            {  

                $sql = "select username from user where username = '$_POST[username]'"; //SQL语句  
                $result = mysql_query($sql);    //执行SQL语句  
                $num = mysql_num_rows($result); //统计执行结果影响的行数  
                if($num)    //如果已经存在该用户  
                {  
                    echo "<script>alert('用户名已存在'); history.go(-1);</script>";  
                }  
                else    //不存在当前注册用户名称  
                {  
                    $sql_insert = "insert into user (username,password,createtime) values('$_POST[username]','$_POST[password]','$createtime')";  
                    $res_insert = mysql_query($sql_insert);                
                    if($res_insert)  
                    {  
                         
                        echo "<script>alert('注册成功！');setTimeout(function(){self.location.href='login.php';},0)</script>";  //提示后跳转
                       
                    }  
                    else  
                    {  
                        echo "<script>alert('系统繁忙，请稍候！'); history.go(-1);</script>";  
                    }  
                }  
            }  
            else  
            {  
                echo "<script>alert('密码不一致！'); history.go(-1);</script>";  
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