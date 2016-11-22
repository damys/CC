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

            'db_name'    => $CONF['DB_NAME'],
            'db_host'    => $CONF['DB_HOST']

        );

        $this->_dao = DB_PDO::getInstance($confs);
    }

    /**
     * 根据管理员id 获得用户可以访问的控制器和方法
     * @param $session_manager_id
     * @return mixed
     */
    public function getRole_auth_acByManager_id($manager_id){
        $sql = "select role_auth_ac from mcools_admin_manager a join mcools_admin_role b on a.manager_role_id = b.role_id where a.manager_id = $manager_id";

        return $this->_dao->fetch($sql, null);
    }

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