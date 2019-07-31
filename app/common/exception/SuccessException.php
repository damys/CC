<?php

/**
 * 暂时不用，用直接加返回
 *
 * 创建成功（如果不需要返回任何消息）
 * 201 创建成功，
 * 202 需要一个异步的处理才能完成请求
 */
class SuccessException extends BaseException
{
    public $errCode = 0;
    public $errMsg  = 'Success';
    public $code = 201;
}