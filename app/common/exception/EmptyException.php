<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15 0015
 * Time: 17:36
 */
namespace framework\exception;

class EmptyException extends BaseException
{
    public $errCode = 1001;
    public $errMsg = '不能为空';
    public $code = 200;

}