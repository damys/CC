<?php

/**
 * 基本处理异常类
 * Class EException
 */

class EException extends BaseException
{
    public $errCode = 1;
    public $errMsg  = '未知错误';
    public $code    = 200;
}