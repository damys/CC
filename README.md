# CC FRAMEWORK FOR PHP

---
## php文件名说明 ：

    1）php 类名：首字母大写

    2）非 php 类名（只有函数无类）：全部小写

    3）Model, Controller 文件名：添加： .class.php， 如：IndexController.class.php

    4）第三方库，自定义库：.class.php， 如：DB_PDO.class.php


## URL
---

    1. 在服务器增加：.htaccess文件 隐藏index.php
        xxx.com/index/index  实际访问xxx.com/index.php/index/index

    2. 请求URL: xxx.com/index/demo
        实际访问：控制器是：index, 控制方法是：demo

    注：后台：增加参数p=admin, 默认为前台


## 配置文件
---
    $CONF = include ROOT . 'config\config.php';   //加载配置文件
    直接通过获取：$CONF['XXX']

## 目录结构
---
CC/	框架目录

├── app

├── assets

├── config

├── framework

├── runtime

├── index.php

├── .htaccess

## 测试数据库
    CREATE TABLE `test` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(32) NOT NULL,
      `age` int(11) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    
    
    INSERT INTO `test` VALUES ('1', '王四', '26');
    INSERT INTO `test` VALUES ('2', '王五', '27');
    INSERT INTO `test` VALUES ('3', '王六', '28');
    INSERT INTO `test` VALUES ('4', '王七', '29');
    INSERT INTO `test` VALUES ('5', '王八', '30');

