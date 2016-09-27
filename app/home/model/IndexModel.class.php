<?php

class IndexModel extends BaseModel
{
    function getRow(){
        $sql = 'select * from test where id > 2';
        return $this->_dao->getRow($sql);
    }
}