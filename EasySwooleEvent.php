<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\Exceptions\ExceptionHandler;
use App\Middleware\TokenValidationMiddleware;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use Illuminate\Database\Capsule\Manager as DB;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Utility\File;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        self::loadConf();
        date_default_timezone_set('Asia/Shanghai');
        // 初始化数据库
        $db = new DB();
        // 创建链接
        $db->addConnection(config('DB'));
        // 设置全局静态可访问
        $db->setAsGlobal();
        // 启动Eloquent
        $db->bootEloquent();
        //DI注册异常处理:
        //Di::getInstance()->set(SysConst::HTTP_EXCEPTION_HANDLER,[ExceptionHandler::class,'handle']);
        //加载API
        require_once EASYSWOOLE_ROOT.'/App/routes/api.php';
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        TokenValidationMiddleware::getInstance()->handle($request, $response);//token 验证
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }

    public static function onReceive(\swoole_server $server, int $fd, int $reactor_id, string $data):void
    {

    }

    /**
     * 加载配置文件
     */
    public static function loadConf(){
        $files = File::scanDirectory(EASYSWOOLE_ROOT.'/Config');
        if(is_array($files)){
            foreach ($files['files'] as $file) {
                $fileNameArr= explode('.',$file);
                $fileSuffix = end($fileNameArr);
                if($fileSuffix=='php'){
                    Config::getInstance()->loadFile($file);
                }elseif($fileSuffix=='env'){
                    Config::getInstance()->loadEnv($file);
                }
            }
        }
    }
}