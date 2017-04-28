<?php

/**
 * Class ArticleModel
 */
class ArticleModel extends BaseModel
{
    // 数据表名
    private $_table = null;


    /**
     * 继承父类的方法, 从配置文件获取数据表前缀名
     */
    public function __construct()
    {
        parent::__construct();

        $this->_table = $this->_db_prefix . "article";
    }
}