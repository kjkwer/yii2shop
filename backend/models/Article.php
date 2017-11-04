<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 16:33
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Article extends ActiveRecord
{
    public function attributeLabels(){
        return [
            "name"=>"文章名称",
            "intro"=>"文章简介",
            "article_category_id"=>"选择分类",
            "sort"=>"排序",
            "status"=>"状态"
        ];
    }
    public function rules(){
        return [
            [["name","intro","article_category_id","status"],"required"]
        ];
    }
    //>>获取文章的所属分类
    public function getCategory(){
        return $this->hasOne(ArticleCategory::className(),["id"=>"article_category_id"]);
    }
}