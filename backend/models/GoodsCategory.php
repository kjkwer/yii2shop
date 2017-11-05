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

    //>>查询出所有数据
    public static function getAll(){
        return GoodsCategory::find()->select(["id","parent_id","name"])->asArray()->all();
    }
}