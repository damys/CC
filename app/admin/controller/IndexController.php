<?php

class IndexController extends BaseController
{
    public function indexAction(){

        // 测试：从数据库中取数据
        $model = Factory::M('ArticleModel');
//        msg($model->insert());
        msg($model->getAll());

//        msg(preg_match("/cli/i", php_sapi_name()) ? true : false);

        // 使用配置文件加载，空间加载
//        msg(OrderStatusEnum::PAID_BUT_OUT_OF);

//        msg(ECode::ERR_TOKEN);
//        msg(Response::success(array('name'=>'okoooo')));
//        msg(Response::error(ECode::ERR_TOKEN['code'], ECode::ERR_TOKEN['msg']));


//        $categories = '';
//        if(empty($categories)){
//            throw new EException(ECode::ERR_TOKEN);
//            throw new MissException(ECode::ERR_URL);
//
//            throw new MissException([
//                'errCode'=>4044,
//                'msg' => '缺少参数111'
//            ]);
//        } else {
//            throw new Exception();
//            throw new Exception('Sorry, Server error...');
//        }


        $title = 'admin title';
        $this->assign('title', $title);

        $this->display('/index/index.html');
    }

    public function testAction()
    {
        if(isset($_GET['code']) )
        {
            if($_GET['code'] == 404) {
                $this->display('/error/404.tpl');
            }

            if($_GET['code'] == 500) {
                $this->display('/error/500.tpl');
            }

            if($_GET['code'] == 1) {
                $this->display('/error/forbidden.tpl');
            }
        }
    }

    public function ShowAction()
    {
        include EXTEND . 'captcha'.DS.'Captcha.php';
        $captcha = new Captcha();
        $captcha->viewImg();
//        $_SESSION['captcha'] = $captcha->getCode();
    }
}

