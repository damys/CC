<?php

/* ========================================================================
 * 路由类
 * 主要功能：解析URL
 * ======================================================================== */

/**
 * Class Route
 */
class Route
{
    public $controller;
    public $action;

    /**
     * 获取URL 参数部分
     * 返回对应控制器和方法
     */
    public function __construct()
    {
        $uri = strtolower($_SERVER['REQUEST_URI']);

        if (!empty($uri) && $uri != '/') {
            $path = explode('?',$uri);    // 丢掉?以及后面的参数

            /**
             * 一、controller， action 路由设置
             */
            // 3-1. 去掉多余的分割符：/index/index => index, index
            $path = explode('/', trim($path[0],'/'));

            // 3-2.处理参数:controller
            if (isset($path[0]) && $path[0]){
                // action伪静态：可以加.html
                if(strpos($path[0], '.')){
                    $path[0] = substr($path[0],0, strpos($path[0], '.'));
                }
                $this->controller = $path[0];
            }

            // 3-3.处理参数:action 默认为：Index
            if (isset($path[1]) && $path[1]) {
                // action伪静态：可以加.html
                if(strpos($path[1], '.')){
                    $path[1] = substr($path[1],0, strpos($path[1], '.'));
                }
                $this->action = $path[1];
            } else {
                $this->action = $GLOBALS['CONF']['DEF_ACTION'];
            }


            /**
             * 短域名设置，美化域名，通过获取配置文件设置
             * 使用：1. 配置文件中修改，2. 链接地址
             * 注意：短名最好不要与控制器重名
             */
            // 短域名设置
            if(isset($GLOBALS['CONF']['ROUTE'][$this->controller])){
                $this->action = $GLOBALS['CONF']['ROUTE'][$this->controller][1];
                $this->controller = $GLOBALS['CONF']['ROUTE'][$this->controller][0];

                // 短域名设置的路由数组首位缺少一位，因为短域名缺少一位
                array_unshift($path, 0);

                // 伪静态：卸载掉除扩展名
                if(strpos($path[3], '.')){
                    $path[3] = substr($path[3],0, strpos($path[3], '.'));
                }
            }

            /**
             * 二、卸掉控制器，方法。 对post, get 提交url 解析：/id/1/name/tom
             */
            unset($path[0]);
            unset($path[1]);

            // 计算大于2个参数时，开始获取参数值
            $count = count($path) + 2;

            // 伪静态：卸掉多余(奇数)参数。
            if(0 != $count % 2){
                unset($path[$count-1]);
                $count--;
            }

            // 遍历参数
            $i = 2;
            while ($i < $count){
                $_GET[$path[$i]] = $path[$i+1];
                $i = $i + 2;
            }

        } else {
            // 如首页缺控制器，默认调用
            $this->controller = $GLOBALS['CONF']['DEF_CONTROLLER'];
            $this->action = $GLOBALS['CONF']['DEF_ACTION'];
        }
        // msg("实际调用：controller:" . $this->controller . ', ' . 'action:' . $this->action);
    }
}