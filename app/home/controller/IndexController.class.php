<?php

class IndexController extends BaseController
{
    public function IndexAction(){

        $model = ModelFactory::M('IndexModel');
        msg($model->getRow());

        $title = 'home title';
        $this->assign('title', $title);

        // 测试公共类：
        $model = ModelFactory::M('ArticleModel');

        $this->display('index/index.html');
    }
}