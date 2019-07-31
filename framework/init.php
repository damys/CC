<?php
header('Content-Type:text/html; charset=utf-8');
date_default_timezone_set('PRC');

$GLOBALS['CONF'] = include ROOT . 'config' . DS . 'config.php';

if($GLOBALS['CONF']['session']['auto_start']) {
    session_start();
    if(defined('DEBUG') && DEBUG === 1){
        framework\base\Debug::addmsg('开启会话Session, session_id：'.session_id());
    }
}

if($GLOBALS['CONF']['app_debug']){
    ini_set('display_errors', 'On');
    error_reporting(E_ALL ^ E_NOTICE);

    define('DEBUG', 1);
    include FRAMEWORK . 'base' . DS . 'Debug.php';
    framework\base\Debug::start();
    set_error_handler(array("framework\base\Debug", 'Catcher'));
} else {
    ini_set('display_errors', 'Off');
}

if($GLOBALS['CONF']['log_is']){
    ini_set('log_errors', 'On');

    $logPath   = $GLOBALS['CONF']['log_path'] . date('Y', time()) .'/'. date('m', time()) . '/';

    if(!file_exists($logPath)) mkdir($logPath, 0777, true);
    $fileName = date("Y-m-d") . '_log_errors.txt';
    if(is_file($fileName)){
        ini_set('error_log', $logPath.$fileName);
    }
}

/* ========================================================================
 * 设置平台，模块. 默认为前台，当传参为admin 则选择平台为后台，静态资源路径
 * ======================================================================== */
if($_SERVER['HTTP_HOST'] == 'admin.ccphp.com'){
    $module = 'admin';
} else {
    $module = 'home';
}
define("PLAT", $module);
define('EXT', '.php');

$module = $module === 'home' ? '' : $module.DS;

define('JS_URL', DS . 'assets'. DS .$module. 'js'. DS);
define('CSS_URL', DS . 'assets'. DS .$module. 'css'. DS);
define('IMG_URL', DS . 'assets'. DS .$module. 'img'. DS);

define("APP", ROOT . 'app' . DS);                      // 程序包（前后台）的完整路径
define("PLAT_PATH", APP . PLAT . DS);                  // 后台完整路径
define("CTRL_PATH", PLAT_PATH . 'controller' . DS);    // 当前控制器所在完整路径
define("MODEL_PATH", PLAT_PATH . 'model' . DS);
define("VIEW_PATH", PLAT_PATH . 'view' . DS);

/* ========================================================================
 * 加载公共函数库， 路由，核心框架文件
 * ======================================================================== */
include PLAT_PATH . 'functions.php';                   // 加载模块函数文件
include FRAMEWORK . 'base' . DS . 'Route.php';
include FRAMEWORK . 'cc.php';                          // 核心库

// new 类时候，就会调用这个类方法， 并运行run 方法
spl_autoload_register('cc::autoload');
cc::run();

if(defined('DEBUG') && DEBUG === 1){
    framework\base\Debug::stop();
    framework\base\Debug::message();
}