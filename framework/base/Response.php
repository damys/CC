<?php

/* ========================================================================
 * 通信数据接口类
 * 待扩展：返回系统的错误码：默认是200， 400，500....
 * ======================================================================== */

class Response
{
    const JSON = "json";

    /**
     * 成功接口返回
     * @param array $data
     * @param int $errcode
     * @param string $errmsg
     * @return string
     */
    public static function success($data = [], $errcode = 0, $errmsg = 'Success')
    {
        echo self::json($errcode, $errmsg, $data);
        exit;
    }


    /**
     * 按失败接口返回
     * @param $errcode
     * @param string $errmsg
     * @param array $data
     * @return string
     */
    public static function error($errcode, $errmsg = '', $data = [])
    {
        echo  self::json($errcode, $errmsg, $data);
        exit;
    }



    /**
     * 按综合方式输出通信数据, 停止执行下面的内容
     * @param $errcode
     * @param string $errmsg
     * @param array $data
     * @param string $type
     * @return string
     */
    public static function show($errcode, $errmsg = '', $data = [], $type = self::JSON)
    {
        if(!is_numeric($errcode)) return '';

        $type = isset($_GET['format']) ? $_GET['format'] : self::JSON;

        $content = array(
            'errcode' => $errcode,
            'errmsg'  => $errmsg,
            'data'    => $data
        );

        if($type == 'json') {
            echo self::json($errcode, $errmsg, $data);
        }
        else if($type == 'array') {
            var_dump($content);
        }
        else if($type == 'xml') {
            echo self::xmlEncode($errcode, $errmsg, $data);
        }

        exit;
    }


    /**
     * 按json方式输出通信数据
     * @param $errcode
     * @param string $errmsg
     * @param array $data
     * @return string
     */
    public static function json($errcode, $errmsg = '', $data = [])
    {
        if(!is_numeric($errcode)) return '';

        $content = array(
            'errcode'  => $errcode,
            'errmsg'   => $errmsg,
            'data'     => $data
        );

        return json_encode($content);
    }


    /**
     * 按xml方式输出通信数据
     * @param $errcode
     * @param $errmsg
     * @param array $data
     * @return string
     */
    public static function xmlEncode($errcode, $errmsg, $data = [])
    {
        if(!is_numeric($errcode)) return '';

        $content = array(
            'errcode' => $errcode,
            'errmsg'  => $errmsg,
            'data'    => $data,
        );

        header("Content-Type:text/xml");
        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<root>\n";
        $xml .= self::xmlToEncode($content);
        $xml .= "</root>";

        return $xml;
    }


    /**
     * 按xml 格式转换
     * @param $data
     * @return string
     */
    public static function xmlToEncode($data)
    {
        $xml = $attr = "";
        foreach($data as $key => $value) {
            if(is_numeric($key)) {
                $attr = " id='{$key}'";
                $key = "item";
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= is_array($value) ? self::xmlToEncode($value) : $value;
            $xml .= "</{$key}>\n";
        }

        return $xml;
    }
}