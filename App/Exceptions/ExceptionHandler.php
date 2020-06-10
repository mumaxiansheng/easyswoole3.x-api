<?php
/**
 * Created by PhpStorm.
 * User: taoyongxing
 * Date: 2018/10/28
 * Time: 4:18 PM
 */

namespace App\Exceptions;

use EasySwoole\Http\Response;
use EasySwoole\Http\Request;



class ExceptionHandler
{
    public function handle( \Throwable $exception, Request $request, Response $response )
    {
        //异常处理
        var_dump($exception->getTraceAsString());
    }
}