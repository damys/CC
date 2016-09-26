<?php


class IndexController extends BaseController
{
    public function IndexAction(){
        msg('this is admin controller');
        $this->display('index\index.html');


    }

    public function DemoAction(){
        $this->display('index\demo.html');
    }
}