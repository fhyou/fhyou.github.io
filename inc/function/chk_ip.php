<?php

function chk_ip($ip){
  $ip_=preg_replace('/\d+$/','*',$ip);
  if(file_exists('data/ip/'.$ip) || file_exists('data/ip/'.$ip_)){
    err('抱歉，你所处的IP段已被屏蔽，禁止发表');
  }
}

?>