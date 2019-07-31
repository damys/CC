<?php

class ParameterException extends BaseException
{
    public $errCode = 10000;
    public $errMsg  = '参数错误';
    public $code = 400;
}