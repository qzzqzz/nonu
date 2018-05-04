<?php

namespace app\model;

use think\Db;
use think\Model;

/**
 * Created by PhpStorm.
 * User : zw
 * Date : 2018/4/25
 * Time : 下午12:01
 * Intro:
 */
class AVGTotalModel extends Model
{
    protected $table = "avg_total";
    protected $db_;

    function __construct()
    {
        $this->db_ = Db::table($this->table);
    }

    public function addList($params)
    {

   /*     $nowTime               = date("Y-m-d", time());
        $where_["create_time"] = array( "gt", $nowTime );

        $result = Db::table($this->table)
            ->field("id")
            ->where($where_)
            ->count();
        if (count($result) > 0) { //今天已经跑过了就不在跑
            return 0;
        }*/

        Db::startTrans();
        try {
            $id = Db::table($this->table)->insertGetId($params);
            Db::commit();
            return $id;
        } catch (\Exception $e) {
            Db::rollback();
            return 0;
        }
    }
}