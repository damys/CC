<?php


class IndexController extends BaseController
{
    public function IndexAction(){
        $model = ModelFactory::M('IndexModel');
        msg($model->getRow());

        msg('this is admin controller');
        $this->display('index\index.html');


    }

    public function DemoAction(){
        $this->display('index\demo.html');
    }
}