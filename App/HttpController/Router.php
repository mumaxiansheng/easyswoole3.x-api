<?php
/**
 * Created by PhpStorm.
 * User: taoyongxing
 * Date: 2018/10/28
 * Time: 4:47 PM
 */

namespace App\HttpController;

use App\Libaries\Api;
use EasySwoole\Http\AbstractInterface\AbstractRouter;;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{

    function initialize(RouteCollector $routeCollector)
    {
        Api::getInstance()->registerRouter($routeCollector);
    }

}