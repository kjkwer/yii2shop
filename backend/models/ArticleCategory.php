<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 15:48
 */

namespace backend\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class ArticleCategory extends ActiveRecord
{
    public function attributeLabels(){
        return [
            'name'=>'分类名称',
            'intro'=>'分类简介',
            'sort'=>'排序',
            'status'=>'状态',
        ];
    }
    public function rules(){
        return [
            [['name','intro','status'],'required']
        ];
    }
    //>>将取出的数组转换为数组  ["id"=>"name","id"=>"name"...]的形式,
    //>>当新建文章时,作为下拉选择所述酚类时使用
    public static function categoryOne(){
        return ArrayHelper::map(self::find()->andWhere(["!=","status","-1"])->asArray()->all(),"id","name");
    }
}