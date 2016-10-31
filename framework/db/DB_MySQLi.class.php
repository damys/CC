<?php

class DB_MySQLi
{
    private $db_host          = null;
    private $db_name          = null;
    private $db_user          = null;
    private $db_pwd           = null;
    private $db_port          = null;
    private $db_charset       = null;
    private $log_is           = 0;
    private $log_path         = null;

    private static $_instance = null;
    private $_transTimes      = 0;
    private $_mysqli          = null;
    private $_result          = null;
    private $_handle          = null;
    private $_stmt            = null;

    /**
     * 构造函数：
     * 记录执行的时间戳
     * 获取数据库配置信息
     * 开启日志
     * 连接数据库
     */
    private function __construct($confs){
        //获取数据库配置信息
        $this->db_host       = $confs['db_host'];
        $this->db_name       = $confs['db_name'];
        $this->db_user       = $confs['db_user'];
        $this->db_pwd        = $confs['db_pwd'];
        $this->db_port       = $confs['db_port'];
        $this->db_charset    = $confs['db_charset'];
        $this->log_is        = $confs['log_is'];
        $this->log_path      = $confs['log_path'];

        //开启日志
        if($this->log_is){
            $handle = fopen($this->log_path . "dblog.txt", "a+");
            $this->_handle = $handle;
        }

        //连接数据库
        try {
            $this->_mysqli = self::connect();
        } catch (Exception $e) {
            self::halt('DB Connect Error! ' . $e->getMessage());
        }

    }


    /**
     * 析构函数：
     * 关闭log 文件handle
     * 释放结果集所占资源
     * 关闭数据库连接
     */
    public function __destruct(){
        //$use_time = ($this->microtime_float() - $this->time);
        //self::write_log("完成整个查询任务所用的时间为 " . $use_time);

        if($this->log_is){
            fclose($this->_handle);
        }

        self::free();
        self::close();
    }


    /**
     * 连接数据库
     */
    protected function connect(){
        $this->_mysqli = new MySQLi($this->db_host, $this->db_user, $this->db_pwd, $this->db_name);
        //$this->_mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 2);  //设置超时时间

        if($this->_mysqli->connect_errno){
            self::halt('DB Connect Error! ' . $this->_mysqli->connect_error);
        }

        //设置字符编码
        $this->_mysqli->query('set names utf8');

        return $this->_mysqli;
    }


    /**
     * 单例模式
     * 判断：如果没有实例化，就先实例化
     */
    public static function getInstance($confs){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self($confs);
        }

        return self::$_instance;
    }

    // +---------------------常用性数据操作--------------------------------------------
    /**
     * 获取1条数据, 为关联一维数组
     * @param $sql 
     * @param int $fetch_type 数组的类型，默认为关联数组, 可选MYSQLI_BOTH, MYSQLI_NUM 索引
     * @return array  返回数组
     */
    public function getRow($sql, $fetch_type = MYSQLI_ASSOC){
        //清空结果集
        if($this->_result){
            self::free();
        }

        $this->_result = $this->_mysqli->query($sql);

        return $this->_result->fetch_array($fetch_type);
    }


    /**
     * 获取全部数据集, 按关联二维组数返回
     * @param $sql 
     * @param int $fetch_type 数组的类型，默认为关联数组, 可选MYSQLI_BOTH, MYSQLI_NUM 索引
     * @return array  返回数组
     */
    public function getAll($sql, $fetch_type = MYSQLI_ASSOC){
        //清空结果集
        if($this->_result) {
            self::free();
        }

        $this->_result = $this->_mysqli->query($sql);

        $data = array();
        //判断返回结果集中行的数目
        if($this->_result && $this->_result->num_rows > 0){
            while($row = $this->_result->fetch_array($fetch_type)){
                $data[] = $row;
            }
        }

        //释放结果集
        self::free();

        return $data;
    }

    /**
     * 获取最后插入的ID, 也就是最后一个自增id
     * @return int  插入的ID
     */
    public function getLastInsID(){
        return $this->_mysqli->insert_id;
    }

    /**
     * 数据的添加
     * @param $table 表名
     * @param $array 要插入的关联数组
     * @return bool|int 返回插入的最后id
     */
    public function insert($table, $array){
        if(!(is_array($array)) || count($array)<=0 ){
            self::halt('INSERT 没有要插入的数据');
        }

//        $field = implode(',', array_keys($array));       //字段
//        $value = "'" . implode("','", $array) . "'";     //给每个字段值加上单引号
//        $sql = "INSERT INTO $table( $field ) VALUES ( $value )";

        $code = self::getCode($array);
        $sql  = "INSERT INTO `{$table}` SET ";
        $sql .= $code;

        $res = $this->_mysqli->query($sql);
        $count = $this->_mysqli->affected_rows;

        if($res && $count > 0){
            self::write_log("执行INSERT 成功, 受影响的行数:{$count}, SQL:{$sql}");

            return $this->getLastInsID();
        }else{
            self::write_log("执行INSERT 失败,SQL:{$sql}");
            return false;
        }
    }


    /**
     * 数据的删除
     * @param $table 表名
     * @param $where 条件
     * @return bool|int 返回受影响的行数
     */
    public function delete($table, $where){
        $sql = "DELETE FROM `{$table}` WHERE {$where}";
        $res = $this->_mysqli->query($sql);
        $count = $this->_mysqli->affected_rows;

        if($res && $count > 0){
            self::write_log("执行DELETE 成功, 受影响的行数:{$count}, SQL:{$sql}");

            return $count;
        }else{
            self::write_log("执行DELETE 失败, SQL:{$sql}");

            return false;
        }
    }


    /**
     * 数据的更新
     * @param $table  表名
     * @param $array  更新的数据，为一个数组
     * @param $where  条件
     * @return bool|int 返回受影响的行数
     */
    public function update($table, $array, $where){
        if(!(is_array($array) || count($array) <=0)){
            self::halt('UPDATE 没有要更新的数据');
        }

        //拼装SQL
        $code = self::getCode($array);
        $sql  = "UPDATE `{$table}` SET ";
        $sql .= $code;
        $sql .= " WHERE {$where}";

        $res = $this->_mysqli->query($sql);
        $count = $this->_mysqli->affected_rows;

        if($res &&  $count > 0){
            self::write_log("执行UPDATE 成功，受影响的行数：{$count}, SQL:{$sql}");

            return $count;
        }else{
            self::write_log("执行UPDATE 失败, SQL:{$sql}");

            return false;
        }

    }

    /**
     * 获取要操作的数据, 返回合并后的SQL语句
     * @param $args
     * @return string
     */
    private function getCode($args) {
        $code = '';
        if (is_array ($args)) {
            foreach ($args as $k => $v) {
                if ($v == '') {
                    continue;
                }
                $code .= "`$k`='$v',";
            }
        }
        $code = substr($code, 0, - 1);
        return $code;
    }

    // +---------------------通用性数据操作--------------------------------------------
    /**
     * 可执行insert, delete, update 返回受影响的行数
     * @param $sql
     * @return bool|int 行数
     */
    public function execute($sql){
        //清空结果集

        $this->_result = $this->_mysqli->query($sql);

        if(!$this->_result){
            self::halt("执行execute 失败 " . $sql);
            return false;
        }

        self::write_log("执行execute 成功 " . $sql);

        return $this->_mysqli->affected_rows;
    }


    /**
     * 执行查询 返回数据集, 按关联二维组数返回
     * @param $sql
     * @return array 返回二维数组
     */
    public function select($sql){
        //清空结果集
        if($this->_result) self::free();

        $this->_result = $this->_mysqli->query($sql);
        $data = array();

        //判断返回结果集中行的数目
        if($this->_result && $this->_result->num_rows > 0){
            while($row = $this->_result->fetch_assoc()){
                $data[] = $row;
            }
        }

        return $data;

    }

    // +---------------------事务处理--------------------------------------------
    //开启一个事务
    public function beginTransaction(){
        if($this->_transTimes == 0) {
            $this->_transTimes++;
            $this->_mysqli->autocommit(FALSE);

            return ;
        }
    }

    //回滚一个事务
    public function rollBack(){
        if($this->_transTimes > 0 ) {
            $this->_transTimes = 0;
            return $this->_mysqli->rollback();
        }
    }

    //提交一个事务
    public function commit(){
        if($this->_transTimes > 0){
            $this->_transTimes = 0;
            if(!$this->_mysqli->commit()){
                return false;
            }
        }

        return true;

    }


    // +---------------------预编译--(推荐)------------------------------------------
    /**
     * 预处理SQL
     * @param $sql
     * @return mysqli_stmt|null   返回创建预定义对象
     */
    private function prepare($sql){
        $this->_stmt = $this->_mysqli->prepare($sql);

        if(!$this->_stmt){
            self::halt("Prepare stms Error! " . $sql);
        }

        return $this->_stmt;
    }

    /**
     * 值绑定
     * @param $array
     * @return array  为索引数组
     */
    private function bindValue($array){
        $data = array();
        foreach ($array as $k=>$v) {
            $data[$k] = &$array[$k];
        }

        return $data;
    }

    /**
     * 执行insert, update, delete 预编译语句
     * @param $sql
     * @param $param  为一个数组，array('si', 'name', 24);
     * @return bool
     */
    public function exec($sql, $param){
        $this->_stmt = self::prepare($sql);
        call_user_func_array(array($this->_stmt, 'bind_param'), $this->bindValue($param)); // $this->_stmt 的方法 bind_param() 按字符种类绑定参数：i,d,s,b

        if($this->_stmt->execute()){
            self::write_log("exec 成功! " . $sql);
        }

        return $this->_stmt->execute();
    }


    /**
     * 多条时获取id 最后1条数据, 为一维数组
     * @param $sql
     * @param $param
     * @return array
     */
    public function fetch($sql, $param){
        $this->_stmt = self::prepare($sql);

        if(!call_user_func_array(array($this->_stmt, 'bind_param'), $this->bindValue($param))){
            self::halt("Fetch 参数绑定 ERROR!");
        }

        $this->_stmt->execute();
        $meta = $this->_stmt->result_metadata();

        // 将结果绑定数组元素设置为引用状态
        $parameters = array();
        $row = array();
        while($field = $meta->fetch_field()){
            $parameters[] = &$row[$field->name];
        }

        if(!call_user_func_array(array($this->_stmt, 'bind_result'), $this->bindValue($parameters))){
            self::halt("Fetch 值绑定 ERROR!");
        }

        $result = array();
        while($this->_stmt->fetch()){
            foreach($row as $k=>$v){
                $result[$k] = $v;
            }
        }

        return $result;
    }


    /**
     * 获取全部数据集, 按关联二维组数返回
     * @param $sql
     * @param $param
     * @return array
     */
    public function fetchAll($sql, $param){
        $this->_stmt = self::prepare($sql);

        if(!call_user_func_array(array($this->_stmt, 'bind_param'), $this->bindValue($param))){
            self::halt("Fetch 参数绑定 ERROR!");
        }
        $this->_stmt->execute();
        $meta = $this->_stmt->result_metadata();

        // 将结果绑定数组元素设置为引用状态
        $parameters = array();
        $row = array();
        while($field = $meta->fetch_field()){
            $parameters[] = &$row[$field->name];
        }

        if(!call_user_func_array(array($this->_stmt, 'bind_result'), $this->bindValue($parameters))){
            self::halt("Fetch 参数绑定 ERROR!");
        }

        $result = array();
        $data = array();
        while($this->_stmt->fetch()){
            foreach($row as $k=>$v){
                $data[$k] = $v;
            }
            $result[] = $data;
        }

        return $result;
    }

    // +---------------------其它处理--------------------------------------------
    /**
     * 访止注入
     * @param $sql_str
     * @return int 返回1 合法， 0不合法
     */
    public function injectCheck($sql_str){
        return  preg_match('/select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i', $sql_str);
    }


    //获取毫秒数
    public function microtime_float(){
        list($usec, $sec) = explode(" ", microtime());   //microtime() 返回：毫秒 时间戳 0.63559400 1469065900

        return ((float)$usec + (float)$sec);
    }

    //获取客户端IP
    public function getIp() {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else
            if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } else
                if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
                    $ip = getenv("REMOTE_ADDR");
                } else
                    if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    } else {
                        $ip = "unknown";
                    }
        return ($ip);
    }

    /**
     * 错误提示，并结束执行
     * @param string $msg  提示信息
     */
    protected function halt($msg = ''){
        $msg .=' ' .  $this->_mysqli->error;
        self::write_log($msg);
        die($msg);
    }


    /**
     * 写入日志信息到文件
     * @param string $msg  日志信息
     */
    protected function write_log($msg = ''){
        if($this->log_is){
            $text = date("Y-m-d H:i:s") . " " . $msg . "\r\n";
            fwrite( $this->_handle, $text);
        }
    }


    //释放结果集所占资源
    protected function free(){
        $this->_result=null;
    }


    //关闭数据库连接
    protected function close(){
        if($this->_mysqli){
            $this->_mysqli->close();
        }

        $this->_mysqli=null;
        $this->_stmt = null;

    }

    //禁止clone
    private function __clone(){}
}


















