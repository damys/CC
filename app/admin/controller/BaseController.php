<?php

class BaseController
{

    public function __construct()
    {
        self::startCustomException();
    }

    /**
     * 开启自定义异常处理
     */
    public function startCustomException()
    {
        include_once APP . 'common' .DS . 'exception'. DS .'ExceptionHandler'.EXT;
        set_exception_handler(['ExceptionHandler', 'render']);
    }


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
     * 空方法操作的处理, 子类可以重写些方法
     */
    public function _empty()
    {
        if(isset($_POST) && count($_POST)){
            Response::error(4002,'方法不存在');
        }

        if(defined('DEBUG') && DEBUG === 1) {
            framework\base\Debug::addmsg('提示：方法不存在');
        }

        cc::notFound(4002);
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

}