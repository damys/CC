<?php

class BaseModel
{
    protected $_dao       = null;   //用于存储数据库工具类的实例(对象)
    protected $_db_prefix = null;   // 数据库表前缀

    /**
     * 可以指定不同的数据库
     * BaseModel constructor.
     * @param null $DBName 完整的数据名：前缀+数据库
     */
    public function __construct($DBName=null)
    {
        if(file_exists(ROOT . "config/config.php")){
            $CONF = include ROOT . 'config/config.php';   //加载配置文件
        }else{
            die('config file is not find!');
        }

        // 数据库表前缀
        $this->_db_prefix = $CONF['DB_PREFIX'];

        $confs = array(
            'dsn'        => $CONF['DB_DSN'].$CONF['DB_PREFIX'].$CONF['DB_NAME'],
            'db_user'    => $CONF['DB_USER'],
            'db_pwd'     => $CONF['DB_PWD'],
            'db_charset' => $CONF['DB_CHARSET'],
            'db_port'    => $CONF['DB_PORT'],
            'log_is'     => $CONF['LOG_IS'],
            'log_path'   => $CONF['LOG_PATH'],
            'db_name'    => $CONF['DB_NAME'],
            'db_host'    => $CONF['DB_HOST']

        );

        // 可以指定不同的数据库（完整的数据名：前缀+数据库）
        if($DBName){
            $confs['dsn'] = $CONF['DB_DSN'] . $DBName;
            $confs['db_name'] = $DBName;
        }

        $this->_dao = DB_PDO::getInstance($confs);
    }
}