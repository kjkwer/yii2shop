<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6
 * Time: 11:30
 */

namespace backend\models;


use yii\db\ActiveRecord;

class GoodsIntro extends ActiveRecord
{
    public function attributeLabels(){
        return [
            "content"=>"商品详情",
        ];
    }
    public function rules(){
        return [
            [["content"],"required"]
        ];
    }
}