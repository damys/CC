<?php

class BaseModel
{
    protected $_dao       = null;   // 用于存储数据库工具类的实例(对象)
    protected $_prefix = null;      // 数据表前缀

    /**
     * 可以指定不同的数据库
     * @param null $DBName 完整的数据名：前缀+数据库
     */
    public function __construct($DBName = null)
    {
        if(file_exists(ROOT . "config/db.php")){
            $CONF = include ROOT . 'config/db.php';

            if(!isset($CONF['mysql'])) {
                die('Config File Unfound Mysql');
            }
        }
        else {
            die('Config File Unfound!');
        }

        $conf = $CONF['mysql'];

        // 数据表前缀
        $this->_prefix = $conf['prefix'];

        $confs = array(
            'dsn'        => 'mysql:host='.$conf['host'].';dbname=',
            'port'       => $conf['port'],
            'dbname'     => $conf['dbname'],
            'username'   => $conf['username'],
            'password'   => $conf['password'],
            'charset'    => $conf['charset'],

            'log_is'     => $conf['log_is'],
            'log_path'   => $conf['log_path'],
        );

        // 可以指定不同的数据库（完整的数据名：前缀+数据库）
        if($DBName) {
            $confs['dbname'] = $DBName;
        }

        $this->_dao = DB_PDO::getInstance($confs);
    }
}