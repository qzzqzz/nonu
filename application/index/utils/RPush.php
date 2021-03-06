<?php

namespace app\index\utils;


/**
 * Created by PhpStorm.
 * User : zw
 * Date : 2018/2/24
 * Time : 17:17
 * Intro:
 */
class RPush
{

    /**
     * @param $stock
     * @return CurlResponse|bool
     */
    public function getNounNum($stock)
    {
        $arr = array(
            'key'   => APP_KEY,
            'stock' => $stock
        );
        $curl          = new CurlUtil();
        $curl->headers = [
            "Accept"        => "application/json",
            "Content-Type"  => "application/json;charset=utf-8",
        ];
        $curl->options = [
            "CURLOPT_SSL_VERIFYPEER" => 0,
            "CURLOPT_SSL_VERIFYHOST" => 2,
        ];
        $url           = $this->buildSendUrl();
        $response      = $curl->get($url, $arr);
        return $response;
    }

    /**
     * 请求api
     * @return string
     */
    private function buildSendUrl()
    {
        return "http://op.juhe.cn/onebox/stock/query";
    }
}