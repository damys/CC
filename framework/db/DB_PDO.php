<?php

/**
 * PDO 封装类
 * 功能：封装了常规操作
 * Class DB_PDO
 */
class DB_PDO
{
    private $dsn              = null;
    private $username         = null;
    private $password         = null;
    private $port             = null;
    private $charset          = null;
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
        // 获取数据库配置信息
        $this->dsn         = $confs['dsn'].$confs['dbname'];
        $this->port        = $confs['port'];
        $this->username    = $confs['username'];
        $this->password    = $confs['password'];
        $this->charset     = $confs['charset'];
        $this->log_is      = $confs['log_is'];
        $this->log_path    = $confs['log_path'];


        // 连接数据库
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
     * 禁止clone
     */
    private function __clone(){}


    /**
     * 连接数据库
     * @return null|PDO
     */
    protected function connect()
    {
        try{
            //开启自动提交，连接数据库，并开启长连接
            $_options_values = array(PDO::ATTR_AUTOCOMMIT=>1, PDO::ATTR_PERSISTENT=>true, PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES ' . $this->charset);
            $this->_pdo = new PDO($this->dsn, $this->username, $this->password, $_options_values);
        } catch (PDOException $e){
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


    /**
     * 获取最后插入的ID,也就是最后一个自增id
     * @return int
     */
    public function getLastInsID()
    {
        return $this->_pdo->lastInsertId();
    }


    // +---------------------预编译-------------------------------------------

    /**
     * 执行insert, update, delete 预编译语句, 返回有影响的行数
     * @param $sql
     * @param $param array
     * @return bool|int
     */
    public function exec($sql, $param = [])
    {
        if(!is_array($param) || !is_string($sql))
        {
            $temp = 'exec 没有要更新的数据';
            self::debug($temp, $param);
            self::halt($temp);
        }

        try{
            $this->_stmt = $this->_pdo->prepare($sql);
            $this->_stmt->execute($param);
            $count = $this->_stmt->rowCount();

            //记录执行类型：insert, update, delete
            $mode = strtoupper(substr($sql, 0, 6));

            if($count)
            {
                $lastInsertId = $this->_pdo->lastInsertId();

                // 处理日志，debug
                $temp = "执行$mode 成功，受影响的行数($count)";
                if($mode == 'INSERT'){
                    $temp .= "，返回ID ($lastInsertId) ";
                }
                $temp .='SQL：'.$sql;

                self::debug($temp, $param);
                self::writeLog($temp.' 参数：'.json_encode($param));


                // 如果是插入语句就返回插入的最后id
                if($mode == 'INSERT'){
                    return $lastInsertId;
                } else {
                    // update, delete
                    return $count;
                }
            }

            $temp = "执行$mode 失败，受影响的行数($count)，SQL：$sql 参数：".json_encode($param);
            self::debug($temp);
            self::writeLog($temp);
            return false;

        } catch(PDOException $e) {
            $temp = "执行$mode 失败, SQL：$sql";
            self::debug($temp);
            self::writeLog($temp);

            return false;
        }
    }


    /**
     * 获取1条数据, 为关联一维数组
     * @param $sql
     * @param $param array
     * @return mixed 数组
     */
    public function fetch($sql, $param = [])
    {
        if(!is_array($param) || !is_string($sql))
        {
            $temp = 'fetch 参数错误';
            self::debug($temp, $param);
            self::halt($temp);
        }

        try{
            $this->_stmt = $this->_pdo->prepare($sql);
            $this->_stmt->execute($param);
            $this->_stmt->setFetchMode(PDO::FETCH_ASSOC);

            $count = $this->_stmt->rowCount();
            self::debug("执行fetch 成功，受影响的行数：($count)，SQL：".$sql, $param);

            return $this->_stmt->fetch();
        } catch (PDOException $e){
            $temp = "执行fetch 失败 SQL：$sql " . $e->getMessage();
            self::debug($temp, $param);
            self::halt($temp);
        }

    }


    /**
     * 获取全部数据集, 按关联二维组数返回
     * @param $sql
     * @param $param array
     * @return array 数据
     */
    public function fetchAll($sql, $param = [])
    {
        if(!is_array($param) || !is_string($sql))
        {
            $temp = 'fetchAll 参数错误';
            self::debug($temp, $param);
            self::halt($temp);
        }

        try{
            $this->_stmt = $this->_pdo->prepare($sql);
            $this->_stmt->execute($param);
            $this->_stmt->setFetchMode(PDO::FETCH_ASSOC);

            $count = $this->_stmt->rowCount();
            self::debug("执行fetchAll 成功，受影响的行数($count)，SQL：".$sql, $param);

            return $this->_stmt->fetchAll();
        } catch( PDOException $e) {
            $temp = "执行fetchAll 失败 SQL：$sql " . $e->getMessage();
            self::debug($temp, $param);
            self::halt($temp);
        }
    }


    // 释放所占资源
    protected function free()
    {
        $this->_stmt = null;
    }


    // 关闭连接
    protected function close()
    {
        $this->_pdo = null;
    }


    // +---------------------事务处理--------------------------------------------
    // 开启一个事务
    public function beginTransaction()
    {
        if($this->_transTimes == 0) {
            //下面这句话启用pdo错误模式，这样当执行操作出现错误的时候，才会被catch，在默认情况下，即使发生错误也不会被catch,所以这句话很重要.
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_transTimes++;

            return $this->_pdo->beginTransaction();
        }
    }


    // 回滚一个事务
    public function rollBack()
    {
        if($this->_transTimes > 0) {
            $this->_transTimes = 0;

            return $this->_pdo->rollBack();
        }

        return false;
    }


    // 提交一个事务
    public function commit()
    {
        if($this->_transTimes > 0) {
            $this->_transTimes = 0;

            return $this->_pdo->commit();
        }

        return false;
    }


    // +--------------------- 方法函数 --------------------------------------------
    // 获取客户端IP
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
        self::writeLog($msg);
        die($msg);
    }


    /**
     * 写入日志信息到文件
     * @param string $msg  日志信息
     * 注：目录结构：年/月/年月日_db_log.txt
     */
    protected function writeLog($msg = '')
    {
        if($this->log_is)
        {
            // 拼装日志目录
            $log_path   = $this->log_path . date('Y', time()) .'/'. date('m', time()) . '/';

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


    /**
     * @param $sql
     * @param array $param
     * fetch, fetchAll 为添加参考维度：时间，查询参数
     */
    protected function debug($sql, $param=[])
    {
        if(defined('DEBUG') && DEBUG === 1)
        {
            $text = date("Y-m-d H:i:s") . " " . $sql .' 参数：'. json_encode($param);
            framework\base\Debug::addmsg($text,2);
        }
    }

}