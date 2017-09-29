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
