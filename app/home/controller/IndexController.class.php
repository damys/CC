<?php

class IndexController extends BaseController
{
    public function IndexAction(){

        $obj = ModelFactory::M('IndexModel');
        msg($obj->getRow());

        $title = 'home title';
        $data = 'home data';
        $this->assign('title', $title);
        $this->assign('data', $data);

        $this->display('index/index.html');
    }

}