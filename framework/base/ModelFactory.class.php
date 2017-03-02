<?php

/* ========================================================================
 * 单例工厂类
 * 主要功能：存储各个模型类的唯一实例（单例）
 * ======================================================================== */

/**
 * Class ModelFactory
 */
class ModelFactory
{
    //用于存储各个模型类的唯一实例（单例）
    static $all_model = array();

    static function M($model_name)
    {
        //判断此模型是否存在，是否是实例
        if(!isset(static::$all_model[$model_name]) || !(static::$all_model[$model_name] instanceof $model_name)){
            static::$all_model[$model_name] = new $model_name();
        }
        return static::$all_model[$model_name];
    }
}