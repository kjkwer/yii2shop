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

class OrderGoods extends ActiveRecord
{
    public function add($order_id,$cart){
        $this->order_id = $order_id;
        $this->goods_id = $cart->goods_id;
        $goods = Goods::findOne(["id"=>$cart->goods_id]);
        $this->goods_name = $goods->name;
        $this->logo = $goods->logo;
        $this->price = $goods->shop_price;
        $this->amount = $cart->amount;
        $this->total = $goods->shop_price*$cart->amount;
        if ($this->save()){
            return true;
        }
        return false;
    }
}