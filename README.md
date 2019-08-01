# CCPHP Web Programming Framework For PHP

# CCPHP php 框架
## V: PHP Framework 1.2.x

### 对配置文件，应用共用模块目录结构做了相关调整


## 目录结构
```
├─framework 框架核心目录
│    ├─base                公共类库目录
│        ├─cache.php       缓存文件
│        ├─config.php      获取配置文件助手
│        ├─Factory.php     单例工厂类
│        ├─error.php       错误码设置
│        ├─response.php    通信数据类
│        ├─route.php       路由处理器
│    ├─db                  数据库目录
│    ├─http                http 目录
│    ├─init.php            初始化项目
│    └─cc.php              核心框架
├─app 应用目录
│    ├─common              公用模块
│    ├─home                home 模块
│    ├─admin               admin 模块
│        ├─controller      控制器目录
│        ├─model           模型目录 
│        ├─view            视图目录 
│        ├─exception       自定异常 
│        ├─enum            枚举状态
│        ├─serive          服务类
│        ├─EC.php          状态状态码
│        └─functions.php   公共函数
├─config                    全局配置目录
├─extend                    扩展类库目录
├─vendor                    第三方类库目录（Composer依赖库）
├─www                       WEB目录（对外访问目录）
│    ├─assets              默认home 资源目录
│        ├─css
│        ├─js
│        ├─images
│        ├─upload
│        └─admin admin   模块资源目录
│            ├─css
│            ├─js
│            └─images
│    ├─.htaccess          用于apache的重写
│    └─index.php          入口文件
└─runtime                  运行目录/临时文件
```

## session
### db session
```
使用前，请创建数据库表。下面是在MySQL中创建cc_session表的示例
CREATE TABLE `cc_session` (
    `session_id` varchar(255) binary NOT NULL default '',
    `session_expires` int(10) unsigned NOT NULL default '0',
    `session_data` text,
    PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM;
```
