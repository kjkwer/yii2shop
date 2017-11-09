<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 15:13
 */

namespace backend\controllers;

use backend\models\PermissionForm;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class RbacController extends Controller
{
    //>>权限列表
    public function actionPermissionList(){
        //>>获取所有权限的数据
        $auth = \Yii::$app->authManager;
        $permissionList = $auth->getPermissions();
        //var_dump(count($permissionList));exit();
        //>>创建分页工具
        $pager = new Pagination();
        $pager->totalCount=count($permissionList);
        $pager->pageSize=4;
        //>>显示页面
        return $this->render("permissionList",[
            "permissionList"=>$permissionList,
            "pager"=>$pager
        ]);
    }
    //>>增加权限
    public function actionPermissionAdd(){
        //>>创建模型对象
        $permission = new PermissionForm();
        //>>接收请求参数
        $request = new Request();
        if ($request->isPost){
            $permission->load($request->post());
            //>>验证数据
            if ($permission->validate()){
                //>>验证通过保存权限
                $auth = \Yii::$app->authManager;
                $per = $auth->createPermission($permission->route);
                $per->description = $permission->desc;
                $auth->add($per);
                //>>保存成功,跳转页面
                \Yii::$app->session->setFlash("success","添加成功");
                return $this->redirect("permission-list");
            }
        }
        //>>显示视图
        return $this->render("permissionAdd",[
            "permission"=>$permission
        ]);
    }
    //>>删除权限
    public function actionPermissionDelete(){
        //>>获取数据
        $request = new Request();
        $route = $request->post("route");
        //>>获取该权限对象
        $auth = \Yii::$app->authManager;
        $per = $auth->getPermission($route);
        if ($auth->remove($per)){
            //>>删除成功,响应浏览器
            echo 1;
        }else{
            echo "删除失败";
        }
    }
}