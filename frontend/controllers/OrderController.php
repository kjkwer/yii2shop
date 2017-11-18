<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16
 * Time: 16:27
 */

namespace frontend\controllers;


use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Request;

class OrderController extends Controller
{
    public $enableCsrfValidation=false;
    //>>显示订单表单页
    public function actionIndex(){
        /**
         * 尚未登录
         */
        if (\Yii::$app->user->isGuest){
            //>>尚未登录
            return $this->redirect("/member/login");
        }
        /**
         * 已登录
         */
        //>>1 获取用户的收货地址
        $addresses = Address::findAll(["memeber_id"=>\Yii::$app->user->id]);
        //>>2 获取用户购物车信息
        $cartList = Cart::findAll(["member_id"=>\Yii::$app->user->id]);
        //>>3 获取商品id,设置数据["goods_id1"=>"amount1","goods_id2"=>"amount2".....]
        $ids = [];
        $carts = [];
        foreach ($cartList as $cart){
            $ids[] = $cart->goods_id;
            $carts[$cart->goods_id]=$cart->amount;
        }
        //>>4 取出所有商品信息
        $goodsList = Goods::find()->where(["in","id",$ids])->all();
        //>>5 初始化总价和总数
        $totalAmount = 0;
        $totalPrice = 0;
        //var_dump($goodsList);exit();
        //>>显示订单页面
        return $this->render("order",[
            "addresses"=>$addresses,
            "carts"=>$carts,
            "goodsList"=>$goodsList,
            "totalPrice" => $totalPrice,
            "totalAmount"=>$totalAmount
        ]);
    }
    //>>生成订单
    public function actionAdd(){
        //>>获取数据
        $request  = new Request();
        $data = $request->post();
        $order = new Order();
        //>>创建订单表
        $carts = Cart::find()->where(["member_id"=>\Yii::$app->user->getId()])->all();
        //>>用户id
        $order->member_id = \Yii::$app->user->getId();
        //>>地址信息
        $address = Address::findOne(["id"=>$data["address_id"]]);//地址信息
        $order->name = $address->username;
        $order->province = $address->provinces;
        $order->city = $address->cities;
        $order->area = $address->areas;
        $order->address = $address->address;
        $order->tel = $address->tel;
        //>>配送方式
        $order->delivery_id = Order::$delivery[$data["delivery"]][0];
        $order->delivery_name = Order::$delivery[$data["delivery"]][1];
        $order->delivery_price = Order::$delivery[$data["delivery"]][2];
        //>>支付方式
        $order->payment_id = Order::$pay[$data["pay"]][0];
        $order->payment_name = Order::$pay[$data["pay"]][1];
        //>>订单金额
        $order->total = 0;
        foreach ($carts as $cart){
            $goods = Goods::findOne(["id"=>$cart->goods_id]);
            $order->total += $goods->shop_price*$cart->amount;
        }
        $order->total += $order->delivery_price;
        //>>订单状态,交易号,创建时间
        $order->status = 1;
        $order->trade_no = uniqid();
        $order->create_time = time();
        //>>开启事务
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            if ($order->save()){
                //>>创建订单详情表
                foreach ($carts as $cart){
                    $orderGoods = new OrderGoods();
                    $goods = Goods::findOne(["id"=>$cart->goods_id]);
                    if ($cart->amount > $goods->stock){
                        throw new Exception($goods->name."库存不足");
                    }
                    $orderGoods->order_id = $order->id;
                    $orderGoods->goods_id = $cart->goods_id;
                    $orderGoods->goods_name = $goods->name;
                    $orderGoods->logo = $goods->logo;
                    $orderGoods->price = $goods->shop_price;
                    $orderGoods->amount = $cart->amount;
                    $orderGoods->total = $goods->shop_price*$cart->amount;
                    $orderGoods->save();
                    //>>减少商品库存
                    $goods->stock -= $orderGoods->amount;
                    $goods->save();
                }
                //删除购物车
                Cart::deleteAll('member_id='.\Yii::$app->user->id);
                $order->save();
            }
            //>>提交事务
            $transaction->commit();
        }catch (Exception $e){
            //>>回滚
            $transaction->rollBack();
            //>>输出错误信息
            echo $e->getMessage();
        }
    }
    //>>显示订单提交成功页面
    public function actionSuccess(){
        //>>显示页面
        return $this->render("success");
    }
    //>>显示用户订单列表
    public function actionList(){
        //>>判断用户是否登录
        if (\Yii::$app->user->isGuest){
            //>>尚未登录
            return $this->redirect("/member/login");
        }
        //>>获取用户所有的订单信息
        $orderList = Order::findAll(["member_id"=>\Yii::$app->user->getId()]);
        //>>显示列表
        return $this->render("list",[
            "orderList"=>$orderList
        ]);
    }
}