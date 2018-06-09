<html>  
 <head> 
<meta charset="utf-8"/> 
 <title>用户注册</title> 
<script type="text/javascript" src="js/login.js"></script> 
<style> 
 body{ 
      background: #ebebeb; 
     font-family: "Helvetica Neue","Hiragino Sans GB","Microsoft YaHei","\9ED1\4F53",Arial,sans-serif; */
     color: #222; 
     font-size: 12px; 
 }  
 *{padding: 0px;margin: 0px;} 
 .top_div{ 
     background: #008ead; 
     width: 100%; 
     height: 400px; 
 } 
 .ipt{ 
     border: 1px solid #d3d3d3; 
     padding: 10px 10px; 
     width: 290px; 
     border-radius: 4px; 
     padding-left: 35px; 
     -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075); 
     box-shadow: inset 0 1px 1px rgba(0,0,0,.075); 
     -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s; 
     -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s; 
     transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s 
 } 
 .ipt:focus{ 
     border-color: #66afe9; 
     outline: 0; 
     -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6); 
     box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6) 
 } 

 a{ 
     text-decoration: none; 
 } 
 .tou{ 
     background: url("images/tou.png") no-repeat; 
     width: 97px; 
     height: 92px; 
     position: absolute; 
     top: -87px; 
     left: 140px; 
 } 

 .initial_left_hand{ 
     background: url("images/hand.png") no-repeat; 
     width: 30px; 
     height: 20px; 
     position: absolute; 
     top: -12px; 
     left: 100px; 
 } 
 .initial_right_hand{ 
     background: url("images/hand.png") no-repeat; 
     width: 30px; 
     height: 20px; 
     position: absolute; 
     top: -12px; 
     right: -112px; 
 } 
 .sub{width:65px; 
     height:40px; 
     line-height:20px; 
     font-size:20px; 
     background: #008ead; 
     color:#FFF; 
     padding-bottom:4px; 
     border-radius: 3px; 
     border: 1px solid #1a7598; 
    font-weight: bold; 
 } 

 </style> 
     </head> 
    
     <body> 
    <form action="registersuccess.php" method="post">
    <div class="top_div"></div> 
    <div style="width: 400px;height: 200px;margin: auto auto;background: #ffffff;text-align: center;margin-top: -100px;border: 1px solid #e7e7e7">
    <div style="width: 165px;height: 96px;position: absolute">
    <div class="tou"></div> 
    <div  class="initial_left_hand"></div> 
    <div class="initial_right_hand"></div> 
     </div> 
    
   
    <p style="padding: 10px 0px 10px 0px;position: relative;">
     用户名    ：&nbsp;&nbsp;&nbsp; 
    <input class="ipt" type="text" name="username" placeholder="请输入用户名"> 
    </p> 
    <p style="position: relative;">
    密码          ：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
     <input id="password" class="ipt" type="password" name="password" placeholder="请输入密码"> 
    </p> 
    <p style="padding: 10px 0px 0px 0px;position: relative;">
     密码确认： 
    <input id="password" class="ipt" type="password" name="confirm" placeholder="请再次输入密码"> 
    </p> 
 
    <div style="height: 50px;line-height: 20px;margin-top: 17px;border-top: 1px solid #e7e7e7;">
    <p style="margin: 0px 35px 20px 45px;">
    <span style="float: right">
    <input class="sub" type="submit" value="确认" name="submit"> 
   
     </span> 
     </p> 
     </div> 
     </div> 
     </form> 
     </body> 
     </html>  

