<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 14:23
 */

namespace backend\models;


use yii\base\Model;

class RePwdForm extends Model
{
    public $oldPwd;
    public $newPwd;
    public $againNewPwd;
    public function attributeLabels(){
        return [
            "oldPwd"=>"旧密码",
            "newPwd"=>"新密码",
            "againNewPwd"=>"确认密码",
        ];
    }
    public function rules(){
        return [
            [["oldPwd","newPwd","againNewPwd"],"required"],
            [["againNewPwd"],"compare","compareAttribute"=>"newPwd","message"=>"两次输入密码必须一致"]
        ];
    }
}