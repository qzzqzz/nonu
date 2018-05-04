<?php

namespace app\index\controller;

use app\index\extend\Basic;
use app\index\service\GetNounService;
use app\index\service\MailService;

class Index extends Basic
{
    private $getNounService;
    private $mailService;

    function __construct()
    {
        $this->getNounService = new GetNounService();
        $this->mailService  = new MailService();
    }

    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }

    /**
     * 定时任务执行，录入数据
     */
    public function getNoun()
    {
        $this->getNounService->getNoun();
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function getTest(){
        //$this->getNounService->getList();
        $param1 = array(
            "to_email" => "1191502428@qq.com",
        );
        $param2 = array(
            "to_email" => "1191502422@qq.com",
        );
        $params = [];
        array_push($params,$param1);
        array_push($params,$param2);
        $this->mailService->sendMail($params,"hello world");
    }

    public function addNoun(){
        $params = $this->params;
      /*  $params = array(
            "noun_name" => "汽车之家",
        );*/
        if(isset($params["noun_name"])){
           return $this->response("101","请求参数错误");
        }
        $this->getNounService->addNoun($params["noun_name"]);
    }

    public function totalAVG(){
        $this->getNounService->totalAVG();
    }
}
