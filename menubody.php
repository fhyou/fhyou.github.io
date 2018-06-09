<!--  <div class="row"> 
	<div class="col-md-6">
		<strong>Welcome to You!</strong>
	</div>
	<div class="col-md-6">
		<ul class="list-inline top-link link">
			<li><a><div id="time1">实时时间</div> <script>setInterval("document.getElementById('time1').innerHTML = new Date().toLocaleString();", 0);</script></a></li>
			<li><a href="index.php"> 主页</a></li> -->
<?php
 /* if (isset($_COOKIE["username"])) {
    if ($_COOKIE["username"]) {
        $username = $_COOKIE['username'];
        echo "$username";
        ?>
								<li><a href="#">,欢迎你！</a></li>
			<li><a href="logout.php">注销</a></li>
								<?php  }else { ?>
								 <li><a href="login.php"> 登陆</a></li>
			<li><a href="register.php">注册</a></li>
							<?php }}else {?>
                         	<li><a href="login.php"> 登陆</a></li>
			<li><a href="register.php">注册</a></li>
                         <?php }
 */                        
                         ?> 
<!-- 					</ul>
	</div>
 </div>  
-->
<div class="header">
	<div class="top">
		<img class="logo" src="images/logo.jpg">
		<ul class="nav">
			<li class="seleli"><a href="index.php">首页</a></li>
			<li><a href="bulletin.php">公告栏</a></li>
			<li><a href="forum.php">课后讨论</a></li>
			<li><a href="extension.php">课堂延伸</a></li>
<!-- 			<li><a href="answering.php">在线答疑</a></li> -->

		</ul>
	</div>
</div>

<div class="container">
	<div class="leftbar">
		<div class="lm01">
			<img class="peptx" src="images/touxiang.jpg" />
			<div class="pepdet">
				<p class="pepname" style="text-indent: 4em">范宏猷</p>
				<p style="text-indent: 4em">生物科学学院</p>
				<p style="text-indent: 4.5em">博士、讲师</p>
				<p style="text-indent: 8em"> <div id="time1">实时时间</div> <script>setInterval("document.getElementById('time1').innerHTML = new Date().toLocaleString();", 0);</script></p>
			</div>
			<div class="clear"></div>
		</div>
		<div class="lm02">
			<div class="title">
				<img class="icon" src="images/dataicon.jpg" />
				<h2>日历</h2>
			</div>

			<div class="detail01">

				<div class="input-group date form_date" data-date=""
					data-date-format="dd MM yyyy" data-link-field="dtp_input2"
					data-link-format="yyyy-mm-dd"></div>
				<input type="hidden" id="dtp_input2" value="" /><br />
			</div>

		</div>
		<br />
		<div class="lm03">
			<div class="title">
				<img style="padding-right: 5px;" class="icon"
					src="images/weaicon.jpg" />
				<h2>天气</h2>
			</div>
			<div class="detail">
				<iframe width="210" scrolling="no" height="292" frameborder="0"
					allowtransparency="true"
					src="http://i.tianqi.com/index.php?c=code&id=19&color=%23002060&bgc=%23&icon=1&py=panyu&temp=0&num=4">
				</iframe>
			</div>
		</div>
	</div>
 <div class="mainbody">


	<script type="text/javascript" src="js/bootstrap-datetimepicker.js"
		charset="UTF-8"></script>
	<script type="text/javascript"> 
     $('.form_datetime').datetimepicker({
        //language:  'fr',
         weekStart: 1,
         todayBtn:  1,
 		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
 		forceParse: 0,
        showMeridian: 1
     });
 	$('.form_date').datetimepicker({
         language:  'fr',
        weekStart: 1,
         todayBtn:  1,
	autoclose: 1,
 		todayHighlight: 1,
		startView: 2,
 		minView: 2,
		forceParse: 0
     });
 	$('.form_time').datetimepicker({
         language:  'fr',
         weekStart: 1,
        todayBtn:  1,
 		autoclose: 1,
 		todayHighlight: 1,
 		startView: 1,
 		minView: 0,
 		maxView: 1,
 		forceParse: 0
     });
 </script>