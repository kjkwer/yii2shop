<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15
 * Time: 15:51
 */

namespace frontend\controllers;


use frontend\models\Cart;
use yii\helpers\Url;
use yii\web\Controller;

class CartController extends Controller
{
    //>>购物车列表
    public function actionList(){
        if (\Yii::$app->user->isGuest){
            //>>尚未登录
            return $this->redirect(Url::to(["/member/login"]));
        }
        //>>获取当前用户的购物车列表
        $member_id = \Yii::$app->user->identity->id;
        $cartList = Cart::findAll(["member_id"=>$member_id]);
        $totalPrice = 0;//总价
        //>>显示列表页
        return $this->render("list",[
            "cartList"=>$cartList,
            "totalPrice" => $totalPrice
        ]);
    }
    //>>更新数量
    public function actionUpdateAmount(){
        //>>获取数据
        $amount = \Yii::$app->request->get("amount");
        $id = \Yii::$app->request->get("id");
        //>>更新数据
        $cart = Cart::findOne(["id"=>$id]);
        $cart->amount = $amount;
        $cart->save();
    }
    //>>删除数据
    public function actionDelete(){
        //>>获取数据
        $id = \Yii::$app->request->get("id");
        //>>删除数据
        if (Cart::findOne(["id"=>$id])->delete()){
            return true;
        }
        return false;
    }
}