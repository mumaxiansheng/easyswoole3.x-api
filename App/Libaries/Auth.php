<?php
/**
 * Created by PhpStorm.
 * User: mamtree-mac-002
 * Date: 2018/11/12
 * Time: 2:19 PM
 */

namespace App\Libaries;


use App\Entities\OauthAccessToken;
use Firebase\JWT\JWT;

class Auth
{
    protected static $instance;
    protected $ttl;//过期时间
    protected $private_key;//私钥
    protected $public_key;//公钥
    protected $jwt_claims=[
        'iss'=>'zhizubaba.com',//该JWT的签发者
        'aud'=>'zhizubaba.com',
        'iat'=>'',//在什么时候签发的token
        'exp'=>'',//token什么时候过期
        'nbf'=>'1542004679',//token在此时间之前不能被接收处理
        'sub'=>'0',//该JWT所面向的用户
        'jti'=>'',//JWT ID为web token提供唯一标识
        "scopes"=>[],//作用域
    ];


    private function __construct()
    {
        //设置私钥
        $this->private_key=file_get_contents(config('app.private_key'));
        //设置公钥
        $this->public_key=file_get_contents(config('app.public_key'));
        $this->init();
        $this->ttl=config('app.ttl');
        $this->jwt_claims['exp']=$this->jwt_claims['iat']+$this->ttl;
    }

    public static function getInstance()
    {
        if(!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    private function init()
    {
        $this->jwt_claims['iat']=time();
        $this->jwt_claims['jti']=md5(uniqid(mt_rand(), true));
    }


    /**
     * @param array $scopes
     * @param $user_id
     * @return array|bool
     * 生成token
     */
    public function createToken(array $scopes,$user_id)
    {
        $this->jwt_claims['sub']=$user_id;
        $this->jwt_claims['scopes']=$scopes;
        try {
            $token=JWT::encode($this->jwt_claims,$this->private_key, 'RS256');
            $data=[
                'id'=>$this->jwt_claims['jti'],
                'user_id'=>$this->jwt_claims['sub'],
                'client_id'=>'1',
                'name'=>'api-app',
                'scopes'=>json_encode($scopes),
                'revoked'=>'0',
                'created_at'=>date('Y-m-d H:i:s',$this->jwt_claims['iat']),
                'updated_at'=>date('Y-m-d H:i:s',$this->jwt_claims['iat']),
                'expires_at'=>date('Y-m-d H:i:s',$this->jwt_claims['exp']),
            ];
            //保存登录记录
            OauthAccessToken::query()->create($data);
            return [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' =>$this->ttl,
            ];
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $token
     * @return array
     * 验证token
     */
    public function  verificationToken($token)
    {
        $token=(string) trim(mb_substr($token,7, null,'UTF-8'));
        JWT::$leeway = 60;
        try {
            $decoded =(array)JWT::decode($token,$this->public_key, array('RS256'));
            //TODO 验证jti
            return $decoded;
        }
        catch (\Exception $e){
            return ['error'=>$e->getMessage()];
        }
    }
}