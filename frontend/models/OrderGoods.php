<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17
 * Time: 12:57
 */

namespace frontend\models;


use backend\models\Goods;
use yii\db\ActiveRecord;
use yii\db\Exception;

class OrderGoods extends ActiveRecord
{
    //>>通过订单详情表查找商品所属订单信息
    public function getOrder(){
        return self::hasOne(Order::className(),["id"=>"order_id"]);
    }
}