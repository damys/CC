﻿<?php
return array(
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------
    'DEFAULT_CHARSET'   => 'utf-8',     // 默认输出编码
    'DEFAULT_TIMEZONE'  => 'PRC',	    // 默认时区
    'DEBUG'             => true,        // true 开启应用调试模式，false 开启生产模式
    'DEFAULT_LANg'      => 'zh-cn',     // 默认语言

    // +----------------------------------------------------------------------
    // | 数据库设置
    // +----------------------------------------------------------------------
    'DB_DSN'        => 'mysql:host=127.0.0.1;dbname=cc_test',  //dsn 设置
    'DB_TYPE'       => 'mysql',        // 数据库类型
    'DB_HOST'       => '127.0.0.1',    // 服务器地址
    'DB_NAME'       => 'test',         // 数据库名
    'DB_USER'       => 'root',         // 用户名
    'DB_PWD'        => '123456',       // 密码
    'DB_PORT'       => '3306',         // 端口号
    'DB_PREFIX'     => 'CC_',          // 数据库表前缀
    'DB_CHARSET'    => 'utf8',         // 数据库编码默认：utf8

    // +----------------------------------------------------------------------
    // | 路由设置
    // +----------------------------------------------------------------------
    'DEF_CONTROLLER'    => 'Index',
    'DEF_ACTION'        => 'Index',
    'ROUTE'=>[
        'NewsDetail'=>['News','detail']         // 短域名c-a: 院校详细。 注：不要与Controller 同名, 实际控制器大写
    ],

    // +----------------------------------------------------------------------
    // | 日志记录
    // +----------------------------------------------------------------------
    'LOG_IS'            => true,                // 开启日志, 0 为关闭日志
    'LOG_PATH'          => './runtime/logs/',   // 日志存放在目录

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------
    'SESSION_START'     => true,                 // 是否自动开户 SESSION
    'SESSION_ID'        => '',                   // SESSION ID
    'SESSION_PREFIX'    => ''                    // SESSION 前缀
);