<?php
namespace console\controllers;
use backend\models\Goods;
use yii\console\Controller;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/21
 * Time: 10:21
 */
class ClearController extends Controller
{
    //>>设置定时器,自动清理超时未支付订单
    public function actionClearOrder(){
        set_time_limit(0);//不限制该脚本执行时间
        $time = time();
        $sql = 'update `order` set status=0 WHERE status=1 AND '.$time.'-`create_time`>60';
        \Yii::$app->db->createCommand($sql)->execute();//执行sql命令
    }
    //>>将redis中的商品数量同步至数据表中
    public function actionToTable(){
        //>>链接redis
        $redis = new \Redis();
        $redis->connect("127.0.0.1");
        //>>获取所有商品信息
        $goodsList = Goods::find()->all();
        foreach ($goodsList as $goods){
            $goods->stock = $redis->get("stock_".$goods->id);
            $goods->save();
        }
    }
    //>>将商品数量同步至redis中
    public function actionToRedis(){
        //>>链接redis
        $redis = new \Redis();
        $redis->connect("127.0.0.1");
        //>>获取所有商品信息
        $goodsList = Goods::find()->all();
        foreach ($goodsList as $goods){
            $redis->set("stock_".$goods->id,$goods->stock);
        }
    }
}