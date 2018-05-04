<?php
namespace app\model;

use think\Db;
use think\Model;

/**
 * Created by PhpStorm.
 * User : zw
 * Date : 2018/3/11
 * Time : 下午10:09
 * Intro:
 */
class TotalModel extends Model{

    protected $table = "total";
    protected $db_ ;
    function __construct()
    {
        $this->db_ = Db::table($this->table);
    }

    public function addTotal($params){
        Db::startTrans();
        try {
            $this->db_->insertAll($params);
            Db::commit();
            return 1;
        } catch (\Exception $e) {
            Db::rollback();
            return 0;
        }
    }

    public function selectNouns(){

    }

    public function updateNoun(){

    }

    public function getList($field,$params){

        $result = Db::table($this->table)
            ->field($field)
            ->where($params)
            ->order("id desc")
            ->select();
        //echo $this->db_->getLastSql();
        return $result;
    }


}