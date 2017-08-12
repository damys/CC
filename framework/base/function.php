<?php
/* ========================================================================
 * 全局函数
 * ======================================================================== */


/**
 * 调试用：打印数组或变量
 * @param $var 数组或变量
 */
function msg($var)
{
    if (is_bool($var)) {
        var_dump($var);
    } else if (is_null($var)) {
        var_dump(NULL);
    } else {
        echo "<pre style='position:relative; _position:fixed; bottom:0; left: 0; ;z-index:1000;padding:10px;border-radius:4px;background:#F5F5F5;border:1px solid #ddd;font-size:14px;line-height:18px;'>" .  print_r($var, true) . "</pre>";
    }
}


/**
 * 获取get数据
 * @param $str 变量名
 * @param $filter 过滤方式 int为只支持int类型
 * @param $default 默认值 当获取不到值时,所返回的默认值
 * @return mix
 */
function get($str='false', $filter = '', $default = false)
{
    if($str !== false) {
        $return = isset($_GET[$str]) ? $_GET[$str] : false;
        if($return) {
            switch ($filter) {
                case 'int':
                    if (!is_numeric($return)) return $default;
                    break;
                default:
                    $return = htmlspecialchars($return);
            }
            return $return;
        } else {
            return $default;
        }
    } else {
        return $_GET;
    }
}


/**
 * 获取post数据
 * @param $str 变量名
 * @param $filter 过滤方式 int为只支持int类型
 * @param $default 默认值 当获取不到值时,所返回的默认值
 * @return mix
 */
function post($str=false, $filter = '', $default = false)
{
    if($str !== false) {
        $return = isset($_POST[$str])?$_POST[$str]:false;

        if($return) {
            switch ($filter) {
                case 'int':
                    if (!is_numeric($return)) return $default;
                    break;
                default:
                    $return = htmlspecialchars($return);
            }
            return $return;
        } else {
            return $default;
        }
    } else {
        return $_POST;
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
    if( mb_strlen($str, 'utf-8') <= $len) return $str;
    else return mb_substr($str, 0, $len, 'utf-8') . $ellipsis;
}


/**
 * 获取和设置语言定义(不区分大小写)
 * @param string|array $name 语言变量
 * @param mixed $value 语言值或者变量
 * @return mixed
 */
function L($name=null, $value=null)
{
    static $_lang = array();

    // 空参数返回所有定义
    if (empty($name))
        return $_lang;
    // 判断语言获取(或设置)
    // 若不存在,直接返回全大写$name
    if (is_string($name)) {
        $name   =   strtoupper($name);
        if (is_null($value)){
            return isset($_lang[$name]) ? $_lang[$name] : $name;
        }elseif(is_array($value)){
            // 支持变量
            $replace = array_keys($value);
            foreach($replace as &$v){
                $v = '{$'.$v.'}';
            }
            return str_replace($replace,$value,isset($_lang[$name]) ? $_lang[$name] : $name);
        }
        $_lang[$name] = $value; // 语言定义
        return null;
    }
    // 批量定义, 合并数组，转为大写
    if (is_array($name))
        $_lang = array_merge($_lang, array_change_key_case($name, CASE_UPPER));

    return null;
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