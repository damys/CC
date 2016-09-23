<?php
///////////////测试用////////////////////
function microtime_float(){
    list($usec, $sec) = explode(' ', microtime());   //microtime() 返回：毫秒 时间戳 0.63559400 1469065900
    return ((float)$usec + (float)$sec);
}
$d1 = microtime_float();




define('DS', DIRECTORY_SEPARATOR);                //目录分隔符,自动匹配。win:\, unix:/
define('ROOT', __DIR__ . DS);                     //根目录
define('FRAMEWORK', ROOT . 'framework' . DS);     //框架目录


/* ========================================================================
 *  URL 静态资源js, css, img 路径
 * ======================================================================== */
define('SITE_URL', 'http://www.framework.com/');                  //网址域名,注意修改
define('JS_URL', SITE_URL . 'assets/home/js/');            //前台静态资源路径,默认不带平台名
define('CSS_URL', SITE_URL. 'assets/home/css/');
define('IMG_URL', SITE_URL. 'assets/home/img/');
define('ADMIN_JS_URL', SITE_URL . 'assets/admin/js/');     //后台静态资源路径
define('ADMIN_CSS_URL', SITE_URL. 'assets/admin/css/');
define('ADMIN_IMG_URL', SITE_URL. 'assets/admin/img/');
define('IMG_UPLOAD', SITE_URL . 'upload/');                 //文件上传路径



include FRAMEWORK . 'init.php';

///////////////测试用////////////////////
$d2 = microtime_float();
msg("动行消耗时间：" . ($d2-$d1));


/*
1. php文件名说明 ：
    1）php 类名：首字母大写
    2）非 php 类名（只有函数无类）：全部小写
    3）Model, Controller 文件名：添加： .class.php， 如：IndexController.class.php
    4）第三方库，自定义库：.class.php， 如：DB_PDO.class.php


2. URL
    1. 在服务器增加：.htaccess文件 隐藏index.php
        xxx.com/index/index  实际访问xxx.com/index.php/index/index

    2. 请求URL: xxx.com/index/demo
        实际访问：控制器是：index, 控制方法是：demo

    注：后台：增加参数p=admin, 默认为前台


3. 配置文件
    $CONF = include ROOT . 'config\config.php';   //加载配置文件
    直接通过获取：$CONF['XXX']


*/