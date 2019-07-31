<?php

/**
 * 模块使用前，需先在基类中引用 ExcetionHandler 并调用其方法render
 * Class BaseException
 */

class BaseException extends Exception
{
    public $errCode  = 999;                   // 自定义错误码
    public $errMsg   = 'invalid parameters';
    public $code     = 400;                   // http 状态码


    /**
     * 构造函数，接收一个关联数组, 否则使用基类的信息
     * @param array $params 关联数组只应包含errCode,errMsg和code，且不应该是空值
     */
    public function __construct($params=[])
    {
        if(!is_array($params)){
            return;
        }

        if(array_key_exists('errCode', $params)){
            $this->errCode = $params['errCode'];
        }

        if(array_key_exists('errMsg', $params)){
            $this->errMsg = $params['errMsg'];
        }

        if(array_key_exists('code', $params)){
            $this->code = $params['code'];  // http 状态码: 200,400,404,500
        }
    }
}