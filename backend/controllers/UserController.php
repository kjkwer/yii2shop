<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7
 * Time: 9:24
 */

namespace backend\controllers;


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
}