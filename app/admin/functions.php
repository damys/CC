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
 * 测试：将数据存入文件
 * @param $data
 * @param string $fileName
 */
function file_test($data, $fileName = '')
{
    if(is_array($data)) {
        $data = json_encode($data);
    }

    $data = date('Y-m-d H:i:s', time()).'---'. $data;

    if(empty($fileName)) {
        $fileName = '../runtime/temp.txt';
    }
    else {
        $fileName = '../runtime/'.$fileName;
    }

    file_put_contents($fileName, $data.PHP_EOL, FILE_APPEND);
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

// 获取唯一序列号
function uuid(){
    $str = md5(uniqid(md5(microtime(true)), true));

    $uuid  = substr($str,0,8) . '-';
    $uuid .= substr($str,8,4) . '-';
    $uuid .= substr($str,12,4) . '-';
    $uuid .= substr($str,16,4) . '-';
    $uuid .= substr($str,20,12);
    return $uuid;
}


// +----------------------------------------------------------------------
// curl: get, get_header, post, post_header
// +----------------------------------------------------------------------

/**
 * @param string $url get请求地址
 * @param int $httpCode 返回状态码
 * @return mixed
 */
function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //不做证书校验,部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

/**
 * @param string $url get请求地址
 * @param array $header 自定义的header数据,注意格式如：array('token:c3d34690477d21952ef67162ff1e726e')
 * @param int $httpCode 返回状态码
 * @return mixed
 */
function curl_get_header($url,$header = [], &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //不做证书校验,部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

/**
 * @param string $url post请求地址
 * @param array $params
 * @return mixed
 */
function curl_post($url, array $params = array())
{
    $data_string = json_encode($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER,
        array(
            'Content-Type: app/json'
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}

/**
 * 发送数据
 * @param String $url     请求的地址
 * @param Array  $header  自定义的header数据
 * @param Array  $content POST的数据
 * @return String
 */
function curl_post_header($url, $header, $content){
    $ch = curl_init();
    if(substr($url,0,5)=='https'){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
    $response = curl_exec($ch);
    if($error=curl_error($ch)){
        die($error);
    }
    curl_close($ch);
    return $response;
}

/**
 * post raw 格式
 * @param $url
 * @param $rawData
 * @return mixed
 */
function curl_post_raw($url, $rawData)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER,
        array(
            'Content-Type: text'
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}