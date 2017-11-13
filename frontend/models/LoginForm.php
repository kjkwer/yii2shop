<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/12
 * Time: 20:59
 */

namespace frontend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password_hash;
    public $captcha;
    public $rem;
    public function rules(){
        return [
            [["username","password_hash"],"required"],
            [["captcha"],"captcha"],
            [["rem"],"safe"]
        ];
    }
    //>>验证登录信息
    public function checkLogin(){
        $model = Memeber::findOne(["username"=>$this->username]);
        //>>验证用户名是否存在
        if ($model){
            //>>验证密码是否正确
            if (\Yii::$app->security->validatePassword($this->password_hash,$model->password_hash)){
                //>>验证通过更行登录信息
                $model->last_login_time = time();
                $model->last_login_ip = ip2long(\Yii::$app->request->userIP);
                $model->save(false);
                //>>保存session和cookie信息
                if ($this->rem){
                    $time = 3600;
                }else{
                    $time = 0;
                }
                \Yii::$app->user->login($model,$time);
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}