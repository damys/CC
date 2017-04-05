<?php

class IndexController extends BaseController
{
    public function IndexAction(){

        $model = ModelFactory::M('IndexModel');
        msg($model->getRow());

        $title = 'home title';

        $this->assign('title', $title);
        $this->display('index/index.html');
    }
}