<?php

class NewsController extends BaseController
{
    public function indexAction()
    {
        msg('news index page...');
//        echo 0/0;
        self::testAction();
        $this->display('news/index.html');
    }

    public function testAction()
    {
        $name = 'aaaaa';
        parent::assign('name', $name);
//        $this->display('news/index.html');
    }

    public function detailAction()
    {
        include EXTENT . 'Page.php';
        $page = new extend\page(30, 10, 2, 'http://www.ccphp.com');
        echo $page->show();

        msg('news detail page....');
    }
}

