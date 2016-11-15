<?php

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

        if (!empty($uri) && $uri != '/') {
            $path = explode('?',$uri);    // 丢掉?以及后面的参数

            // 去掉多余的分割符：/index/index => index, index
            $path = explode('/', trim($path[0],'/'));

            // 处理参数:对post, get 提交url 解析：/id/1/name/tom
            if (isset($path[0]) && $path[0]){
                $this->controller = $path[0];
                unset($path[0]);                //卸掉控制器，方法名
            }

            if (isset($path[1]) && $path[1]) {
                $this->action = $path[1];
                unset($path[1]);
            } else {
                $this->action = 'Index';
            }

            // 计算大于2个参数时，开始获取参数值
            $count = count($path) + 2;
            $i = 2;
            while ($i < $count){
                $_GET[$path[$i]] = $path[$i+1];
                $i = $i + 2;
            }
            // msg($path);


        } else {
            $this->controller = 'Index';
            $this->action = 'Index';
        }
    }
}