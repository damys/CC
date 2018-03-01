<?php

class IndexController extends BaseController
{
    public function IndexAction(){

//        $model = ModelFactory::M('IndexModel');
//        msg($model->getRow());
        $model = ModelFactory::M('ArticleModel');
        msg($model->getAll());



//        msg(Route::getInstance());

        $this->assign('title', "home title");
        $this->display('/index/index.html');
    }
	
	public function testAction()
    {
        include FRAMEWORK . 'captcha'.DS.'Captcha.class.php';

        $captcha = new Captcha();
        $captcha->viewImg();
        $_SESSION['captcha'] = $captcha->getCode();
    }
}