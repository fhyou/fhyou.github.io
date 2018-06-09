<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

<?php

$shoujihaoma = $_POST["shoujihaoma"];
$password1 = $_POST["password1"];

echo "<br>手机号：" . $shoujihaoma;
echo "<br>密码：" . $password1;

$msg = " ";

if(empty($shoujihaoma) || strlen($shoujihaoma)!=11){
    $msg = "手机号码为空或输入错误！";
    // // 跳转方法1
    // echo "<script>window.location.href='login.html'</script>";
    // 跳转方法2
    header("Location:"."login2.php? msg=${msg}");
}

if(empty($password1)||strlen($password1) < 6 || strlen($password1) > 12){
    $msg = " 密码格式错误！";
    header("Location:"."login2.php? msg=${msg}");
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "pyhome";

$conn = new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
    die("conn error".$conn->connect_error);
}

$sql="SELECT id,sjh,pass FROM `reg` WHERE sjh='${shoujihaoma}' and pass='${password1}'";
$result = $conn->query($sql);

// 把运行过程显示在浏览器网页上
// var_dump($result);

if($result->num_rows>0){
    while($row = $result->fetch_assoc()){
    echo "<br>id: ".$row["id"]. "  sjh: ".$row["sjh"]. "  pass: ".$row["pass"];
    }
}else{
    $msg = "用户名或密码错误";
    header("Location:"."login2.php? msg=${msg}");
}

// 关闭数据库
$conn->close();
?>

</body>

</html>
