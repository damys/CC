<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15 0015
 * Time: 17:36
 */

class IntegerException extends BaseException
{
    public $errCode = 1002;
    public $errMsg = '必须为整型';
    public $code = 200;

}