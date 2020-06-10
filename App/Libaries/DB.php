<?php
/**
 * Created by PhpStorm.
 * User: mamtree-mac-002
 * Date: 2018/11/14
 * Time: 1:39 PM
 */

namespace App\Libaries;


use EasySwoole\Mysqli\Mysqli;

class DB
{
    private static $instance;

    public static function getInstance()
    {
        //判断当前类是否已创建
        if (!isset(self::$instance)) {
            $conf = new \EasySwoole\Mysqli\Config(config('MYSQL'));
            self::$instance = new Mysqli($conf);
        }
        return self::$instance;
    }

    /**
     * 开启事务
     */
    public static function beginTransaction(){
        return self::getInstance()->startTransaction();
    }

    /**
     * @return array|bool
     * 事务回滚
     */
    public static function rollBack()
    {
        return self::getInstance()->rollback();
    }

    /**
     * @return bool
     * 事务提交
     */
    public static function commit()
    {
        return self::getInstance()->commit();
    }
}