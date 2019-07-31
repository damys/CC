<?php

/* ========================================================================
 * 配置类： 全局变量加载配置文件
 * 主要功能：加载系统配置,如果之前已经加载过,那么就直接返回
 * ======================================================================== */

class Config
{
    static public $conf = [];

    /**
     * 加载系统配置,如果之前已经加载过,那么就直接返回
     * @param $name 配置名
     * @param string $file 文件名
     * @return bool
     */
    public static function get($name, $file='config.inc')
    {
        if(isset(self::$conf[$file][$name])) {
            return self::$conf[$file][$name];
        }

        $conf = ROOT . 'config' . DS . $file . '.php';

        if(is_file($conf)) {
            self::$conf[$file] = include $conf;
            return isset(self::$conf[$file][$name]) ? self::$conf[$file][$name] : false;
        }
        else {
            return false;
        }
    }


    /**
     * 加载系统配置文件(直接加载整个配置文件),如果之前已经加载过,那么就直接返回
     * @param $file='config.icn' 文件名
     * @return mixed
     */
    public static function getAll($file='config.inc')
    {
        if(isset(self::$conf[$file])) {
            return self::$conf[$file];
        }

        $conf = ROOT . 'config/' . $file . '.php';

        if(is_file($conf)) {
            self::$conf[$file] = include $conf;
            return self::$conf[$file];
        }
        else {
            return false;
        }
    }
}