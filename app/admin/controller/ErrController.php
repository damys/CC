<?php

class ErrController extends BaseController
{
    public function indexAction()
    {
        // 如果是post 请求，以接口的方式提示
        if(isset($_POST) && count($_POST)){
            Response::show(4000,'Unprivileged access');
        }

        // 根据状态码操作
        if(isset($_GET['c']) && !empty($_GET['c']) && is_numeric($_GET['c'])){
            $message = '';
            // 状态码
            switch ($_GET['c']){
                case 4001:
                    $message = '找不到控制器';
                    break;
                case 4002:
                    $message = '找不到操作方法';
                    break;
                default:
                    $message = '非法访问';
                    break;
            }

            // 无权限访问
            if($message === '非法访问'){
                $this->display('/error/forbidden.html');exit;
            }

            // 处理有状态码
            $this->assign('message', $message);
            $this->display('/error/404.html'); exit;
        }


        // 无权限访问
        $this->display('/error/forbidden.html');
    }


    /**
     * 404
     */
    public function error_404Action()
    {
        // 如果是post 请求，以接口的方式提示
        if(isset($_POST) && count($_POST)){
            Response::show(1,'404 ERROR');
        }

        $this->display('/error/404.html');
    }


    /**
     * 500
     */
    public function error_500Action()
    {
        // 如果是post 请求，以接口的方式提示
        if(isset($_POST) && count($_POST)){
            Response::show(1,'500 ERROR');
        }

        $this->display('/error/500.html');
    }
}