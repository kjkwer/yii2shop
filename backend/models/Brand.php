<?php
namespace backend\models;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 14:16
 */
class Brand extends \yii\db\ActiveRecord
{
    public $logoFile;
    public function attributeLabels(){
        return [
            'name'=>'品牌名称',
            'intro'=>'品牌简介',
            'logoFile'=>'上传Logo',
            'sort'=>'排序',
            'status'=>'状态',
        ];
    }
    public function rules(){
        return [
            [['name','intro','status'],'required']
        ];
    }
}