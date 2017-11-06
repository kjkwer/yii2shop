<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7
 * Time: 0:23
 */

namespace backend\models;


use yii\base\Model;

class GoodsSearchForm extends Model
{
    public $name;
    public $sn;
    public $minPrice;
    public $maxPrice;
    public function attributeLabels(){
        return [
            "name"=>"商品名称",
            "sn"=>"SN号",
            "minPrice"=>"最小金额",
            "maxPrice"=>"最大金额"
        ];
    }
    public function rules(){
        return [
            [["sn","minPrice","maxPrice"],"double"]
        ];
    }
}