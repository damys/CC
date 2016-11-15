<?php

class IndexModel extends BaseModel
{
    // table name
    protected $_table = "test";

    function getRow(){
        $sql = "select * from {$this->_table} where id > 1";
        return $this->_dao->getRow($sql);
    }
}