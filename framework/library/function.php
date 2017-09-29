<?php

/**
 * 按类型生成随机字符串
 * @param $type 类型，默认为混合，upper(只生成大写字母)，lower(只生成小写字母)，number(只生成数字)
 * @param $length  长度，默认为32位， 定义字符串长度
 * @return string
*/
function random($type='', $length = 32) 
{
   $chars = '';
   $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';  //数据池

   if ($type == 'upper') {
       for ($i = 0; $i < $length; $i++) {
           $chars .= $string[mt_rand(36, 61)];
       }
       return $chars;
   } else if ($type == 'lower') {
       for ($i = 0; $i < $length; $i++) {
           $chars .= $string[mt_rand(10, 35)];
       }
       return $chars;
   } else if ($type == 'number') {
       for ($i = 0; $i < $length; $i++) {
           $chars .= $string[mt_rand(0, 9)];
       }
       return $chars;
   } else {
       for ($i = 0; $i < $length; $i++) {
           $chars .= $string[mt_rand(0, 61)];
       }
       return $chars;
   }
}


/**
* 产生随机数
* @param int $length
* @return string
* @return string
*/
function random($type='', $length=32)
{
   if($type === 'touble'){
       $chars=array(
           'a','b','c','d','e','f','g','h',
           'i','j','k','m','n','p','q','r','s',
           't','u','v','w','x','y','z','A','B','C','D',
           'E','F','G','H','I','J','K','L','M','N',
           'P','Q','R','S','T','U','V','W','X','Y','Z',
           '0','1','2','3','4','5','6','7','8','9');
   }else{
       $chars=array('a','b','c','d','e','f','g','h',
           'i','j','k','l','m','n','o','p','q','r','s',
           't','u','v','w','x','y','z','A','B','C','D',
           'E','F','G','H','I','J','K','L','M','N','O',
           'P','Q','R','S','T','U','V','W','X','Y','Z',
           '0','1','2','3','4','5','6','7','8','9','!',
           '@','#','$','%','^','&','*','(',')','-','_',
           '[',']','{','}','<','>','~','`','+','=',',',
           '.',';',':','/','?','|');
   }

   $keys=array_rand($chars, $length);
   $password='';

   for($i=0;$i<$length;$i++){
       $password.=$chars[$keys[$i]];
   }
   return $password;
}


/**
 * 计算该月有几天
 * @param $month 
 * @param $year
 * @return int
*/
function getdaysInmonth($month, $year) 
{
    $days = '';
    if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12)
        $days = 31;
    else if ($month == 4 || $month == 6 || $month == 9 || $month == 11)
        $days = 30;
    else if ($month == 2) {
        if (isLeapyear($year)) {
            $days = 29;
        } else {
            $days = 28;
        }
    }
    return ($days);
}



/**
 * 传入时间戳,计算距离现在的时间
 * @param  number $time 时间戳
 * @return string     返回多少以前
 */
 function formatTime($time) {
    $time = (int) substr($time, 0, 10);
    $int = time() - $time;
    $str = '';
    if ($int <= 2){
        $str = sprintf('刚刚', $int);
    }elseif ($int < 60){
        $str = sprintf('%d秒前', $int);
    }elseif ($int < 3600){
        $str = sprintf('%d分钟前', floor($int / 60));
    }elseif ($int < 86400){
        $str = sprintf('%d小时前', floor($int / 3600));
    }elseif ($int < 1728000){
        $str = sprintf('%d天前', floor($int / 86400));
    }else{
        $str = date('Y-m-d H:i:s', $time);
    }
    return $str;
}



/**
 * 按时间戳格式化自定义时间
 * @param $time
 * @return bool|string
 */
 function formatTime($time)
 {
     $rtime = date("Y-m-d H:i",$time);
     $time = time() - $time;
 
     if ($time < 60){
         $str = '刚刚';
     }elseif($time < 60 * 60){
         $min = floor($time/60);
         $str = $min.'分钟前';
     }elseif($time < 60 * 60 * 24){
         $h = floor($time/(60*60));
         $str = $h.'小时前 ';
     }elseif($time < 60 * 60 * 24 * 3){
         $d = floor($time/(60*60*24));
         if($d==1){
             $str = '昨天 '.$rtime;
         }else{
             $str = '前天 '.$rtime;
         }
     }else{
         $str = $rtime;
     }
     return $str;
 }