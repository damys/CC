<?php

/*
 * 自定义异常消息
 */
class ExceptionHandler
{
    public static $errCode;
    public static $errMsg;
    public static $code;

    public static function render(Exception $e) {
        // 自定义异常，则控制http状态码，不需要记录日志
        // 因为这些通常是因为客户端传递参数错误或者是用户请求造成的异常,不应当记录日志
        if ($e instanceof BaseException) {
            self::$errCode = $e->errCode;
            self::$errMsg  = $e->errMsg;
            self::$code    = $e->code;
        }
        // 服务器未处理的异常，将http状态码设置为500，并记录日志
        else {
            // 系统未知异常处理
            if($e->getCode() && $e->getCode() < 0) {
                self::$errCode  = ECode::ERR_UNKNOWN[0];
                self::$errMsg   = ECode::ERR_UNKNOWN[1];
                self::$code     = ECode::ERR_UNKNOWN[2];
            }
            // 非未知使用500 错误
            else {
                self::$errCode = $e->getCode() != 0 ? $e->getCode() : ECode::ERR_500[0];
                self::$errMsg  = $e->getMessage() ? $e->getMessage() : ECode::ERR_500[1];
                self::$code    = ECode::ERR_500[2];
            }

            // 记录日志
            if(defined('DEBUG') && DEBUG === 1){
                framework\base\Debug::addmsg(json_encode([
                    'msg'         => self::$errMsg,
                    'error_code'  => self::$errCode,
                    'request_url' => $_SERVER['REQUEST_URI'],
                    'data'        => []
                ]));
                framework\base\Debug::stop();
                framework\base\Debug::message();
            }
        }

        // 设置服务器状态码, 参数:可以自定义页面
        headerCode(self::$code);

        $result = [
            'errCode'    => self::$errCode,
            'errMsg'     => self::$errMsg,
            'request_url' => $_SERVER['REQUEST_URI'],
            'data'        => []
        ];

        echo json_encode($result);
    }
}