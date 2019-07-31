<?php
/* ========================================================================
 * 全局函数
 * ======================================================================== */

// +----------------------------------------------------------------------
// 检测，调试
// +----------------------------------------------------------------------
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
 * 设置服务器http 状态码 200,400,404,500
 * @param $code
 * @param bool $showPage
 */
function headerCode($code, $showPage = false) {
    if(is_numeric($code) && $code > 0)
    {
        switch ($code) {
            case 200:
                header('HTTP/2.0 200 OK');
                break;
            case 400:
                header('HTTP/2.0 400');
                if($showPage) include VIEW_PATH . 'error/404.html';
                break;
            case 403:
                header('HTTP/2.0 403');
                if($showPage) include  VIEW_PATH . 'error/forbidden.html';
                break;
            case 404:
                header('HTTP/2.0 404 Not Found');
                if($showPage) include  VIEW_PATH . 'error/404.html';
                break;
            case 500:
                header('HTTP/2.0 500');
                if($showPage) include VIEW_PATH . 'error/500.html';
                break;

            default:
                header('HTTP/2.0 400');
                if($showPage) include VIEW_PATH . 'error/404.html';
                break;
        }
    }
}



// +----------------------------------------------------------------------
// 常用方法
// +----------------------------------------------------------------------

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


// +----------------------------------------------------------------------
// 参数处理
// +----------------------------------------------------------------------
/**
 * 获取get数据
 * @param $str
 * @param string $filter 过滤方式 int为只支持int类型
 * @param bool $default 默认值 当获取不到值时,所返回的默认值
 * @return bool|string
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
 * @param bool $str
 * @param string $filter 过滤方式 int为只支持int类型
 * @param bool $default 默认值 当获取不到值时,所返回的默认值
 * @return bool|string
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