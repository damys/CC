<?php

/**
 * Class ArticleModel
 */
class ArticleModel extends BaseModel
{
    // 数据表名
    private $_table = null;
    protected $_dao       = null;   //用于存储数据库工具类的实例(对象)
    protected $_db_prefix = null;   // 数据库表前缀


    /**
     * 继承父类的方法, 从配置文件获取数据表前缀名
     */
    public function __construct()
    {
        parent::__construct();   // 可以指定数据。 如：cc_test2

        $this->_table = $this->_db_prefix . "test";
    }

    function getAll()
    {
        $sql = "select * from {$this->_table} where id > 1";

        return $this->_dao->getAll($sql);
    }
}