<?php


use App\Libaries\Validator;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\Response;


/**
 * @param Response $response
 * @param array $data
 * 返回成功信息
 */
function sendSuccess(Response $response,$data=[])
{
    $responseData['status']=200;
    $responseData['message']=$data;
    $response->write(json_encode($responseData, JSON_UNESCAPED_UNICODE));
    $response->withStatus(200);
    $response->withHeader('Content-type', 'application/json;charset=utf-8');
    $response->end();
}


/**
 * 返回错误信息
 *
 * @param Response $response Response对象
 * @param $message 返回的错误信息
 * @param int $code 状态码
 * @param array $options 附带信息
 *
 */
function sendError(Response $response,$message,$code=400,array $options = [])
{
    $responseData['status']=$code;
    $responseData['message']=(string)$message;
    if(!empty($options))
        $responseData=array_merge($responseData, $options);
    $response->write(json_encode($responseData, JSON_UNESCAPED_UNICODE));
    $response->withStatus($code);
    $response->withHeader('Content-type', 'application/json;charset=utf-8');
    $response->end();
}


/**
 * @param $key
 * @return string
 * 加载语言包
 */
function lang($key)
{
    $segments = explode('.', $key);
    if(count($segments)<2)
        return '';
    $lang = require_once(EASYSWOOLE_ROOT.'/Lang/zh-cn/'.$segments[0].'.php');
    return $lang[$segments[1]];
}


/**
 * @param $key
 * @return array|mixed|null
 * 获取配置文件
 */
function config($key)
{
    return Config::getInstance()->getConf($key);
}


/**
 * @param array $data
 * @param array $rules
 * @param array $messages
 * @return array|bool
 * 添加验证助手函数
 */
function validators($data=[], $rules=[], $messages=[])
{
    $validator = Validator::getInstance()->make($data, $rules, $messages);
    if ($validator->fails()) {
        foreach ($validator->errors()->messages() as $v)
            return $v[0];
    }
    return $validator->fails();
}


function __asset__($path='')
{
    return '/public/'.$path;
}