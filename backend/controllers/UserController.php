<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7
 * Time: 9:24
 */

namespace backend\controllers;


use backend\filters\RbacFilters;
use backend\models\LoginForm;
use backend\models\RePwdForm;
use backend\models\RoleForm;
use backend\models\User;
use yii\captcha\Captcha;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
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
        $pager->pageSize = 10;
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
        //>>获取所有的角色
        $auth = \Yii::$app->authManager;
        $roleList = $auth->getRoles();
        //>>并将权限转换成 ["name1"=>"description1","name2"=>"description2"...]的形式
        $roles = ArrayHelper::map($roleList,"name","description");
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            $userModel->load($request->post());
            $roleNames = $request->post("User")["roles"];
            if ($userModel->validate()){
                $userModel->password_hash = \Yii::$app->security->generatePasswordHash($userModel->password_hash);
                $userModel->auth_key = uniqid();
                $userModel->save();
                //>>给用户分配配色
                if($roleNames){
                    foreach ($roleNames as $roleName){
                        $role = $auth->getRole($roleName);
                        $auth->assign($role,$userModel->id);
                    }
                }
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
           "userModel"=>$userModel,
            "roles"=>$roles
        ]);
    }
    //>>修改管理员
    public function actionUpd($id){
        //>>创建表单对象
        $userModel = User::findOne(["id"=>$id]);
        //>>获取所有的角色
        $auth = \Yii::$app->authManager;
        $roleList = $auth->getRoles();
        //>>并将权限转换成 ["name1"=>"description1","name2"=>"description2"...]的形式
        $roles = ArrayHelper::map($roleList,"name","description");
        //>>获取该用户所有的角色
        $userRoles = $auth->getRolesByUser($userModel->id);
        $userModel->roles = [];
        foreach ($userRoles as $userRole){
            $userModel->roles[]=$userRole->name;
        }
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            $userModel->load($request->post());
            $roleNames = $request->post("User")["roles"];
            if ($userModel->validate()){
                $userModel->password_hash = \Yii::$app->security->generatePasswordHash($userModel->password_hash);
                $userModel->save();
                //>>给用户设置新的角色
                $auth->revokeAll($id);
                if($roleNames){
                    foreach ($roleNames as $roleName){
                        $role = $auth->getRole($roleName);
                        $auth->assign($role,$userModel->id);
                    }
                }
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
            "userModel"=>$userModel,
            "roles"=>$roles
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
            \Yii::$app->authManager->revokeAll($id);
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
    //>>修改密码
    public function actionRePwd(){
        //>>判断用户是否登录
        if(\Yii::$app->user->isGuest){
            //>>未登录则跳转到登录页
            \Yii::$app->session->setFlash("success","请先登录");
            return $this->redirect("login");
        }
        //>>创建表单模型对象
        $rePwdForm = new RePwdForm();
        //>>接收参数
        $request = new Request();
        if ($request->isPost){
            $rePwdForm->load($request->post());
            //>>验证数据
            if ($rePwdForm->validate()){
                //>>获取当前用户密码
                $pwd = \Yii::$app->user->identity->password_hash;
                //>>验证旧密码是否正确
                if (\Yii::$app->security->validatePassword($rePwdForm->oldPwd,$pwd)){
                    User::updateAll(["password_hash"=>\Yii::$app->security->generatePasswordHash($rePwdForm->newPwd),"auth_key"=>uniqid()],["id"=>\Yii::$app->user->identity->id]);
                    \Yii::$app->session->setFlash("success","密码修改成功,请重新登录");
                    return $this->redirect("login");
                }else{
                    $rePwdForm->addError("oldPwd","旧密码错误");
                }
            }else{
                \Yii::$app->session->setFlash("success",$rePwdForm->getErrors());
            }
        }
        //>>显示视图
        return $this->render("rePwd",[
            "rePwdForm"=>$rePwdForm
        ]);
    }
    //>>绑定行为
    public function actions(){
        return [
            "captcha"=>[   //验证码
                'class'=>CaptchaAction::className(),
                'fixedVerifyCode'=>YII_ENV_TEST?'testme':null,
                'minLength'=>4,
                'maxLength'=>4
            ]
        ];
    }
    //>>附加行为
    public function behaviors(){
        return [
            "rbac"=>[   //权限控制
                "class"=>RbacFilters::className(),
                "except"=>["login","logout","captcha"]
            ]
        ];
    }
}