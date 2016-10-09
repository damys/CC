<?php

class BaseModel
{
    protected $_dao = null;   //用于存储数据库工具类的实例(对象)

    public function __construct()
    {
        if(file_exists(ROOT . "config/config.php")){
            $CONF = include ROOT . 'config/config.php';   //加载配置文件
        }else{
            die('config file is not find!');
        }

        $confs = array(
            'dsn'        => $CONF['DB_DSN'],
            'db_user'    => $CONF['DB_USER'],
            'db_pwd'     => $CONF['DB_PWD'],
            'db_charset' => $CONF['DB_CHARSET'],
            'db_port'    => $CONF['DB_PORT'],
            'log_is'     => $CONF['LOG_IS'],
            'log_path'   => $CONF['LOG_PATH'],

            'db_name'        => $CONF['DB_NAME'],
            'db_host'        => $CONF['DB_HOST']

        );

        $this->_dao = DB_PDO::getInstance($confs);
    }
}