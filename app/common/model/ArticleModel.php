<?php

class ArticleModel extends BaseModel
{
    // 数据表名
    private $_table = null;
    protected $_prefix = null;   // 数据表前缀


    /**
     * 继承父类的方法, 从配置文件获取数据表前缀名
     */
    public function __construct()
    {
        parent::__construct();   // 可以指定数据。 如：cc_test2

        $this->_table = $this->_prefix . "test";
    }

    function getAll()
    {
        $sql = "select * from {$this->_table} where id > 80";
        return $this->_dao->fetchAll($sql,[]);
    }

    function insert()
    {
        $sql = "insert into $this->_table(name, age) VALUES (:name,:age)";
        $data = array(":name"=>'tom', ":age"=>28);

        return $this->_dao->exec($sql,$data);
    }
}