<?php

/**
 * Class IndexController
 */
class IndexController extends BaseController
{
    public function IndexAction(){
        $model = ModelFactory::M('IndexModel');
        msg($model->getRow());

        $this->display('index\index.html');
    }
}