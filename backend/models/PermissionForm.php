<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 15:45
 */

namespace backend\models;


use yii\base\Model;

class PermissionForm extends Model
{
    public $route;
    public $desc;
    public function attributeLabels(){
        return [
            "route"=>"路由",
            "desc"=>"描述"
        ];
    }
    public function rules(){
        return [
            [["route","desc"],"required"]
        ];
    }
}