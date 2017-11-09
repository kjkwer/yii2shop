<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9
 * Time: 10:30
 */

namespace backend\models;


use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $desc;
    public $per;
    public function attributeLabels(){
        return [
            'name'=>'角色名称',
            'desc'=>'角色描述',
            'per'=>'添加权限',
        ];
    }
    public function rules(){
        return [
            [["name","desc"],"required"],
            [["per"],"safe"],
        ];
    }
}