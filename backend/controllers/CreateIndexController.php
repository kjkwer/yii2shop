<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20
 * Time: 19:34
 */

namespace backend\controllers;


use yii\web\Controller;

class CreateIndexController extends Controller
{
    //>>生成静态首页
    public function actionCreateIndex(){
        $indexStatus = $this->renderPartial("@frontend/views/index/index");
        $fileName = \Yii::getAlias("@frontend/views/index/index.html");
        file_put_contents($fileName,$indexStatus);
        echo "生成静态首页成功";
    }
}