<?php


class IndexController extends BaseController
{
    public function indexAction()
    {
        $model = Factory::M('ArticleModel');
//        msg('home');
//        msg($model->insert());
        msg($model->getAll());
//        msg($model->delete());
//        msg($model->update());

        msg('home index');
//        msg(Conf::get('db_dsn', 'db.config.inc'));

        // Service
        OrderService::getOrder();

        // enum
        $s = OrderStatusEnum::PAID_BUT_OUT_OF;
        msg('home enum: '. $s);

        include_once EXTEND . 'Third.php';
        extend\Third::getMsg();

        include_once VENDOR.'Third.php';
        vendor\Third::getMsg();

        $this->assign('title', "home title");
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

    public function test2Action()
    {
        msg(1111111);
//        NewsController::testAction();
        $new = new NewsController();
        $new->testAction();
        parent::display('news/index.html');
//        Base::get();
    }


}