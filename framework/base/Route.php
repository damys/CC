<?php

/* ========================================================================
 * 路由类
 * 主要功能：解析URL
 * ======================================================================== */

class Route
{
    public $controller        = null;
    public $action            = null;
    public $defController     = null;
    public $defAction         = null;
    public $route             = [];
    private static $_instance = null;


    /**
     * 获取URL 参数部分
     * 返回对应控制器和方法
     * 注： 支持linux（严格区分大小写）路由
     */
    public function __construct()
    {
        self::loadRouteConfig();
        self::init();
    }

    /**
     * 单例模式
     * 判断：如果没有实例化，就先实例化
     */
    public static function getInstance()
    {
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * init
     */
    private function init()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (!empty($uri) && $uri != '/')
        {
            $path = explode('?',$uri);    // 丢掉?以及后面的参数

            // 一、controller， action 路由设置
            // 1.1 去掉多余的分割符：/index/index => Index, Index
            $path = explode('/', trim($path[0],'/'));

            // 1.2 处理参数:controller
            if (isset($path[0]) && $path[0]) {
                $this->controller = ucfirst($path[0]);
            }
            else {
                // default controller
                $this->controller = ucfirst($this->route['def_controller']);
            }

            // 1.3 处理参数:action 默认为：Index
            if (isset($path[1]) && $path[1]) {
                // 伪静态：可以加.html
                if(strpos($path[1], '.')){
                    $path[1] = substr($path[1],0, strpos($path[1], '.'));
                }
                $this->action = strtolower($path[1]);
            }
            else {
                // default action
                $this->action = strtolower($this->route['def_action']);
            }

            /**
             * 短域名设置，美化域名，通过获取配置文件设置
             * 使用：1. 配置文件中修改，2. 链接地址
             * 注意：短名最好不要与控制器重名
             */
            // 短域名设置
            if(isset($this->route['route'][$this->controller])) {
                $this->action     = $this->route['route'][$this->controller][1];
                $this->controller = ucfirst($this->route['route'][$this->controller][0]);

                // 短域名设置的路由数组首位缺少一位，因为短域名缺少一位
                array_unshift($path, 0);

                // 伪静态：卸载掉除扩展名
                if(isset($path[3]) && strpos($path[3], '.')) {
                    $path[3] = substr($path[3],0, strpos($path[3], '.'));
                }
            }

            // 二、卸掉控制器，方法。 对post, get 提交url 解析：/id/1/name/tom
            unset($path[0]);
            unset($path[1]);

            // 计算大于2个参数时，开始获取参数值
            $count = count($path) + 2;

            // 伪静态：卸掉多余(奇数)参数。
            if(0 != $count % 2) {
                unset($path[$count-1]);
                $count--;
            }

            // 遍历参数
            $i = 2;
            while ($i < $count){
                $_GET[$path[$i]] = $path[$i+1];
                $i = $i + 2;
            }

            if(defined('DEBUG') && DEBUG === 1) {
                framework\base\Debug::addmsg("请求的参数：" . json_encode($_GET), 1);
            }
        }
        else {
            // 如首页缺控制器，默认调用
            $this->controller = ucfirst($this->route['def_controller']);
            $this->action = strtolower($this->route['def_action']);
        }

        if(defined('DEBUG') && DEBUG === 1) {
            framework\base\Debug::addmsg("调用控制器方法：" . $this->controller . '/' . $this->action,1);
        }
    }


    /**
     * 加载自己模块下路由配置文件，否则使用默认的
     */
    public function loadRouteConfig()
    {
        if(file_exists(PLAT_PATH . 'route' . EXT)) {
            $this->route = include PLAT_PATH . 'route' . EXT;
        }
        else {
            $this->route = [
                'def_controller'           => 'Index',
                'def_action'               => 'index',
            ];
        }
    }


    /**
     * 禁止
     */
    public function __clone()
    {
        // TODO: Implement __clone() method.
    }
}