<?php
/**
 * Created by PhpStorm.
 * User: mamtree-mac-002
 * Date: 2018/11/5
 * Time: 2:01 PM
 */
$api=\App\Libaries\Api::getInstance();
$api->group(['namespace' => '/Frontend','prefix' =>'api'],function ($api){
     //小程序登录
     $api->get('miniAppLogin','User/miniAppLogin');
     $api->group(['middleware' =>'token'], function ($api) {
         $api->get('UserInfo','User/index');
     });
});