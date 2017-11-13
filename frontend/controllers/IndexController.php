<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 15:42
 */

namespace frontend\controllers;


use yii\web\Controller;

class IndexController extends Controller
{
    public function actionIndex(){
        //>>显示首页
        return $this->render("index");
    }
}