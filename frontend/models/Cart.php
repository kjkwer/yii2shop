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
use yii\web\Cookie;

class Cart extends ActiveRecord
{
    public function rules()
    {
        return [
            [["amount","goods_id"],"required"]
        ];
    }
    //>>添加新的商品
    public function addGoods(){
        if (\Yii::$app->user->isGuest){
            //>>接收参数
            $goods_id = $this->goods_id;
            $amount = \Yii::$app->request->post("amount");
            //>>读取cookie的信息
            $carts = self::getCookies();
            if (isset($carts[$goods_id])){
                $carts[$goods_id] = $carts[$goods_id]+$amount;
            }else{
                $carts[$goods_id] = $amount;
            }
            //>>将设置好的购物车信息存入cookie中
            self::saveCookies($carts);
            return true;
        }else{
            //>>判断用户购物车中是否已存在该商品
            if ($cartModel = Cart::find()->where(["goods_id"=>$this->goods_id])->andWhere(["member_id"=>\Yii::$app->user->identity->id])->one()){
                //>>已存在
                $cartModel->amount = $cartModel->amount+$this->amount;
                if ($cartModel->save()){
                    return true;
                }
            }else{
                $this->member_id = \Yii::$app->user->identity->id;
                if ($this->save()){
                    return true;
                }
            }
        }
        return false;
    }
    //>>根据商品ID获取到商品信息
    public function getGoodsMessage(){
        return self::hasOne(Goods::className(),["id"=>"goods_id"]);
    }
    //>>获取cookie中购物车中的信息
    public static function getCookies(){
        $cookies = \Yii::$app->request->cookies;
        $carts = unserialize($cookies->getValue("carts"));
        if (!$carts){
            $carts = [];
        }
        return $carts;
    }
    //>>保存cookie购物车信息
    public static function saveCookies($carts){
        $cookies = \Yii::$app->response->cookies;
        $cookie = new Cookie();
        $cookie->name = "carts";
        $cookie->value = serialize($carts);
        $cookies->add($cookie);
    }
    //>>登录成功,将cookie中的购物车商品信息保存到用户的购物车中
    public static function cookieToUser(){
        //>>1 获取cookie的购物车中的信息
        $cartsCookies = self::getCookies();
        //>>2 获取cookie中所有商品的id
        $ids = array_keys($cartsCookies);
        //>>3 若用户的购物车中存在cookie的商品,则累加数量,若不存在,则新增商品
        foreach ($ids as $id){
            if ($model = Cart::findOne(["goods_id"=>$id,"member_id"=>\Yii::$app->user->getId()])){
                $model->amount += $cartsCookies[$id];
                $model->save();
            }else{
                $model = new Cart();
                $model->amount = $cartsCookies[$id];
                $model->goods_id = $id;
                $model->member_id = \Yii::$app->user->getId();
                $model->save();
            }
        }
        //>>4 清空cookie中购物车物品
        $cartsCookies = [];
        //>>5 保存cookie
        self::saveCookies($cartsCookies);
    }
}