# CCPHP Web Programming Framework For PHP

V: PHP Framework 1.1.x

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
    方式1：加载配置文件
    $CONF = include ROOT . 'config\config.php';
    直接通过获取：$CONF['XXX']
    
    方式2：使用全局变量加载配置文件
    $GLOBALS['CONF'] = include ROOT . 'config\config.php';
    直接通过获取：$GLOBALS['CONF']['DEFAULT_CHARSET'];

## 目录结构
---
CC/	框架目录

├── app         应用代码

├── assets      静态文件：images,css,js

├── config      程序配置或数据库配置

├── framework   框架主代码

├── runtime     临时数据目录

├── index.php   入口文件

├── .htaccess   重定向


## 测试数据库
    CREATE TABLE `CC_test` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(32) NOT NULL,
      `age` int(11) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    
    
    INSERT INTO `CC_test` VALUES ('1', 'tom', '26');
    INSERT INTO `CC_test` VALUES ('2', 'jack', '27');
    INSERT INTO `CC_test` VALUES ('3', 'rose', '28');
    INSERT INTO `CC_test` VALUES ('4', 'json', '29');
    INSERT INTO `CC_test` VALUES ('5', 'damys', '30');


## 数据参数绑定
---
### 根据传入的字段，实现参数绑定， 用于insert 语句
   

    /**
     * 根据传入的字段，实现参数绑定， 用于insert 语句
     * @param $param  array
     * @return array
     *
     * 在sql 语句的拼接。如：table(:name,:age) values (name,age). 调用： $kv = $this->kv($param);
     *
        public function addManager($param){
            $ii = $this->ii($param);
            $sql = "insert into $this->_table({$ii['k']}) values ({$ii['v']})";

            return $this->_dao->exec($sql, $ii['data']);
        }
     *
     */
    function ii($param){
        $k = '';   // name
        $v = '';   // :name

        foreach($param as $key => $value){
            $k .= "$key,";
            $v .= ":$key,";
            $data[":$key"] = $value;  // 对数据进行拼接。如：array(':name' => 'jack')
        }
        // 去掉最后一个符号','
        $k = rtrim($k,',');
        $v = rtrim($v,',');

        return array('k'=>$k, 'v'=>$v, 'data'=>$data);
    }


### 根据传入的字段，实现参数绑定， 用于update 语句

    /**
     * 根据传入的字段，实现参数绑定， 用于update 语句。操作时需要卸载id,增加参数id
     * @param $param
     * @return array
     *
     * 在sql 语句的拼接。如：table set(:name=name, :age=age) 调用：
     *
        public function updateManagerById($param, $manager_id){

            $uu = $this->uu($param);
            $sql = "update $this->_table set {$uu['kv']} where manager_id = :manager_id";
            $uu['data']['manager_id'] = $manager_id;

            return $this->_dao->exec($sql, $uu['data']);
        }
     *
     */
    function uu($param){

        $kv = '';
        foreach($param as $key => $value){
            $kv .="$key=:$key,";       // name = :name
            $data[":$key"] = $value;   // 对数据进行拼接。如：array(':name' => 'jack')
        }
        // 去掉最后一个符号','
        $kv = rtrim($kv,',');

        return array( 'kv'=>$kv, 'data'=>$data);
    }


## 模型，控制器，方法
---
## 增加公共Model

    // 加载：共公model 是在根目录下
    if(file_exists(ROOT .'model'. DS . $class . '.class.php')){
        require ROOT .'model'. DS . $class . '.class.php';

    } else {
        // 加载：前后台model
        if(file_exists(MODEL_PATH . $class . '.class.php')){
            require MODEL_PATH . $class . '.class.php';
        }
    }


## .html加载公共块

    <?php include VIEW_PATH."tpl/footer.html"; ?>


## BaseController 给前台设置并分配路由（控制器，方法）到模板统一使用

    $route = ModelFactory::M('Route');
    $this->assign('route', $route);
	

	// 使用：
	$route->controller;
	$route->action;
	
	
## 问题
### 1. linux 严格区分大小写，平台路由(正反斜杠)
<pre>
ModelFactory:M('AbcModel')  
$this->display('/register/index.html')
</pre>