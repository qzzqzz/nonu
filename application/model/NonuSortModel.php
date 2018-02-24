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

class NounSortModel extends Model{

    protected $table = "nonu_sort";
    protected $db_ ;
    function __construct()
    {
        $this->db_ = Db::table($this->table);
    }

    public function addNoun(){

    }


}