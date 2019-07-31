<?php

class SessionException extends BaseException
{
    public $errCode = 10011;
    public $errMsg  = 'session 过期了';
    public $code = 404;

}