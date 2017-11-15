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
use yii\data\Pagination;
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
        $child = GoodsCategory::findOne(["parent_id"=>$id]);
        if(!$child){
            //>>是三级分类,得到三级分类的id集合
            $ids =[$id];
        }else{
            /**
             * 方法一:遍历的方式查找三级分类
             */
            //>>不是三级分类
//            $depth = GoodsCategory::findOne(["id"=>$id])->depth;//该分类所在层级
//            if ($depth==1){
//                //>>为二级分类,获取所有三级分类目录
//                $threeCategoryList = GoodsCategory::findAll(["parent_id"=>$id]);
//                //>>获取所有目录下的所有商品
//                $goodsList = [];
//                foreach ($threeCategoryList as $threeCategory){
//                    $goodses = Goods::findALL(["goods_category_id"=>$threeCategory->id]);
//                    foreach ($goodses as $goods){
//                        $goodsList[] = $goods;
//                    }
//                }
//            }
//            if ($depth==0){
//                //>>为一级目录,获取所有二级目录
//                $twoCategoryList = GoodsCategory::findAll(["parent_id"=>$id]);
//                //>>获取所有三级目录
//                $threeCategoryList = [];
//                foreach ($twoCategoryList as $twoCategory){
//                    $array = GoodsCategory::findAll(["parent_id"=>$twoCategory->id]);
//                    foreach ($array as $arr){
//                        $threeCategoryList[] = $arr;
//                    }
//                }
//                //>>获取该一级分类下所有商品信息
//                $goodsList = [];
//                foreach ($threeCategoryList as $threeCategory){
//                    $goodses = Goods::findALL(["goods_category_id"=>$threeCategory->id]);
//                    foreach ($goodses as $goods){
//                        $goodsList[] = $goods;
//                    }
//                }
//            }
            /**
             * 方法二:通过判断左值和右值查找三级分类
             */
            //            $goodsCategory = GoodsCategory::findOne(["id"=>$id]);
//            //var_dump($goodsCategory->lft);exit;
//            //>>查询下面所有的三级分类
//            $threeCategoryList = GoodsCategory::find()->where(["depth"=>2,"tree"=>$goodsCategory->tree])->andWhere([">","lft",$goodsCategory->lft])->andWhere(["<","rgt",$goodsCategory->rgt])->all();
//            //>>获取该一级分类下所有商品信息
//            $goodsList = [];
//            foreach ($threeCategoryList as $threeCategory){
//                $goodses = Goods::findALL(["goods_category_id"=>$threeCategory->id]);
//                foreach ($goodses as $goods){
//                    $goodsList[] = $goods;
//                }
//            }
            /**
             * 方法三:
             */
            $goodsCategory = GoodsCategory::findOne(["id"=>$id]);
            //>>children()为嵌套集合插件中的方法,可以查询下层级的数据,如果不带参数,则查询所有向下层级的数据
            $ids = $goodsCategory->children()->andWhere(["depth"=>2])->column();//三级分类的id集合
        }
        //>>创建商品模型对象
        $model = Goods::find();
        //var_dump($child);exit();
        //>>设计分页工具
        $pager = new Pagination();
        $pager->pageSize = 20;
        $pager->totalCount = $model->where(["in","goods_category_id",$ids])->andWhere(["=","is_on_sale",1])->count();
        //>>获取当前页的商品数据
        $goodsList = $model->limit($pager->limit)->offset($pager->offset)->all();
        //>>从redis中获取商品分类所有的商品分类信息
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