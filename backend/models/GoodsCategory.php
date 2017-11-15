<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/5
 * Time: 16:33
 */

namespace backend\models;
use yii;
use yii\db\ActiveRecord;
//>>嵌套集合插件的文件命名空间
use creocoder\nestedsets\NestedSetsBehavior;
class GoodsCategory extends ActiveRecord
{
    public static function tableName()
    {
        return 'goods_category';
    }
    public function attributeLabels(){
        return [
            "name" => "名称",
            "parent_id" => "父目录",
            "intro" => "简介"
        ];
    }
    public function rules(){
        return [
            [["name","parent_id","intro"],"required"]
        ];
    }
    //>>查询出所有数据  只查询字段id  parent_id  name
    public static function getAll(){
        return GoodsCategory::find()->select(["id","parent_id","name"])->asArray()->all();
    }
    //>>将所有商品分类信息保存在redis中
    public static function saveRedis(){
        //>>链接redis
        $redis = new \Redis();
        $redis->connect("127.0.0.1");
        //>>获取所有商品
        $goodsCategoryList = GoodsCategory::find()->where(["parent_id"=>0])->all();
        //>>设计商品分级数据
        $categorys = [];
        foreach ($goodsCategoryList as $goodsCategory){
            $arr = ["name"=>$goodsCategory->name,"id"=>$goodsCategory->id];
            if ($goodsCategoryChildList = GoodsCategory::findAll(["parent_id"=>$goodsCategory->id])){
                foreach ($goodsCategoryChildList as $goodsCategoryChild){
                    $arr1 = ["name"=>$goodsCategoryChild->name,"id"=>$goodsCategoryChild->id];
                    if ($goodsCategoryGrandsonList = GoodsCategory::findAll(["parent_id"=>$goodsCategoryChild->id])){
                        foreach ($goodsCategoryGrandsonList as $goodsCategoryGrandson){
                            $arr2 = ["name"=>$goodsCategoryGrandson->name,"id"=>$goodsCategoryGrandson->id];
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
        $redis->delete('goodsCategory');//先redis中之前的商品分类数据
        foreach ($categorys as $category){
            $category = serialize($category);
            $redis->lPush("goodsCategory",$category);
        }
    }
    //>>从redis中获取所有商品分类数据
    public static function getRedis(){
        $redis = new \Redis();
        $redis->connect("127.0.0.1");
        $datas = $redis->lRange('goodsCategory', 0, -1);
        $goodsCategoryList = [];
        foreach ($datas as $data){
            $goodsCategoryList[] = unserialize($data);
        }
        return $goodsCategoryList;
    }
    //>>
    //>>==========嵌套集合插件配置=====================
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',//要使用到多棵树
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }
    //>>================================================



}