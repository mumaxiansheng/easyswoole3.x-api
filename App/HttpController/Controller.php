<?php
/**
 * Created by PhpStorm.
 * User: taoyongxing
 * Date: 2018/11/7
 * Time: 11:06 PM
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\Controller as BaseController;



class Controller extends BaseController
{



    function index()
    {

    }

    function onException(\Throwable $throwable): void
    {
        var_dump($throwable->getMessage());
    }

    protected function actionNotFound(?string $action): void
    {
        $this->response()->withStatus(Status::CODE_NOT_FOUND);
        $this->response()->write('action not found');
    }
}