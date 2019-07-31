<?php

class TokenException extends BaseException
{
    public $errCode = 10001;
    public $errMsg  = 'token已过期或无效token';
    public $code = 401;
}