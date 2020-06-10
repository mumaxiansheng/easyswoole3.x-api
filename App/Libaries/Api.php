<?php
/**
 * Created by PhpStorm.
 * User: mamtree-mac-002
 * Date: 2018/11/5
 * Time: 2:12 PM
 */

namespace App\Libaries;


use FastRoute\RouteCollector;

class Api
{
    protected static $instance;

    protected $conf_instance;

    protected $prefix;

    protected $middleware;

    protected $namespace;

    protected $groupStack = [];

    private function __construct()
    {
        $this->conf_instance = \EasySwoole\EasySwoole\Config::getInstance();
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * @param array $attributes
     * @param $callback
     * 设置组
     */
    public function group(array $attributes, $callback)
    {
        $this->groupStack[] = $attributes;
        call_user_func($callback, $this);
        array_pop($this->groupStack);
    }

    /**
     * @param $methods
     * @param $url
     * @param $action
     * 设置  路由
     */
    private function setRoute($methods, $url, $action)
    {
        $this->prefix = '';
        $this->namespace = '';
        $this->middleware = '';
        foreach ($this->groupStack as $value) {
            if (isset($value['prefix']))
                $this->prefix .= '/' . $value['prefix'];
            if (isset($value['namespace']))
                $this->namespace = $value['namespace'];
            if (isset($value['middleware']))
                $this->middleware = empty($this->middleware) ? $value['middleware'] : ',' . $value['middleware'];
        }
        $data = [
            'middleware' => $this->middleware,
            'handler' => $this->namespace . '/' . $action
        ];
        $url = $this->prefix . '/' . $url;
        $this->conf_instance->setConf('API.' . $methods . '.' . $url, $data);
    }


    /**
     * @param $url
     * @param $action
     * 添加 get 路由
     */
    public function get($url, $action)
    {
        return $this->setRoute('GET', $url, $action);
    }

    /**
     * @param $url
     * @param $action
     * 添加 post 路由
     */
    public function post($url, $action)
    {
        return $this->setRoute('POST', $url, $action);
    }

    /**
     * @param $url
     * @param $action
     * 添加 put 路由
     */
    public function put($url, $action)
    {
        return $this->setRoute('PUT', $url, $action);
    }

    /**
     * @param $url
     * @param $action
     * 添加 delete 路由
     */
    public function delete($url, $action)
    {
        return $this->setRoute('DELETE', $url, $action);
    }

    /**
     * @param $url
     * @param $action
     * 添加 any 路由
     */
    public function any($url, $action)
    {
        $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'];
        foreach ($verbs as $v) {
            $this->setRoute($v, $url, $action);
        }
        return;
    }


    /**
     * @param $url
     * @param $action
     * 添加 resource 路由
     */
    public function resource($url, $action)
    {
        $this->setRoute('GET', $url, $action . '/index');
        $this->setRoute('POST', $url, $action . '/store');
        $this->setRoute('GET', $url . "/{id:\d+}", $action . '/show');
        $this->setRoute('PUT', $url . "/{id:\d+}", $action . '/update');
        $this->setRoute('DELETE', $url . "/{id:\d+}", $action . '/destroy');
        return;
    }


    /**
     * @param RouteCollector $routeCollector
     * 注册路由
     */
    public function registerRouter(RouteCollector $routeCollector)
    {
        $route = $this->conf_instance->getConf('API');
        foreach ($route as $k1 => $v1) {
            foreach ($v1 as $key => $value) {
                $routeCollector->addRoute($k1, $key, $value['handler']);
            }
        }
    }
}