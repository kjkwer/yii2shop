<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7
 * Time: 13:56
 */

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password_hash;
    public $rem;
    public function attributeLabels(){
        return [
            "username"=>"用户名",
            "password_hash"=>"密码"
        ];
    }
    public function rules(){
        return [
            [["username","password_hash"],"required"]
        ];
    }
    //>>验证登录
    public function checkLogin(){
        $user = User::findOne(["username"=>$this->username]);
        if ($user){
            //>>查看密码是否正确
            if (\Yii::$app->security->validatePassword($this->password_hash,$user->password_hash)){
                //>>保存最后登录时间和IP
                $user->last_login_time = time();
                $user->last_login_ip = ip2long(\Yii::$app->request->userIP);
                $user->save();
                //>>密码正确,将用户信息保存在session和cookie中
                \Yii::$app->user->login($user,3600);
                //\Yii::$app->user->switchIdentity($user,3600);
                //>>跳转页面
                return true;
            }else{
                $this->addError("password_hash","用户名或密码错误");
            }
        }else{
            $this->addError("password_hash","用户名或密码错误");
        }
    }
}