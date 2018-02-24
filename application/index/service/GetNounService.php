<?php
namespace app\index\service;
use app\index\utils\RPush;

/**
 * Created by PhpStorm.
 * User : zw
 * Date : 2018/2/24
 * Time : 下午3:53
 * Intro:
 */

class GetNounService {

    public function getNoun(){
        $stock  = "汽车之家";
        $rPush  = new RPush();
        $result = $rPush->getNounNum($stock);
        var_dump($result);

    }
}