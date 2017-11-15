<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15
 * Time: 15:48
 */

namespace frontend\models;


use backend\models\Goods;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord
{
    public function rules()
    {
        return [
            [["amount"],"required"]
        ];
    }
    //>>添加新的商品
    public function addGoods($id){
        $this->goods_id = $id;
        $this->member_id = \Yii::$app->user->identity->id;
        if ($this->save()){
            return true;
        }
        return false;
    }
    //>>根据商品ID获取到商品信息
    public function getGoodsMessage(){
        return self::hasOne(Goods::className(),["id"=>"goods_id"]);
    }
}