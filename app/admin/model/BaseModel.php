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

    /**
     * 根据传入的字段，实现参数绑定， 用于update 语句
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

}