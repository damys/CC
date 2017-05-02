<?php

/**
 * PDO 封装类
 * 功能：封装了常规操作
 * Class DB_PDO
 */
class DB_PDO
{
    private $dsn              = null;
    private $db_user          = null;
    private $db_pwd           = null;
    private $db_port          = null;
    private $db_charset       = null;
    private $log_is           = 0;
    private $log_path         = null;

    private static $_instance = null;
    private $_transTimes      = 0;
    private $_pdo             = null;
    private $_stmt            = null;

    /**
     * 构造函数：
     * 获取数据库配置信息
     * 开启日志
     * 连接数据库
     */
    private function __construct($confs)
    {
        //获取数据库配置信息
        $this->dsn        = $confs['dsn'];
        $this->db_user    = $confs['db_user'];
        $this->db_pwd     = $confs['db_pwd'];
        $this->db_charset = $confs['db_charset'];
        $this->db_port    = $confs['db_port'];
        $this->log_is     = $confs['log_is'];
        $this->log_path   = $confs['log_path'];


        //连接数据库
        try{
            $this->_pdo = self::connect();
        }catch(PDOException $e){
            self::halt('DB Connect Error! ' . $e->getMessage());
        }

    }


    /**
     * 析构函数：
     * 关闭log 文件handle
     * 释放结果集所占资源
     */
    public function __destruct()
    {
        self::free();
        self::close();
    }


    /**
     * 连接数据库
     * @return null|PDO
     */
    protected function connect()
    {
        try{
            //开启自动提交，连接数据库，并开启长连接
            $_options_values = array(PDO::ATTR_AUTOCOMMIT=>1, PDO::ATTR_PERSISTENT=>true, PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES ' . $this->db_charset);
            $this->_pdo = new PDO($this->dsn, $this->db_user, $this->db_pwd, $_options_values);
        }catch(PDOException $e){
            self::halt('DB Connect Error! ' . $e->getMessage());
        }

        return $this->_pdo;
    }


    /**
     * 单例模式
     * 判断：如果没有实例化，就先实例化
     */
    public static function getInstance($confs)
    {
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self($confs);
        }

        return self::$_instance;
    }

    // +---------------------常用性数据操作--------------------------------------------
    /**
     * 获取1条数据, 为关联一维数组
     * @param $sql
     * @param int $fetch_type 返回类型：FETCH_ASSOC(默认), FETCH_NUM, FETCH_BOTH...
     * @return array|mixed  返回数组
     */
    public function getRow($sql, $fetch_type = PDO::FETCH_ASSOC)
    {
        try{
            $this->_stmt = $this->_pdo->query($sql);
            $this->_stmt->setFetchMode($fetch_type);

            return $this->_stmt->fetch();
        } catch (PDOException $e){
            self::halt("getRow Error! SQL:{$sql} " . $e->getMessage());
        }

    }


    /**
     * 获取全部数据集, 按关联二维组数返回
     * @param $sql
     * @param int $fetch_type 返回类型：FETCH_ASSOC(默认), FETCH_NUM, FETCH_BOTH...
     * @return array 返回数组
     */
    public function getAll($sql, $fetch_type = PDO::FETCH_ASSOC)
    {
        try {
            $this->_stmt = $this->_pdo->query($sql);
            $this->_stmt->setFetchMode($fetch_type);

            return $this->_stmt->fetchAll();
        } catch (PDOException $e){
            self::halt("getAll Error! SQL:{$sql} " . $e->getMessage());
        }
    }

    /**
     * 获取最后插入的ID,也就是最后一个自增id
     * @return int
     */
    public function getLastInsID()
    {
        return $this->_pdo->lastInsertId();
    }


    /**
     * 数据的添加
     * @param $table 表名
     * @param $array 要插入的关联数组
     * @return bool|string 返回插入的最后id
     */
    public function insert($table, $array)
    {
        if(!(is_array($array)) || count($array)<=0 ){
            self::halt('INSERT 没有要插入的数据');
        }

        $code = self::getCode($array);
        $sql  = "INSERT INTO `{$table}` SET ";
        $sql .= $code;

        if($this->_pdo->exec($sql) && $this->_pdo->lastInsertId()){
            self::write_log("执行INSERT 成功 " . $sql);

            return $this->_pdo->lastInsertId();
        }else{
            self::write_log("执行INSERT 失败 " . $sql);

            return false;
        }
    }


    /**
     * 数据的删除
     * @param $table 表名
     * @param $where 条件
     * @return bool|int 返回受影响的行数
     */
    public function delete($table, $where)
    {
        $sql = "DELETE from {$table} WHERE {$where}";
        $count = $this->_pdo->exec($sql);

        if($count){
            self::write_log("执行DELETE 成功，受影响的行数:{$count}, SQL:{$sql}");

            return $count;
        }else{
            self::write_log("执行DELETE 失败! SQL:{$sql}");

            return false;
        }
    }


    public function update($table, $array, $where)
    {
        if(!(is_array($array) || count($array) <=0)){
            self::halt('UPDATE 没有要更新的数据');
        }

        //拼装SQL
        $code = self::getCode($array);
        $sql  = "UPDATE {$table} SET ";
        $sql .= $code;
        $sql .= " WHERE {$where}";

        $count = $this->_pdo->exec($sql);

        if($count){
            self::write_log("执行UPDATE 成功，受影响的行数：{$count}, SQL:{$sql}");

            return $count;
        }else{
            self::write_log("执行UPDATE 失败, SQL:{$sql}");

            return false;
        }
    }


    /**
     * 获取要操作的数据, 返回合并后的SQL语句
     * @param $args array
     * @return string
     */
    private function getCode($args)
    {
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


    // +---------------------事务处理--------------------------------------------
    //开启一个事务
    public function beginTransaction()
    {
        if($this->_transTimes == 0) {
            //下面这句话启用pdo错误模式，这样当执行操作出现错误的时候，才会被catch，在默认情况下，即使发生错误也不会被catch,所以这句话很重要.
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_transTimes++;

            return $this->_pdo->beginTransaction();
        }
    }


    //回滚一个事务
    public function rollBack()
    {
        if($this->_transTimes > 0) {
            $this->_transTimes = 0;

            return $this->_pdo->rollBack();
        }
        return false;

    }


    //提交一个事务
    public function commit()
    {
        if($this->_transTimes > 0) {
            $this->_transTimes = 0;

            return $this->_pdo->commit();
        }
        return false;
    }


    // +---------------------预编译-(推荐)-------------------------------------------
    /**
     * 执行insert, update, delete 预编译语句, 返回有影响的行数
     * @param $sql
     * @param $param array
     * @return bool|int
     */
    public function exec($sql, $param = null)
    {
        if(!(is_array($param) || count($param) <= 0)){
            self::halt('fetch 没有要更新的数据');
        }

        try{
            $this->_stmt = $this->_pdo->prepare($sql);
            $this->_stmt->execute($param);
            $count = $this->_stmt->rowCount();


            //记录执行类型：insert, update, delete
            $mode = strtoupper(substr($sql, 0, 6));

            if($count){
                self::write_log("执行{$mode}成功，受影响的行数：{$count}, SQL:$sql");

                // 如果是插入语句就返回插入的最后id
                if($mode == 'INSERT'){
                    return $this->_pdo->lastInsertId();
                } else {
                    return $count;
                }
            }
        } catch(PDOException $e){
            self::write_log("执行{$mode}操作失败, SQL:{$sql}");

            return false;
        }
    }


    /**
     * 获取1条数据, 为关联一维数组
     * @param $sql
     * @param $param array
     * @return mixed 数组
     */
    public function fetch($sql, $param = null)
    {
        if(!(is_array($param) || count($param) <= 0)){
            self::halt('fetch 没有要更新的数据');
        }

        try{
            $this->_stmt = $this->_pdo->prepare($sql);
            $this->_stmt->execute($param);
            $this->_stmt->setFetchMode(PDO::FETCH_ASSOC);

            return $this->_stmt->fetch();
        } catch (PDOException $e){
            self::halt("fetch 失败 SQL:{$sql} " . $e->getMessage());
        }

    }


    /**
     * 获取全部数据集, 按关联二维组数返回
     * @param $sql
     * @param $param array
     * @return array 数据
     */
    public function fetchAll($sql, $param = null)
    {
        if(!(is_array($param) || count($param) <= 0)){
            self::halt('fetchAll 没有要更新的数据');
        }

        try{
            $this->_stmt = $this->_pdo->prepare($sql);
            $this->_stmt->execute($param);
            $this->_stmt->setFetchMode(PDO::FETCH_ASSOC);

            return $this->_stmt->fetchAll();
        } catch( PDOException $e) {
            self::halt("fetchAll 失败 SQL:{$sql} " . $e->getMessage());
        }
    }


    // +---------------------其它处理--------------------------------------------
    /**
     * 访止注入
     * @param $sql_str
     * @return int 返回1 合法， 0不合法
     */
    public function injectCheck($sql_str)
    {
        return  preg_match('/select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i', $sql_str);
    }


    //获取毫秒数
    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());   //microtime() 返回：毫秒 时间戳 0.63559400 1469065900

        return ((float)$usec + (float)$sec);
    }

    //获取客户端IP
    public function getIp()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return($ip);
    }

    /**
     * 错误提示，并结束执行
     * @param string $msg  提示信息
     */
    protected function halt($msg = '')
    {
        self::write_log($msg);
        die($msg);
    }


    /**
     * 写入日志信息到文件
     * @param string $msg  日志信息
     * 注：目录结构：年/月/年月日_db_log.txt
     */
    protected function write_log($msg = '')
    {
        if($this->log_is)
        {
            // 拼装日志目录
            $log_path   = $this->log_path . 'db/' . date('Y', time()) .'/'. date('m', time()) . '/';

            // 创建多级目录
            if(!file_exists($log_path)) mkdir($log_path, 0777, true);

            // 获取文件手柄
            $handle = fopen($log_path . '/' . date('Ymd', time()) ."_db_log.txt", "a+");

            // 追加内容到文件
            $text = date("Y-m-d H:i:s") . " " . $msg . "\r\n";
            fwrite( $handle, $text);

            // 关闭文件手柄
            fclose($handle);
        }
    }

    //释放所占资源
    protected function free()
    {
        $this->_stmt = null;
    }

    //关闭连接
    protected function close()
    {
        $this->_pdo = null;
    }

    //禁止clone
    private function __clone(){}
}