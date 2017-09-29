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
function make_rand($type='', $length=32)
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
