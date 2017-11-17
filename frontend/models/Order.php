<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17
 * Time: 11:54
 */

namespace frontend\models;


use backend\models\Goods;
use yii\db\ActiveRecord;
use yii\web\Request;

class Order extends ActiveRecord
{
    //>>添加新订单
    public function add(){
        //>>获取数据
        $request  = new Request();
        $data = $request->post();
        /**
         * 添加订单数据
         */
        //>>用户id
        $this->member_id = \Yii::$app->user->getId();
        //>>地址信息
        $address = Address::findOne(["id"=>$data["address_id"]]);//地址信息
        $this->name = $address->username;
        $this->province = $address->provinces;
        $this->city = $address->cities;
        $this->area = $address->areas;
        $this->address = $address->address;
        $this->tel = $address->tel;
        //>>配送方式
        $this->delivery_name = $data["delivery"];
        if ($this->delivery_name == "普通快递送货上门"){
            $this->delivery_id = 1;
            $this->delivery_price = 10.00;
        }elseif ($this->delivery_name == "特快专递"){
            $this->delivery_id = 2;
            $this->delivery_price = 40.00;
        }elseif ($this->delivery_name == "加急快递送货上门"){
            $this->delivery_id = 3;
            $this->delivery_price = 40.00;
        }elseif ($this->delivery_name == "平邮"){
            $this->delivery_id = 4;
            $this->delivery_price = 10.00;
        }
        //>>支付方式
        $this->payment_name = $data["pay"];
        if ($this->payment_name == "货到付款"){
            $this->payment_id = 1;
        }elseif ($this->payment_name == "在线支付"){
            $this->payment_id = 2;
        }elseif ($this->payment_name == "上门自提"){
            $this->payment_id = 3;
        }elseif ($this->payment_name == "邮局汇款"){
            $this->payment_id = 4;
        }
        //>>订单金额
        $carts = Cart::findAll(["member_id"=>\Yii::$app->user->getId()]);
        $this->total = 0;
        foreach ($carts as $cart){
            $goods = Goods::findOne(["id"=>$cart->goods_id]);
            $this->total += $goods->shop_price*$cart->amount;
        }
        $this->total += $this->delivery_price;
        //>>订单状态,交易号,创建时间
        $this->status = 1;
        $this->trade_no = uniqid();
        $this->create_time = time();
        if ($this->save()){
            return true;
        }
        return false;
    }
}