<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15 0015
 * Time: 17:36
 */

class MissException extends BaseException
{
    public $errCode = 10001;
    public $errMsg = 'Your required resource are not found';
    public $code = 404;
}