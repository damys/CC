<?php

class IndexController extends BaseController
{
    public function IndexAction(){

        $model = ModelFactory::M('IndexModel');
        msg($model->getRow());

        $title = 'home title';
        $data = 'home data';
        $this->assign('title', $title);
        $this->assign('data', $data);

        // 测试公共类：
        $model = ModelFactory::M('ArticleModel');

        $this->display('index/index.html');
    }

    public function demoAction(){

        $this->display('index/demo.html');
    }

}