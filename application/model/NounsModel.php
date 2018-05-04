<?php
namespace app\model;
use think\Db;
use think\Model;

/**
 * Created by PhpStorm.
 * User : zw
 * Date : 2018/2/24
 * Time : 下午3:56
 * Intro:
 */

class NounsModel extends Model{

    protected $table = "nouns";
    protected $db_ ;
    function __construct()
    {
        $this->db_ = Db::table($this->table);
    }

    public function addNoun($params){
        Db::startTrans();
        try {
            $id = $this->db_->insertGetId($params);
            Db::commit();
            return $id;
        } catch (\Exception $e) {
            Db::rollback();
            return 0;
        }
    }

    public function selectNouns(){
        $params["is_del"] = 0;
        return $this->db_
            ->field("id,noun_name")
            ->where($params)
            ->select();

    }

    public function updateNoun(){

    }


}