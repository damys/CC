<?php

class UserException extends BaseException
{
    public $errCode = 20000;
    public $errMsg  = '用户不存在';
    public $code = 404;
}