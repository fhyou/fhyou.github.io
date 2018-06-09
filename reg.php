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
$shoujiyanzhengma = $_POST["shoujiyanzhengma"];
$password1 = $_POST["password1"];

echo "<br>手机号：" . $shoujihaoma;
echo "<br>验证码：" . $shoujiyanzhengma;
echo "<br>密码：" . $password1;

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "pyhome";

$conn = new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
    die("conn error".$conn->connect_error);
}



$sql="INSERT INTO `pyhome`.`reg` (`id`, `sjh`, `yzm`, `pass`) VALUES (NULL, '${shoujihaoma}', '${shoujiyanzhengma}', '${password1}')";
if($conn->query($sql)==true){
    echo "<br>插入成功！";
}else{
    echo "错误：".$sql.$conn->error;
}
    
?>

</body>

</html>
