<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 15:42
 */

namespace frontend\controllers;


use backend\models\GoodsCategory;
use yii\web\Controller;

class IndexController extends Controller
{
    public function actionIndex(){
        //>>获取所有商品分类
        $goodsCategoryList = GoodsCategory::find()->where(["parent_id"=>0])->all();
        //var_dump($goodsCategory);exit();
        //>>显示首页
        return $this->render("index",[
            "goodsCategoryList"=>$goodsCategoryList
        ]);
    }
}