<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15
 * Time: 15:51
 */

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Cart;
use yii\web\Controller;
use yii\web\Request;

class CartController extends Controller
{
    public $enableCsrfValidation = false;
    //>>购物车列表
    public function actionList(){
        if (\Yii::$app->user->isGuest){
            //>>用户未登录
            //>>读取cookie的信息
            $carts = Cart::getCookies();
            //>>获取所有商品的id
            $ids = array_keys($carts);
        }else{
            //>>用户已登录
            //>>获取当前用户的购物车列表
            $member_id = \Yii::$app->user->identity->id;
            $cartList = Cart::findAll(["member_id"=>$member_id]);
            //>>取出所有商品的id
            //>>将购物车数据转换成["goods_id1"=>"amount1","goods_id2"=>"amount2".....]
            $ids = [];
            $carts = [];
            foreach ($cartList as $cart){
                $ids[] = $cart->goods_id;
                $carts[$cart->goods_id]=$cart->amount;
            }
        }
        //>>通过id取出所有的商品
        $goodsList = Goods::find()->where(["in","id",$ids])->all();
        $totalPrice = 0;//总价
        //>>显示列表页
        return $this->render("list",[
            "carts"=>$carts,
            "goodsList"=>$goodsList,
            "totalPrice" => $totalPrice
        ]);
    }
    //>>修改购物车数量
    public function actionUpdateAmount(){
        //>>获取数据
        $amount = \Yii::$app->request->post("amount");
        $goods_id = \Yii::$app->request->post("goods_id");
        if (\Yii::$app->user->isGuest){
            //>>用户未登录
            //>>获取cookie信息
            $carts = Cart::getCookies();
            $carts[$goods_id]=$amount;
            //>>保存cookie信息
            Cart::saveCookies($carts);
        }else{
            //>>用户已登录
            $cart = Cart::find()->where(["goods_id"=>$goods_id])->andWhere(["member_id"=>\Yii::$app->user->identity->id])->one();
            $cart->amount = $amount;
            $cart->save();
        }
    }
    //>>删除数据
    public function actionDelete(){
        //>>获取数据
        $goods_id = \Yii::$app->request->post("goods_id");
        //>>判断用户是否登陆
        if (\Yii::$app->user->isGuest){
            //>>取出cookie中的购物车数据
            $carts = Cart::getCookies();
            //>>删除选中的物品
            unset($carts[$goods_id]);
            //>>保存cookie信息
            Cart::saveCookies($carts);
            return true;
        }
        //>>删除数据
        if (Cart::findOne(["goods_id"=>$goods_id,"member_id"=>\Yii::$app->user->identity->id])->delete()){
            return true;
        }
        return false;
    }
    //>>增加购物车商品
    public function actionAdd(){
        //>>接收表单提交信息
        $request = new Request();
        $cart = new Cart();
        $cart->load($request->post(),"");
        if ($cart->validate() && $cart->addGoods()){
            //>>添加购物车成功,跳转至购物车列表页
            return $this->redirect("/cart/list");
        }
    }
}