<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7
 * Time: 9:28
 */

namespace backend\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    public function attributeLabels(){
        return [
            "username"=>"用户名",
            "password_hash"=>"密码",
            "email"=>"邮箱",
            "status"=>"是否启用"
        ];
    }
    public function rules(){
        return [
            [["username","password_hash","email","status"],"required"],
        ];
    }
    //>>保存附加行为
    //>>附加行为
    public function behaviors(){
        return [
            "timestamp"=>[
                //"class"=>TimestampBehavior::className(),
                "class"=>TimestampBehavior::className(),
                "attributes"=>[
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ]
            ]
        ];
    }
}