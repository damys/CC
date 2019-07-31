<?php

/* ========================================================================
 * 单例工厂类
 * 主要功能：存储各个模型类的唯一实例（单例）
 * ======================================================================== */

class Factory
{
    //用于存储各个模型类的唯一实例（单例）
    static $all = [];

    static function M($name)
    {
        //判断此模型是否存在，是否是实例
        if(!isset(static::$all[$name]) || !(static::$all[$name] instanceof $name)){
            static::$all[$name] = new $name();
        }
        return static::$all[$name];
    }
}