<?php

class ForbiddenException extends BaseException
{
    public $errCode = 10001;
    public $errMsg  = '权限不够';
    public $code = 403;
}