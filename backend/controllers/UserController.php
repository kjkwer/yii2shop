<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7
 * Time: 9:24
 */

namespace backend\controllers;


use backend\models\LoginForm;
use backend\models\User;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class UserController extends Controller
{
    //>>管理员列表
    public function actionList(){
        //>>创建模型对象
        $userModel = new User();
        //>>创键分页工具
        $pager = new Pagination();
        $pager->pageSize = 4;
        $pager->totalCount = $userModel->find()->count();
        //>>查询当前分页的数据
        $userList = $userModel->find()->orderBy("id desc")->limit($pager->limit)->offset($pager->offset)->all();
        //>>显示视图
        return $this->render("list",[
            "userList"=>$userList,
            "pager"=>$pager
        ]);
    }
    //>>添加管理员
    public function actionAdd(){
        //>>创建模型对象
        $userModel = new User();
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            $userModel->load($request->post());
            if ($userModel->validate()){
                $userModel->password_hash = \Yii::$app->security->generatePasswordHash($userModel->password_hash);
                $userModel->save();
                //>>保存数据成功,跳转页面
                \Yii::$app->session->setFlash("success","添加成功");
                return $this->redirect("list");
            }else{
                var_dump($userModel->getErrors());
                exit();
            }
        }
        //>>显示视图
        return $this->render("form",[
           "userModel"=>$userModel
        ]);
    }
    //>>修改管理员
    public function actionUpd($id){
        //>>创建表单对象
        $userModel = User::findOne(["id"=>$id]);
        $userModel->password_hash = "";
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            $userModel->load($request->post());
            if ($userModel->validate()){
                $userModel->password_hash = \Yii::$app->security->generatePasswordHash($userModel->password_hash);
                $userModel->save();
                //>>保存数据成功,跳转页面
                \Yii::$app->session->setFlash("success","添加成功");
                return $this->redirect("list");
            }else{
                var_dump($userModel->getErrors());
                exit();
            }
        }
        //>>显示页面
        return $this->render("form",[
            "userModel"=>$userModel
        ]);
    }
    //>>删除管理员
    public function actionDel(){
        //>>接收数据
        $request = new Request();
        $id = $request->post("id");
        //>>判断是否存在该参数
        if (User::findOne(["id"=>$id])){
            User::findOne(["id"=>$id])->delete();
            echo 1;
        }else{
            echo "该数据不存在";
        }
    }
    //>>登录功能
    public function actionLogin(){
        //>>创建模型对象
        $loginModel = new LoginForm();
        //>>接受表单数据
        $request = new Request();
        if ($request->isPost){
            $loginModel->load($request->post());
            //>>调用模型对象判断验证用户
            if ($loginModel->checkLogin()){
                //>>验证通过,跳转页面
                \Yii::$app->session->setFlash("success","登录成功");
                return $this->redirect("list");
            }
        }
        //>>显示页面
        return $this->render('login',[
            "loginModel"=>$loginModel
        ]);
    }
    //>>注销登陆
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect("login");
    }
}