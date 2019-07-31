<?php

/* ========================================================================
 * CC 框架类
 * 主要功能：加载所需要的类
 * ======================================================================== */

class cc
{
    public static $classMap = [];

    /**
     * 运行预约加载类
     */
    public static function run()
    {
        $route = Route::getInstance();  // 单例模式

        $controller = $route->controller;
        $action     = $route->action;

        //找到对应控制器
        $controllerFile = CTRL_PATH . $controller . "Controller.php";

        if (is_file($controllerFile) && file_exists($controllerFile)) {
            include_once $controllerFile;

            $Controller = $controller . 'Controller';  // 拼装controller: Index + Controller
            $Action = $action . 'Action';              // 拼装action：　Index + Action

            $ctrl = new $Controller();
            $ctrl->$Action();
        }
        else {
            // 如果是以参数的方式请求，以接口的方式提示
            if(isset($_POST) && count($_POST)){
                echo json_encode([
                    'code'=>4001,
                    'msg' =>'控制器不存在'
                ]);

                return;
            }

            // 如果是静态资料目录，直接返回错误码
            if($controller === 'Assets') {
                headerCode(404);
                self::notFound(404);
                exit;
            }

            // 调试模式
            if(defined('DEBUG') && DEBUG === 1) {
                framework\base\Debug::addmsg('提示：控制器不存在' . $controllerFile);
            }

            self::notFound(4001);
        }
    }


    /**
     * 按需加载类
     * @param $class
     * @return bool
     */
    public static function autoload($class)
    {
        // 存放已经实例化的类, 如已存在直接返回， 否则加载
        if (isset($classMap[$class])) {
            return true;
        }
        else {
            self::_loadFile($class);
        }
    }


    /**
     * 解析加载文件
     * @param $class
     */
    private static function _loadFile($class)
    {
        // 文件名，除掉了.php 如：BaseController
        $fileName = str_replace('\\', '/', $class);
        $filePath = '';

        // 预加载类
        $base_class = array('DB_PDO', 'Config', 'Cache', 'Factory', 'Response','Cookie');

        // 加载：预加载类
        if (in_array($class, $base_class)) {
            if($class === 'DB_PDO' ){
                // 目录：db
                $class = 'db' . DS . $class;
            }
            else if($class === 'Config') {
                $class = 'common' . DS . $class;
            }
            else if($class === 'Cookie') {
                $class = 'http' . DS . $class;
            }
            else {
                $class = 'base' . DS . $class;
            }

            $filePath = FRAMEWORK . $class . EXT;
        }
        // 加载 Model 加载顺序：先模块下的model, 后共公的 model
        else if(substr($fileName, -5) == "Model") {
            // 加载模块下 model
            if(file_exists(MODEL_PATH . $class . EXT)) {
                $filePath = MODEL_PATH . $class . EXT;
            }
            else {
                // 加载共公的 /common/model
                if(file_exists(APP .'common'. DS .'model'. DS . $class . EXT)){
                    $filePath = APP .'common'. DS .'model'. DS . $class . EXT;
                }
            }
        }
        // 加载 Controller
        else if(substr($fileName, -10) == "Controller") {
            $filePath = CTRL_PATH . $class . EXT;
        }
        // 加载 Service
        else if(substr($fileName, -7) == 'Service') {
            $filePath = PLAT_PATH . 'service' . DS . $class . EXT;
        }
        // 加载 errorCode
        else if(substr($fileName, -9) == 'EC') {
            $filePath = PLAT_PATH . $class . EXT;
        }
        // 加载公共 exception
        else if(substr($fileName, -9) == 'Exception') {
            $filePath = APP . 'common'. DS . 'exception'. DS . $class . EXT;
        }
        // 加载公共 enum
        else if(substr($fileName, -4) == 'Enum') {
            $filePath = APP . 'common'. DS .'enum' . DS . $class . EXT;
        }
        // 否则加载，(第二级段，如果是命名空间) 在根目录找（可以定义一个目录列表）
        else {
            return false;
        }

        // 加载文件
        if(file_exists($filePath) && is_file($filePath)) {
            include_once $filePath;
            self::$classMap[$class] = $class;
        }

        // 调试模式
        if(defined('DEBUG') && DEBUG === 1) {
//            framework\base\Debug::addmsg('已自动加载文件：' . $fileName .'  ['.$filePath.']', 1);
        }
    }


    /**
     * 按错误码加载相关提示页面
     * @param $errorCode
     * @return mixed
     */
    public static function notFound($errorCode)
    {
        /**
         * 4001 自定义 Not Found controller
         * 4002 自定义 Not Found action
         */
        switch ($errorCode) {
            case '4001':
                header("HTTP/2.0 404 Not Found");
                return include (VIEW_PATH .'error/empty_controller.html');
                break;
            case '4002':
                header("HTTP/2.0 404 Not Found");
                return include (VIEW_PATH .'error/empty_action.html');
                break;
            case '404':
                header("HTTP/2.0 404 Not Found");
                return include (VIEW_PATH .'error/404.html');
                break;
            case '500':
                header("HTTP/2.0 500 Not Found");
                return include (VIEW_PATH .'error/500.html');
                break;

            default:
                header("HTTP/2.0 500 Not Found");
                return include (VIEW_PATH .'error/500.html');
                break;
        }
    }

}