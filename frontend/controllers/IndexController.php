<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 15:42
 */

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\web\Controller;

class IndexController extends Controller
{
    //>>显示商城首页
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
    //>>显示商品列表页
    public function actionGoodsList($id){
        //>>判断给分类是否为三级分类
        if(!GoodsCategory::findAll(["parent_id"=>$id])){
            //>>是三级分类,获取该分类下的所有商品
            $goodsList = Goods::findAll(["goods_category_id"=>$id,"is_on_sale"=>1]);
        }else{
            //>>不是三级分类
            $depth = GoodsCategory::findOne(["id"=>$id])->depth;//该分类所在层级
            if ($depth==1){
                //>>为二级分类,获取所有三级分类目录
                $threeCategoryList = GoodsCategory::findAll(["parent_id"=>$id]);
                //>>获取所有目录下的所有商品
                $goodsList = [];
                foreach ($threeCategoryList as $threeCategory){
                    $goodses = Goods::findALL(["goods_category_id"=>$threeCategory->id]);
                    foreach ($goodses as $goods){
                        $goodsList[] = $goods;
                    }
                }
            }
            if ($depth==0){
                //>>为一级目录,获取所有二级目录
                $twoCategoryList = GoodsCategory::findAll(["parent_id"=>$id]);
                //>>获取所有三级目录
                $threeCategoryList = [];
                foreach ($twoCategoryList as $twoCategory){
                    $array = GoodsCategory::findAll(["parent_id"=>$twoCategory->id]);
                    foreach ($array as $arr){
                        $threeCategoryList[] = $arr;
                    }
                }
                //>>获取该一级分类下所有商品信息
                $goodsList = [];
                foreach ($threeCategoryList as $threeCategory){
                    $goodses = Goods::findALL(["goods_category_id"=>$threeCategory->id]);
                    foreach ($goodses as $goods){
                        $goodsList[] = $goods;
                    }
                }
            }
        }
        //>>从redis中获取商品分类所有的商品
        $redis = new \Redis();
        $redis->connect("127.0.0.1");
        $goodsCategoryList = GoodsCategory::getRedis();
        //>>显示页面
        //var_dump($goodsList);exit();
        return $this->render("list",[
            "goodsList"=>$goodsList,
            "goodsCategoryList"=>$goodsCategoryList
        ]);
    }
    //>>显示商品详情页
    public function actionGoodsIntro($id){
        echo 111;
    }
}