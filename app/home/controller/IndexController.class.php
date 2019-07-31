<?php

class IndexController extends BaseController
{
    public function IndexAction(){

//        $model = ModelFactory::M('IndexModel');
//        msg($model->getRow());
        $model = ModelFactory::M('ArticleModel');
        msg($model->getAll());

        echo $GLOBALS['CONF']['LOG_PATH'];


//        msg(Route::getInstance());

        $this->assign('title', "home title");
        $this->display('/index/index.html');
    }

    public function TestAction()
    {
        msg(1111111);
    }


}