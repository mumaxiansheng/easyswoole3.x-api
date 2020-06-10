<?php
/**
 * Created by PhpStorm.
 * User: mamtree-mac-002
 * Date: 2018/11/5
 * Time: 11:18 AM
 */

namespace App\Middleware;


use App\Libaries\Auth;

class TokenValidationMiddleware
{
    protected static $instance;
    public static function getInstance()
    {
        if ( !isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }


    public function handle($request, $response)
    {
        $server = $request->getServerParams();
        $path = $request->getUri()->getPath();
        $header = $request->getHeaders();
        $route=config('API.'.$server['request_method']);
        $path_A=explode("/",$path);
        if(is_numeric($path_A[count($path_A)-1]))
        {
            $path_A[count($path_A)-1]="{id:\d+}";
        }
        else
        {
            $path_A[count($path_A)-1]="{name}";
        }
        $path_A = implode("/", $path_A);
        if(isset($route[$path])||isset($route[$path_A]))
        {
            $data=isset($route[$path])?$route[$path]:$route[$path_A];
            if(isset($data['middleware']))
            {
                if($data['middleware']=='token')
                {
                    //验证token
                    if(!isset($header['authorization']))
                        return sendError($response,'Unauthorized',401);
                    $res=Auth::getInstance()->verificationToken($header['authorization'][0]);
                    if(isset($res['error']))
                        return sendError($response,'Unauthorized',401);
                    $request->withQueryParams(['jwt_sub'=>$res['sub']]);
                }
            }
        }
    }
}