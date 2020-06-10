<?php
/**
 * Created by PhpStorm.
 * User: taoyongxing
 * Date: 2018/11/7
 * Time: 11:04 PM
 */

namespace App\HttpController\Frontend;

use App\HttpController\Controller;
use App\Libaries\Auth;
use App\Libaries\DB;
use EasySwoole\Mysqli\Mysqli;
use EasyWeChat\Factory;
use App\Entities\User as UserEloquent;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Class User
 * @package App\HttpController\Frontend
 */
class User extends Controller
{
    /**
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * 小程序登录
     */
    public function miniAppLogin()
    {
        $params = $this->request()->getRequestParam();
        $rules=['code' => 'required'];
        $validator = validators($params, $rules);
        if($validator)
            return sendError($this->response(), $validator, 422);
        $auth=Factory::miniProgram(config('app.Mini_APP'));
        $res=$auth->auth->session($params['code']);
        if (isset($res['errcode']))
            return sendError($this->response(), $res['errmsg'], 422);
        //判断数据库是否存在
        $user=DB::getInstance()->where('open_id', $res['openid'])->getOne('users', 'id');
        if (!$user) {
            $user=UserEloquent::query()->create()->toArray();
        }
        $token=Auth::getInstance()->createToken(['mini-app'], $user['id']);
        return sendSuccess($this->response(), $token);
    }


    public function index()
    {
//        $data = $this->request()->getRequestParam();
//        return sendSuccess($this->response(),$data);
    }


    public function store()
    {
        return sendSuccess($this->response(),['store']);
    }


    public function show()
    {
        $id = $this->request()->getQueryParam('id');
        return sendSuccess($this->response(),['show'.$id]);
    }


    public function update()
    {
        $id = $this->request()->getQueryParam('id');
        return sendSuccess($this->response(),['update'.$id]);
    }


    public function destroy()
    {
        $id = $this->request()->getQueryParam('id');
        return sendSuccess($this->response(),['destroy'.$id]);
    }
}