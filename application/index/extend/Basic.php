<?php

namespace app\index\extend;

/**
 * Created by PhpStorm.
 * User: zw
 * Date: 2018/3/12
 * Time: 9:35
 * 基类
 */
use think\Controller;
use think\Request;
use think\Response;
use Qiniu;

class Basic extends Controller
{
    /**
     * 访问请求对象
     * @var Request
     */
    public $request;

    public $params;




    /**
     * 基础接口SDK
     * @param Request|null $request
     */
    /**
     * Basic constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        // CORS 跨域 Options 检测响应
        $this->corsOptionsHandler();
        // 输入对象
        $this->request = is_null($request) ? Request::instance() : $request;

        if (strtoupper($this->request->method()) === "GET") {
            $this->params = $this->request->param();
        } elseif (strtoupper($this->request->method()) === "POST") {
            $this->params = $this->request->param() != null ? $this->request->param() : null;
        }



    }


    /**
     * 输出返回数据
     * @param string $msg 提示消息内容
     * @param string $code 业务状态码
     * @param mixed $data 要返回的数据
     * @param string $type 返回类型 JSON XML
     * @return Response
     */
    public function response($code = 'SUCCESS', $msg, $data = [], $type = 'json')
    {
        $result = [ 'code' => $code, 'msg' => $msg, 'data' => $data, 'type' => strtolower($type) ];
        return Response::create($result, $type);
    }


    /**
     * Cors Options 授权处理
     */
    public static function corsOptionsHandler()
    {
        if (request()->isOptions()) {
            header('Access-Control-Allow-Origin:*');
            header('Access-Control-Allow-Headers:Accept,Referer,Host,Keep-Alive,User-Agent,X-Requested-With,Cache-Control,Content-Type,Cookie,token');
            header('Access-Control-Allow-Credentials:true');
            header('Access-Control-Allow-Methods:GET,POST,OPTIONS');
            header('Access-Control-Max-Age:1728000');
            header('Content-Type:text/plain charset=UTF-8');
            header('Content-Length: 0', true);
            header('status: 204');
            header('HTTP/1.0 204 No Content');
            exit;
        }
    }

    /**
     *  Cors Request Header信息
     * @return array
     */
    public static function corsRequestHander()
    {
        return [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Credentials' => true,
            'Access-Control-Allow-Methods' => 'GET,POST,OPTIONS',
            'Access-Defined-X-Support' => 'service@cuci.cc',
            'Access-Defined-X-Servers' => 'Guangzhou Cuci Technology Co. Ltd',
        ];
    }

}


