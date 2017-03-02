<?php

/**
 * Class IndexController
 */
class IndexController extends BaseController
{
    public function IndexAction(){

        // 测试：从数据库中取数据
        $model = ModelFactory::M('IndexModel');
        msg($model->getRow());

        $title = 'admin title';
        $this->assign('title', $title);

        $this->display('index\index.html');
    }
}