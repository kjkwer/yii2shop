<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/21
 * Time: 0:40
 */

namespace backend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\web\Controller;

class StaticController extends Controller
{
    //>>生成静态首页
    public function actionCreateIndex(){
        $indexStatus = $this->renderPartial("@frontend/views/index/index");
        $fileName = \Yii::getAlias("@frontend/views/index/index.html");
        file_put_contents($fileName,$indexStatus);
        echo "生成静态首页成功";
    }
    //>>静态化商品详情页
    public function actionCreateGoods($id){
//>>获取商品信息
        $goodsMessage = Goods::findOne(["id"=>$id]);
        //>>获取商品相册的图片
        $goodsGalleryList = GoodsGallery::find()->where(["goods_id"=>$goodsMessage->id])->orderBy("id desc")->all();
        //>>查询到商品详情
        $goodsIntros = GoodsIntro::findOne(["goods_id"=>$goodsMessage->id]);
        //>>查询该商品的分类层级
        $threeCategory = GoodsCategory::findOne(["id"=>$goodsMessage->goods_category_id]);
        $twoCategory = GoodsCategory::findOne(["id"=>$threeCategory->parent_id]);
        $oneCategory = GoodsCategory::findOne(["id"=>$twoCategory->parent_id]);
        //>>获取页面内容
        $goodsIntro = $this->renderPartial("@frontend/views/index/goodsIntro.php",[
            "goodsMessage"=>$goodsMessage,
            "threeCategory"=>$threeCategory,
            "twoCategory"=>$twoCategory,
            "oneCategory"=>$oneCategory,
            "goodsGalleryList"=>$goodsGalleryList,
            "goodsIntros"=>$goodsIntros
        ]);
        //>>将内容存放至静态页面
        $fileName = \Yii::getAlias("@frontend/views/index/goodsIntro/goodsIntro_".$id.".html");
        file_put_contents($fileName,$goodsIntro);
        echo "商品详情页静态化成功";
    }
}