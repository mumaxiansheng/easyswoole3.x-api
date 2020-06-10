<?php
/**
 * Created by PhpStorm.
 * User: mamtree-mac-002
 * Date: 2018/11/13
 * Time: 4:07 PM
 */

return [
    //小程序配置
    'Mini_APP'=>[
        'app_id'=>'wxc5d1dced5fdcd24b',
        'secret'=>'f5a144654a842bf58bcc6643ee2e0466',
        'response_type'=>'array',
        'log' => [
            'level' => 'debug',
            'file' =>EASYSWOOLE_ROOT . '/Log/wechat.log',
        ],
    ],
    //私钥
    'private_key' =>EASYSWOOLE_ROOT.'/Storage/oauth-private.key',
    //公钥
    'public_key' =>EASYSWOOLE_ROOT.'/Storage/oauth-public.key',
    'ttl' =>'1296000',
];