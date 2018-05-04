<?php

namespace app\index\service;

use app\index\utils\RPush;
use app\model\AVGTotalModel;
use app\model\EmailModel;
use app\model\NounsModel;
use app\model\TotalModel;

/**
 * Created by PhpStorm.
 * User : zw
 * Date : 2018/2/24
 * Time : 下午3:53
 * Intro:
 */
class GetNounService
{
    private $totalModel;
    private $nounsModel;

    private $rPushUtils;
    private $avgModel;

    function __construct()
    {
        $this->totalModel = new TotalModel();
        $this->nounsModel = new NounsModel();
        $this->rPushUtils = new RPush();
        $this->avgModel   = new AVGTotalModel();
    }

    public function getNoun()
    {
        $nowTime               = date("Y-m-d", time());
        $params["create_time"] = array( "gt", $nowTime );
        $result                = $this->totalModel->getList("id", $params);
        if (count($result) > 0) { //今天已经跑过了就不在跑
            return;
        }
        $result = $this->nounsModel->selectNouns();
        $params = [];
        if (count($result) > 0) {

            foreach ($result as $key => $value) {
                array_push($params, $this->getNounDetail($value["id"], $value["noun_name"]));
            }
        }
        if (count($params) > 0) {
            $this->totalModel->addTotal($params);
        }
    }

    public function getNounDetail($id, $stock)
    {

        $result      = $this->rPushUtils->getNounNum($stock);
        $content     = json_decode($result->body, true);
        $stockName   = $content["result"]["stockName"];
        $maxPrice    = $content["result"]["maxPrice"];
        $currentTime = $content["result"]["currenttime"];
        $timeZone    = $content["result"]["timeZone"];

        if (!$maxPrice || !$currentTime) {
            //return null;
        } else {
            $totalParams            = $this->binNoun($stockName, $maxPrice, $currentTime, $timeZone);
            $totalParams["noun_id"] = $id;
            return $totalParams;

        }


    }

    private function binNoun($stockName, $maxPrice, $currenttime, $timeZone)
    {
        $params                 = [];
        $params["stock_name"]   = $stockName;
        $params["trade_price"]  = $maxPrice;
        $params["current_time"] = $currenttime;
        $params["time_zone"]    = $timeZone;
        $params["create_time"]  = date("Y-m-d H:i:s", time());
        $params["update_time"]  = date("Y-m-d H:i:s", time());

        return $params;
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function totalAVG()
    {
        $result = $this->nounsModel->selectNouns();
        $time_  = date('Y-m-d', strtotime("-20 day"));

        $message = [];
        foreach ($result as $key => $value) {
            $params["noun_id"]     = $value["id"];
            $params["create_time"] = array( "gt", $time_ );
            $total                 = $this->totalModel->getList("*", $params);

            if (count($total) > 0) {
                $item["noun_name"] = $value["noun_name"];
                $item["now_price"] = $total[0]["trade_price"];
                $avg_price = $this->saveAVG($total, $time_, $value["id"]);
                $item["avg_price"] = $avg_price;
                array_push($message,$item);
            }


        }
        $this->sendMail($message);

    }

    public function addNoun($noun_name)
    {
        $params                = [];
        $params["noun_name"]   = $noun_name;
        $params["create_time"] = date("Y-m-d H:i:s", time());
        $params["update_time"] = date("Y-m-d H:i:s", time());
        $this->nounsModel->addNoun($params);

    }

    private function saveAVG($result, $start_time, $noun_id)
    {
        $totalDay   = count($result);
        $totalPrice = 0;
        $end_time   = date("Y-m-d", time());
        foreach ($result as $item) {
            $totalPrice += $item["trade_price"];
        }
        $avgPrice = round($totalPrice / $totalDay, 2);

        $params["noun_id"]     = $noun_id;
        $params["start_time"]  = $start_time;
        $params["end_time"]    = $end_time;
        $params["avg_price"]   = $avgPrice;
        $params["total_day"]   = $totalDay;
        $params["create_time"] = date("Y-m-d H:i:s", time());
        $params["update_time"] = date("Y-m-d H:i:s", time());
        $id = $this->avgModel->addList($params);
        return $avgPrice;

    }

    /**
     * @param $messageArr
     * @throws \PHPMailer\PHPMailer\Exception
     */
    private function sendMail($messageArr){
        $str = "";
        foreach ($messageArr as $item){
            if($item["now_price"] >= $item["avg_price"]){
                $str .= $item["noun_name"] . "20天均价：".$item["avg_price"]." 。当前价格". $item["now_price"]."<br/>";
            }
        }
        if($str){
            $addressArr = [];
            $emailModel = new EmailModel();
            $emailResult = $emailModel->getEmail("id,name,email",["status"=>0]);
            foreach ($emailResult as $item){
                $arr["to_email"] = $item["email"];
                array_push($addressArr,$arr);
            }
            
            $mailService  = new MailService();
            $mailService->sendMail($addressArr,$str);
        }
    }
}