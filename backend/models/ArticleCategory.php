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
    public static function categoryOne(){
        return ArrayHelper::map(self::find()->andWhere(["!=","status","-1"])->asArray()->all(),"id","name");
    }
}