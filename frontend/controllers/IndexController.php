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
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\web\Controller;

class IndexController extends Controller
{
    //>>显示商城首页
    public function actionIndex(){
        //>>显示首页
        return $this->render("index.html");
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
             * 方法三:用户嵌套集合插件中方法查询所有的三级分类
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
        $pager->pageSize = 100;
        $pager->totalCount = $model->where(["in","goods_category_id",$ids])->andWhere(["=","is_on_sale",1])->count();
        //>>获取当前页的商品数据
        $goodsList = $model->limit($pager->limit)->offset($pager->offset)->all();
        //>>显示页面
        //var_dump($goodsList);exit();
        return $this->render("list",[
            "goodsList"=>$goodsList,
        ]);
    }
    //>>显示商品详情页
    public function actionGoodsIntro($id){
        $fileName = \Yii::getAlias("@frontend/views/index/goodsIntro/goodsIntro_".$id.".html");
        if (!is_file($fileName)){
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
            file_put_contents($fileName,$goodsIntro);
        }
        return $this->render("goodsIntro/goodsIntro_".$id.".html");
    }
    /**
     * 优化,页面静态化,用户登录状态使用Ajax方式获取
     */
    public function actionUserStatus(){
        $login = !\Yii::$app->user->isGuest;
        $username = $login?\Yii::$app->user->identity->username:"";
        return json_encode(["login"=>$login,"username"=>$username]);
    }
    /**
     * 由于商品详情页已静态化所以需要通过Ajax的方式从redis中获取浏览量
     */
    public function actionViewTimes(){
        $id = \Yii::$app->request->get("id");
        $redis = new \Redis();
        $redis->connect("127.0.0.1");
        return $redis->incr("view_times_".$id);
    }
}