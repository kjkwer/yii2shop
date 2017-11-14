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
        //>>从redis中获取商品分类所有的商品
        $redis = new \Redis();
        $redis->connect("127.0.0.1");
        $goodsCategoryList = GoodsCategory::getRedis();
        //var_dump($goodsCategoryList);exit();
        //>>显示首页
        return $this->render("index",[
            "goodsCategoryList"=>$goodsCategoryList
        ]);
    }
    public function actionTest(){
        //>>链接redis
        $redis = new \Redis();
        $redis->connect("127.0.0.1");
        //>>获取所有商品
        $goodsCategoryList = GoodsCategory::find()->where(["parent_id"=>0])->all();
        //>>设计商品分级数据
        $categorys = [];
        foreach ($goodsCategoryList as $goodsCategory){
            $arr = ["name"=>$goodsCategory->name];
            if ($goodsCategoryChildList = GoodsCategory::findAll(["parent_id"=>$goodsCategory->id])){
                foreach ($goodsCategoryChildList as $goodsCategoryChild){
                    $arr1 = ["name"=>$goodsCategoryChild->name];
                    if ($goodsCategoryGrandsonList = GoodsCategory::findAll(["parent_id"=>$goodsCategoryChild->id])){
                        foreach ($goodsCategoryGrandsonList as $goodsCategoryGrandson){
                            $arr2 = ["name"=>$goodsCategoryGrandson->name];
                            $arr1["child"][] = $arr2;
                        }
                    }
                    $arr["child"][] = $arr1;
                }
            }
            $categorys[] = $arr;
        }
        //var_dump($categorys);exit();
        //>>将数据全部保存到redis中
        $redis->delete('goodsCategory');
        foreach ($categorys as $category){
            $category = serialize($category);
            $redis->lPush("goodsCategory",$category);
        }
    }
    public function actionTest1(){
        $redis = new \Redis();
        $redis->connect("127.0.0.1");
        $datas = $redis->lRange('goodsCategory', 0, -1);
        $goodsCategoryList = [];
        foreach ($datas as $data){
            $goodsCategoryList[] = unserialize($data);
        }
        var_dump($goodsCategoryList);
    }
    public function actionTest2(){
//        $redis = new \Redis();
//        $redis->connect("127.0.0.1");
//        $redis->delete('goodsCategory');
    }
}