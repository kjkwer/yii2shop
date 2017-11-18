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
    //>>设置属性保存配送配送方式
    public static $delivery=[
        1=>["1","普通快递送货上门",10],
        2=>["2","特快专递",40],
        3=>["3","加急快递送货上门",40],
        4=>["4","平邮",10],
    ];
    //>>设置属性保存支付方式
    public static $pay=[
        1=>["1","货到付款"],
        2=>["2","在线支付"],
        3=>["3","上门自提"],
        4=>["4","邮局汇款"],
    ];
    //>>查询一个订单下所有的商品  一对多
    public function getGoods(){
        return self::hasMany(OrderGoods::className(),["order_id"=>"id"]);
    }
}