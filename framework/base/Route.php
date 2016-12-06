<?php

/* ========================================================================
 * 路由类
 * 主要功能,解析URL
 * ======================================================================== */

class Route
{
    public $controller;
    public $action;

    /**
     * 获取URL 参数部分
     * 返回对应控制器和方法
     */
    public function __construct(){

        $uri = $_SERVER['REQUEST_URI'];

//        if (!empty($uri) && $uri != '/') {
//            $path = explode('?',$uri);    // 丢掉?以及后面的参数
//
//            // 去掉多余的分割符：/index/index => index, index
//            $path = explode('/', trim($path[0],'/'));
//
//            // 处理参数:对post, get 提交url 解析：/id/1/name/tom
//            if (isset($path[0]) && $path[0]){
//                $this->controller = $path[0];
//                unset($path[0]);                //卸掉控制器，方法名
//            }
//
//            if (isset($path[1]) && $path[1]) {
//                $this->action = $path[1];
//                unset($path[1]);
//            } else {
//                $this->action = 'Index';
//            }
//
//            // 计算大于2个参数时，开始获取参数值
//            $count = count($path) + 2;
//            $i = 2;
//            while ($i < $count){
//                $_GET[$path[$i]] = $path[$i+1];
//                $i = $i + 2;
//            }
//            // msg($path);
//
//
//        } else {
//            $this->controller = 'Index';
//            $this->action = 'Index';
//        }

        if (!empty($uri) && $uri != '/') {
            $path = explode('?',$uri);    // 丢掉?以及后面的参数

            /**
             * controller， action 路由设置
             */
            // 3-1. 去掉多余的分割符：/index/index => index, index
            $path = explode('/', trim($path[0],'/'));

            // 3-2.处理参数:controller
            if (isset($path[0]) && $path[0]){
                $this->controller = $path[0];
            }

            // 3-3.处理参数:action 默认为：Index
            if (isset($path[1]) && $path[1]) {
                $this->action = $path[1];
            } else {
                $this->action = 'Index';
            }


            /**
             * 短域名设置，美化域名，通过获取配置文件设置
             */
            // 1. 获取配置文件中
            include FRAMEWORK . 'base\Conf.php';
            $CONF =  Conf::get('config', 'ROUTE');

            // 2. 短域名设置
            if(isset($CONF[$this->controller])){
                $this->action = $CONF[$this->controller][1];
                $this->controller = $CONF[$this->controller][0];

                // 短域名设置的路由数组首位缺少一位，因为短域名缺少一位
                array_unshift($path, 0);
            }

            // 3. 卸掉控制器，方法。 对post, get 提交url 解析：/id/1/name/tom
            unset($path[0]);
            unset($path[1]);

            // 4. 计算大于2个参数时，开始获取参数值
            $count = count($path) + 2;
            $i = 2;
            while ($i < $count){
                $_GET[$path[$i]] = $path[$i+1];
                $i = $i + 2;
            }

        } else {
            // 如首页缺控制器，默认调用
            $this->controller = 'Index';
            $this->action = 'Index';
        }

        // msg("实际调用：controller:" . $this->controller . ', ' . 'action:' . $this->action);
    }
}