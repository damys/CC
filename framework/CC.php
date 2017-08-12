<?php

/* ========================================================================
 * CC 框架类
 * 主要功能：加载所需要的类
 * ======================================================================== */

/**
 * Class CC
 */
class CC
{
    public static $classMap = array();
    public $assign = array();

    /**
     * 运行预约加载类
     */
    static public function run()
    {
        $route = new Route();   //new 的类不存是会调用load 加载该类

        $controller = $route->controller;
        $action = $route->action;

        //找到对应控制器
        $controllerFile = CTRL_PATH . $controller . "Controller.class.php";

        if (is_file($controllerFile)) {
            include $controllerFile;
            $Controller = $controller . 'Controller';  //拼装controller: Index + Controller
            $Action = $action . 'Action';              //拼装action：　Index + Action

            $ctrl = new $Controller();
            $ctrl->$Action();
        } else {
            // header("location:/code/?c=4001"); exit;
            include (VIEW_PATH .'code/index.html'); exit;
            // die('找不到控制器:' . $controller);
        }
    }


    /**
     * 按需加载类
     * @param $class
     * @return bool
     */
    static public function autoload($class)
    {
        // 存放已经实例化的类, 如有已存在直接返回
        if (isset($classMap[$class])) {
            return true;
        } else {
            // 将反斜线转为正斜线： \index\index  ==> index\index  ==> index/index.html
            $file = str_replace('\\', '/', $class);

            // 预加载类
            $base_class = array('DB_PDO', 'ModelFactory', 'Response', 'Page', 'ImgUpload', 'Session');

            // 自动加载类库
            if (in_array($class, $base_class)) {
                if($class === 'DB_PDO' ){
                    $class = 'db' . DS . $class;
                }else{
                    $class = 'base' . DS . $class;
                }

                require_once FRAMEWORK . $class . '.class.php';

            }else if(substr($file, -5) == "Model"){

                // 加载：共公model 是在根目录下
                if(file_exists(ROOT .'model'. DS . $class . '.class.php')){
                    require ROOT .'model'. DS . $class . '.class.php';

                } else {
                    // 加载：前后台model
                    if(file_exists(MODEL_PATH . $class . '.class.php')){
                        require MODEL_PATH . $class . '.class.php';
                    }
                }

                self::$classMap[$class] = $class;

            }else if(substr($file, -10) == "Controller"){
                require CTRL_PATH . $class . '.class.php';
                self::$classMap[$class] = $class;

            }else{
                return false;
            }
//            msg( '已加载文件(CC.php)：' . $file);
        }
    }
}