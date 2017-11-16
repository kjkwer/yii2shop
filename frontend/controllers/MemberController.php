<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/12
 * Time: 11:49
 */

namespace frontend\controllers;


use Codeception\Module\Redis;
use frontend\models\LoginForm;
use frontend\models\Memeber;
use frontend\models\RegisterForm;
use frontend\models\Sms;
use yii\web\Controller;
use yii\web\Request;

class MemberController extends Controller
{
    //>>用户登录
    public function actionLogin(){
        //>>接收数据
        $request = new Request();
        if ($request->isPost){
            $loginForm = new LoginForm();
            $loginForm->load($request->post(),"");
            $loginForm->load($request->post(),"");
            if ($loginForm->validate() && $loginForm->checkLogin()){
                /**
                 * 获取cookie中的购物信息
                 */
                //>>1 获取购物车中的信息
                return $this->redirect("/index/index");
            }else{
                var_dump($loginForm->getErrors());
                echo "登录失败";
                exit();
            }
        }
        //>>显示页面
        return $this->render("login");
    }
    //>>用户注册
    public function actionRegister(){
        //>>接收验证码数据
        $request = new Request();
        if ($request->isPost){
            $memeber = new Memeber();
            $memeber->load($request->post(),"");
            //验证手机验证码
            $redis = new \Redis();
            $redis->connect("127.0.0.1");
            if ($redis->exists("tel_".$memeber->tel)){
                if ($redis->get("tel_".$memeber->tel) == $request->post("tel_captcha")){
                    //>>验证通过
                    if ($memeber->validate() && $memeber->add()){
                        return $this->redirect("login");
                    }else{
                        var_dump($memeber->getErrors());
                        echo "注册失败";
                        exit();
                    }
                }
            }
        }
        //>>显示注册页面
        return $this->render("register");
    }
    //>>注销登录
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect("/index/index");
    }
    //>>验证用户名是否重复
    public function actionCheckUsername($username){
        if (Memeber::findOne(["username"=>$username])){
            return "false";
        }
        return "true";
    }
    //>>验证电话是否重复
    public function actionCheckTel($tel){
        if (Memeber::findOne(["tel"=>$tel])){
            return "false";
        }
        return "true";
    }
    //>>发送短信验证
    public function actionCheckSms(){
        $tel = \Yii::$app->request->get("tel");
        $code = rand(100000,999999);
        $response = Sms::sendSms(
            "林锋kjkwer", // 短信签名
            "SMS_109425456", // 短信模板编号
            $tel, // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$code,
            )
        );
        //>>保存信息到redis
        if ($response->Message=="OK"){
            $redis = new \Redis();
            $redis->connect("127.0.0.1");
            $redis->set("tel_".$tel,$code,1800);
            return 1;
        }else{
            return 0;
        }
    }
    //>>验证手机验证码
    public function actionCheckTelcaptcha(){
        //echo 111;
        //>>接收参数
        $tel = \Yii::$app->request->get("tel");
        $code = \Yii::$app->request->get("tel_captcha");
        //>>验证验证码
        $redis = new \Redis();
        $redis->connect("127.0.0.1");
        if ($redis->exists("tel_".$tel)){
            if ($redis->get("tel_".$tel) == $code){
                return "true";
            }
        }
        return "false";
    }
    //>>验证邮箱是否重复
    public function actionCheckEmail($email){
        if (Memeber::findOne(["email"=>$email])){
            return "false";
        }
        return "true";
    }
    public function actionTest(){
        $redis = new \Redis();
        $redis->connect("127.0.0.1");
        var_dump($redis->get("tel_13551275376"));
    }
}