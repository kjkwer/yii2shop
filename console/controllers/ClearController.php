<?php
namespace console\controllers;
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
        while (1){
            set_time_limit(0);//不限制该脚本执行时间
            $time = time();
            $sql = 'update `order` set status=0 WHERE status=1 AND '.$time.'-`create_time`>60';
            \Yii::$app->db->createCommand($sql)->execute();//执行sql命令
            sleep(1);  //每隔30秒执行一致
        }
    }
}