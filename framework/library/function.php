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
 * 生成不重复的随机数
 * @param  int $start  需要生成的数字开始范围
 * @param  int $end 结束范围
 * @param  int $length 需要生成的随机数个数
 * @return array       生成的随机数
 */
 function random($start=1, $end=10, $length=4)
 {
    $connt=0;
    $temp=array();
    while($connt<$length){
        $temp[]=rand($start,$end);
        $data=array_unique($temp);
        $connt=count($data);
    }
    sort($data);

    return $data;
}


/**
 * 生成订单15位
 * @param $ord
 * @return int
*/
function autoMakeOrder($ord = 0) 
{
    // 自动生成订单号  传入参数为0 或者1   0为本地  1为线上订单
    if ($ord === 0) {
        $str = '00' . time() . mt_rand(100000, 999999); //00 本地订单
    } else {
        $str = '11' . time() . mt_rand(100000, 999999);  //11 线上订单
    }
    return $str;
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
 * 获取毫秒数
*/
 function microtime()
 {
     list($usec, $sec) = explode(" ", microtime());   //microtime() 返回：毫秒 时间戳 0.63559400 1469065900

     return ((float)$usec + (float)$sec);
 }


/**
 * 传入时间戳,计算距离现在的时间
 * @param  number $time 时间戳
 * @return string     返回多少以前
 */
 function formatTime($time) 
 {
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



 /**
 * 期转换成几分钟前
 * @param $date  2017-6-15
 * @return string
*/
function formatTime($date) 
{
    $timer = strtotime($date);
    $diff = $_SERVER['REQUEST_TIME'] - $timer;
    $day = floor($diff / 86400);
    $free = $diff % 86400;
    
    if($day > 0) {
        if(15 < $day && $day <30){
            return "半个月前";
        }elseif(30 <= $day && $day <90){
            return "1个月前";
        }elseif(90 <= $day && $day <187){
            return "3个月前";
        }elseif(187 <= $day && $day <365){
            return "半年前";
        }elseif(365 <= $day){
            return "1年前";
        }else{
            return $day."天前";
        }
    }else{
        if($free>0){
            $hour = floor($free / 3600);
            $free = $free % 3600;
            if($hour>0){
                return $hour."小时前";
            }else{
                if($free>0){
                    $min = floor($free / 60);
                    $free = $free % 60;
                if($min>0){
                    return $min."分钟前";
                }else{
                    if($free>0){
                        return $free."秒前";
                    }else{
                        return '刚刚';
                    }
                }
                }else{
                    return '刚刚';
                }
            }
        }else{
            return '刚刚';
        }
    }
}


/**
 * 判断是否为润年。 一般的,能被4,400整除, 不能被100整除的年份是闰年
 * @param $year
 * @return bool|string
*/
function isLeapyear($year) 
{
    if ((($year % 4) == 0) && (($year % 100) != 0) || (($year % 400) == 0)) {
        return (true);
    } else {
        return (false);
    }
}




/**
 * 截取字符串，后加点。 注：一个中文占3个字符
 * @param $str
 * @param $len
 * @param string $ellipsis
 * @return string
 */
 function sub_str($str, $len, $ellipsis = '...')
 {
     if( mb_strlen($str, 'utf-8') <= $len) {
         return $str;
     } else {
         return mb_substr($str, 0, $len, 'utf-8') . $ellipsis;
     }
 }


/**
 * 字符串截取，支持中文和其他编码
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $suffix 截断显示字符
 * @param string $charset 编码格式
 * @return string
 */
 function re_substr($str, $start=0, $length, $suffix=true, $charset="utf-8") 
 {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    $omit=mb_strlen($str) >=$length ? '...' : '';
    return $suffix ? $slice.$omit : $slice;
}


//获取客户端IP
function getIp()
{
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = "unknown";
    return($ip);
}


/**
* 根据新浪接口，获取当前所在的城市
* @param $queryIP
* @return string
*/
function getIPLoc_sina($queryIP)
{
    $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . $queryIP;
    $ch = curl_init($url);                                                    // 初始化url地址
    curl_setopt($ch, CURLOPT_ENCODING, 'utf8');            // 设置一个cURL传输选项
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   // 获取数据返回
    $location = curl_exec($ch);                                          // 执行一个cURL会话
    $location = json_decode($location);                            // 对 JSON 格式的字符串进行编码
    curl_close($ch);                                                           // 关闭一个cURL会话
    $loc = "";

    if ($location === FALSE) return "地址不正确";

    if (empty($location->desc)) {
        $loc = $location->city;
    } else {
        $loc = $location->desc;
    }

    return $loc;
}




/**
 * 判断当前设备，1：安卓；2：IOS；3：微信；0：未知
 * @param $ord
 * @return bool|string
*/
function isDevice()
{
    if($_SERVER['HTTP_USER_AGENT']){
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if(strpos($agent, 'micromessenger') !== false)
            return 3;
        elseif(strpos($agent, 'iphone')||strpos($agent, 'ipad'))
            return 2;
        else
            return 1;
    }
    return 0;
}


/**
 * 获取当前访问的设备类型
 * @return integer 1：其他  2：iOS  3：Android
 */
 function get_device_type()
 {
    //全部变成小写字母
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $type = 1;
    //分别进行判断
    if(strpos($agent, 'iphone')!==false || strpos($agent, 'ipad')!==false){
        $type = 2;
    }
    if(strpos($agent, 'android')!==false){
        $type = 3;
    }
    return $type;
}




/**
 * 非法字符过滤函数, 非法替换为空
 * @param $string
 * @return bool|string
*/
function has_unsafeword($string) 
{
    $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\.|\/|\;|\'|\`|\=|\\\|\|/";
    return preg_replace($regex,"", $string);   
}


/**
* 访止注入
* @param $sql_str
* @return int 返回1 合法， 0不合法
*/
function injectCheck($sql_str)
{
    return  preg_match('/select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i', $sql_str);
}


/**
 * xss过滤函数
 *
 * @param $string
 * @return string   将出的字符有空格替换
 */
function remove_xss($string) { 
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);
 
    $parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
 
    $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
 
    $parm = array_merge($parm1, $parm2); 
 
    for ($i = 0; $i < sizeof($parm); $i++) { 
        $pattern = '/'; 
        for ($j = 0; $j < strlen($parm[$i]); $j++) { 
            if ($j > 0) { 
                $pattern .= '('; 
                $pattern .= '(&#[x|X]0([9][a][b]);?)?'; 
                $pattern .= '|(&#0([9][10][13]);?)?'; 
                $pattern .= ')?'; 
            }
            $pattern .= $parm[$i][$j]; 
        }
        $pattern .= '/i';
        $string = preg_replace($pattern, ' ', $string); 
    }
    return $string;
}



/**
 * 去空格，以及字符添加斜杠
 * @param $string
 * @return bool|string
*/
function _trim(&$value) 
{
    return addslashes(trim($value));
}
