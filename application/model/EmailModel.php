<?php
namespace app\model;
use think\Db;
use think\Model;

/**
 * Created by PhpStorm.
 * User : zw
 * Date : 2018/4/25
 * Time : 下午1:54
 * Intro:
 */
class EmailModel extends Model{
    protected $table = "email";
    protected $db_;
    function __construct()
    {
        $this->db_ = Db::table($this->table);
    }

    public function getEmail($field,$where_){
        return $this->db_
            ->field($field)
            ->where($where_)
            ->select();

    }
}