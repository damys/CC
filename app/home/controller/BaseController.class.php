<?php

/**
 * 基类
 * 功能1：统一测试登录，权限过滤，强制退出
 * 功能2：分配值，加载并显示页面，跳转， 防盗链，空处理
 * Class BaseController
 */
class BaseController
{

    public function __construct()
    {
        // 获取路由，并分配到模板统一使用
        $route = ModelFactory::M('Route');

        // 控制器，方法改为小写
        $route->action = strtolower($route->controller);
        $route->action = strtolower($route->action);

        $this->assign('route', $route);
    }

    /* ========================================================================
     * 功能2：分配值，加载并显示页面，跳转， 防盗链，空处理
     * ======================================================================== */

    /**
     * 分配值
     * @param $name
     * @param $value
     */
    public $assign = array();

    function assign($name, $value)
    {
        $this->assign[$name] = $value;
    }


    /**
     * 加载并显示页面
     * @param $file
     */
    function display($file)
    {
        $file = VIEW_PATH . $file;

        if(is_file($file)){
            extract($this->assign);   //找散为字符串
            include $file;
        }
    }


    /**
     * 跳转
     * @param $str
     */
    function redirect($url)
    {
        header('Location:' . $url);
    }


    /**
     * 防盗链
     * @param string $error_url 可以指定错误跳转url
     */
    public function referer($url = '')
    {
        if(isset($_SERVER['HTTP_REFERER'])){

            // 拼装Http
            $http = substr($_SERVER['HTTP_REFERER'], 0, strrpos($_SERVER['HTTP_REFERER'], ':')) == 'https' ? 'https' : 'http';
            $host = $http . '://' . $_SERVER['HTTP_HOST'];

            // 根据拼装的http 地址，如果访问地址不是本地地址，就跳转相关code 页面
            if(substr($_SERVER['HTTP_REFERER'], 0, strlen($host)) != $host){
                !empty($url) ? header("Location:" . $url) : header("Location:/code/?c=4004");exit;
            }

        } else {
            !empty($url) ? header("Location:" . $url) : header("Location:/code/?c=4004");exit;
        }
    }


    /**
     * 空方法操作的处理, 子类可以重写些方法
     */
    public function _empty()
    {
        die("空方法操作");
    }


    /**
     * 魔术方法 在不存在的操作的时候执行, 并调用_empty 方法
     * @access assets
     * @param string $method 方法名
     * @param array $args 参数
     * @return mixed
     */
    public function __call($method,$args)
    {
        if(method_exists($this, '_empty')){
            $this->_empty($method, $args);
        }

        return;
    }


    /**
     * @param $msg 显示的提示信息
     * @param $url 跳转的url 地址
     * @param int $time 页面的跳转时间，默认为3秒
     */
    function gotoUrl($msg, $url, $time=3)
    {
        echo $msg;
        echo "<a href='{$url}'>返回</a>";
        echo "<br>页面将在{$time}秒之后自动跳转";
        header("refresh: $time; url = $url");
    }
}