<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6
 * Time: 11:30
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Goods extends ActiveRecord
{
    public function attributeLabels(){
        return [
            "name"=>"商品名称",
            "sn"=>"货号",
            "logo"=>"选择Logo",
            "goods_category_id"=>"所属分类",
            "brand_id"=>"所属品牌",
            "market_price"=>"市场价格",
            "shop_price"=>"商品价格",
            "stock"=>"库存",
            "is_on_sale"=>"是否在售",
            "status"=>"状态",
            "sort"=>"排序",
        ];
    }
    public function rules(){
        return [
            [["name","sn","logo","goods_category_id","brand_id","market_price","shop_price","stock","is_on_sale","status"],"required"],
            [["goods_category_id"],"compare","compareValue"=>0,"operator"=>">","message"=>"所属分类不能为空"],
            [["market_price","shop_price"],"double"],
            [["stock","sort"],"integer"],
        ];
    }
    //>>获得当前商品所属分类
    public function getCategory(){
        return self::hasOne(GoodsCategory::className(),["id"=>"goods_category_id"]);
    }
    //>>获得当前商品所属品牌
    public function getBrand(){
        return self::hasOne(Brand::className(),["id"=>"brand_id"]);
    }
}