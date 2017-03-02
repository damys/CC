<?php

// 方式1：加载配置文件
//$CONF = include ROOT . 'config\config.php';

// 方式2：使用全局变量加载配置文件
 $GLOBALS['CONF'] = include ROOT . 'config\config.php';

//开启session
if($GLOBALS['CONF']['SESSION_START']) session_start();

//设置默认字符编码, 设置时区
header('content-type:text/html; charset=' . $GLOBALS['CONF']['DEFAULT_CHARSET']);
date_default_timezone_set($GLOBALS['CONF']['DEFAULT_TIMEZONE']);

//打开php 的错误显示
if($GLOBALS['CONF']['DEBUG']){
    ini_set('display_error', 'On');
}else {
    ini_set('display_error', 'Off');
}

//服务器时间
define('TIME', $_SERVER['REQUEST_TIME']);


/* ========================================================================
 * URL 常量化设置平台，模块. 默认为前台，当传参为admin 则选择平台为后台
 * ======================================================================== */
if($_SERVER['HTTP_HOST'] == 'admin.framework.com'){
    $p = 'admin';
} else {
    $p = 'home';
}
define("PLAT", $p);                                    //平台


/* ========================================================================
 * URL 常量化设置 程序包路径
 * ======================================================================== */
define("APP", ROOT . 'app' . DS);                      //程序包（前后台）的完整路径
define("PLAT_PATH", APP . PLAT . DS);                  //后台完整路径
define("CTRL_PATH", PLAT_PATH . 'controller' . DS);    //当前控制器所在完整路径
define("MODEL_PATH", PLAT_PATH . 'model' . DS);        //当前模型所在完整路径
define("VIEW_PATH", PLAT_PATH . 'view' . DS);          //当前视图所在完整路径





/* ========================================================================
 * 加载公共函数库， 框架加载文件，用于引导框架启动
 * ======================================================================== */
include FRAMEWORK . 'base\function.php';
include FRAMEWORK . 'base\Route.php';
include FRAMEWORK . 'CC.php';             //核心库

//当我们new 的类不存在的时候，就调用这个类， 并运行run 方法
spl_autoload_register('CC::autoload');
CC::run();






// 可以直接获取配置文件中的设置值
//include FRAMEWORK . 'base\Conf.php';
//echo Conf::get('config', 'DB_DSN');
//echo Conf::getAll('config')['DB_DSN'];






