<?php
//每日发表限量
function send_mx($point){
  global $web,$cookie;
  if($cookie[2]=='manager'){
    return '';
  }else{
    if($point){
	  $point=abs($point);
      if($point>=0 && $point<=abs($web['class_iron'])){
        return floor($point/100)+5; //铁级
      }elseif($point>abs($web['class_iron']) && $point<=abs($web['class_silver'])){
        return floor($point/100)+5; //银级
      }elseif($point>abs($web['class_slive']) && $point<=abs($web['class_gold'])){
        return floor($point/100)+5; //金级
      }else{
        return '';
      }
    }else{
      return 2;
    }
  }
}

//一人对一个主题最多回复数
$web['reply_mx']=30;
?>