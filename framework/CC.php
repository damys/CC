<?php

class CC
{
    public static $classMap = array();
    public $assign = array();

    static public function run(){
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
            die('找不到控制器:' . $controller);
        }

        //msg($Controller);
    }

    //加载类
    static public function autoload($class){
        //存放已经实例化的类, 如有已存在直接返回
        if (isset($classMap[$class])) {
            return true;
        } else {

            //将反斜线转为正斜线： \index\index  ==> index\index  ==> index/index.html
            $file = str_replace('\\', '/', $class);

            //msg( '已加载文件：' . $file);

            //预加载类
            $base_class = array('DB_PDO', 'ModelFactory', 'Response', 'Cookie');
            //自动加载类库
            if (in_array($class, $base_class)) {
                if($class === 'DB_PDO' ){
                    $class = 'db/' . $class;
                }else{
                    $class = 'base/' . $class;
                }

                require_once FRAMEWORK . $class . '.class.php';

            }else if(substr($file, -5) == "Model"){
                require MODEL_PATH . $class . '.class.php';
                self::$classMap[$class] = $class;

            }else if(substr($file, -10) == "Controller"){
                require CTRL_PATH . $class . '.class.php';
                self::$classMap[$class] = $class;

            }else{
                return false;
            }


        }
    }
}