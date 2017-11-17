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
        $order = new Order();
        //>>创建订单表
        if ($order->add()){
            //>>创建订单详情表
            $carts = Cart::find()->where(["member_id"=>\Yii::$app->user->getId()])->all();
            foreach ($carts as $cart){
                $orderGoods = new OrderGoods();
                if ($orderGoods->add($order->id,$cart)){
                    //>>创建成功,删除购物车中的数据
                    $cart->delete();
                }
            }
            //>>跳转到订单列表页
            return true;
        }
        return false;
    }
    //>>显示订单提交成功页面
    public function actionList(){
        //>>显示页面
        return $this->render("success");
    }
    
}