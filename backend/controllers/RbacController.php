<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 15:13
 */

namespace backend\controllers;

use backend\filters\RbacFilters;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Request;

class RbacController extends Controller
{
    //>>权限列表
    public function actionPermissionList(){
        //>>获取所有权限的数据
        $auth = \Yii::$app->authManager;
        $permissionList = $auth->getPermissions();
        //>>显示页面
        return $this->render("permissionList",[
            "permissionList"=>$permissionList,
        ]);
    }
    //>>增加权限
    public function actionPermissionAdd(){
        //>>创建模型对象
        $permissionForm = new PermissionForm();
        //>>接收请求参数
        $request = new Request();
        if ($request->isPost){
            $permissionForm->load($request->post());
            //>>验证数据
            $permissionForm->scenario = PermissionForm::SCENARIO_ADD; //设置验证场景
            if ($permissionForm->validate() && $permissionForm->add()){
                //>>保存信息成功跳转页面
                \Yii::$app->session->setFlash("success","添加成功");
                return $this->redirect("permission-list");
            }
        }
        //>>显示视图
        return $this->render("permissionAdd",[
            "permissionForm"=>$permissionForm
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
        if ($per){
            if ($auth->remove($per)){
                //>>删除成功,响应浏览器
                echo 1;
            }else{
                echo "删除失败";
            }
        }else{
            echo "该权限不存在";
        }
    }
    //>>修改权限
    public function actionPermissionUpdate($name){
        //>>创建模型对象
        $permissionForm = new PermissionForm();
        //>>获得当前权限的对象
        $auth = \Yii::$app->authManager;
        $per = $auth->getPermission($name);
        if (!$per){  //当权限不存在时
            throw new HttpException(403,"该数据不存在");
        }
        //>>设置回显参数
        $permissionForm->route = $per->name;
        $permissionForm->desc = $per->description;
        $permissionForm->oldRoute = $name;
        //>>接收请求数据
        $request = new Request();
        if ($request->post()) {
            $permissionForm->load($request->post());
            $permissionForm->scenario = PermissionForm::SCENARIO_UPD;//设置验证场景
            if ($permissionForm->validate() && $permissionForm->upd($name)) {
                //>>修改成功返回列表页
                \Yii::$app->session->setFlash("success", "修改成功");
                return $this->redirect("permission-list");
            }
        }
        //>>显示视图
        return $this->render("permissionAdd",[
            "permissionForm"=>$permissionForm,
        ]);
    }
    //>>角色列表
    public function actionRoleList(){
        //>>获取所有角色
        $auth = \Yii::$app->authManager;
        $roleList = $auth->getRoles();
        //>>显示页面
        return $this->render("roleList",[
            "roleList"=>$roleList,
        ]);
    }
    //>>增加角色
    public function actionRoleAdd(){
        //>>创建模型对象
        $roleForm = new RoleForm();
        //>>获取所有的权限
        $auth = \Yii::$app->authManager;
        $permissionList = $auth->getPermissions();
        //>>将权限转换成 ["name1"=>"description1","name2"=>"description2"...]的形式
        $permissions = ArrayHelper::map($permissionList,"name","description");
        //>>接收表单数据
        $request = new Request();
        if ($request->isPost){
            $roleForm->load($request->post());
            $roleForm->scenario = RoleForm::SCENARIO_ADD;//设置验证场景
            if ($roleForm->validate() && $roleForm->add()){
                    //>>保存信息成功跳转页面
                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect("role-list");
            }else{
                var_dump($roleForm->getErrors());
                exit();
            }
        }
        //>>显示视图表单
        return $this->render("roleForm",[
            "roleForm"=>$roleForm,
            "permissions"=>$permissions
        ]);
    }
    //>>删除角色
    public function actionRoleDelete(){
        //>>获取数据
        $request = new Request();
        $name = $request->post("name");
        //>>获取该权限对象
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($name);
        if ($role){
            if ($auth->remove($role)){
                //>>删除成功,响应浏览器
                echo 1;
            }else{
                echo "删除失败";
            }
        }else{
            echo "该角色不存在";
        }
    }
    //>>修改角色
    public function actionRoleUpdate($name){
        //>>创建模型对象
        $roleForm = new RoleForm();
        //>>获取当前角色的对象,以及当前角色下的所有权限
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($name);
        if (!$role){  //当角色不存在时
            throw new HttpException(403,"该数据不存在");
        }
        $rolePermissions = $auth->getPermissionsByRole($name);
        //>>设置回显参数
        $roleForm->name = $role->name;
        $roleForm->desc = $role->description;
        $roleForm->oldName = $name;
        $roleForm->per = [];
        foreach ($rolePermissions as $rolePermission){
            $roleForm->per[] = $rolePermission->name;
        }
        //>>获取所有权限
        $permissionList = $auth->getPermissions();
        $permissions = ArrayHelper::map($permissionList,"name","description");
        //>>接收表单数据
        $request = new Request();
        if ($request->isPost){
            $roleForm->load($request->post());
            $roleForm->scenario = RoleForm::SCENARIO_UPD;
            if ($roleForm->validate() && $roleForm->upd($name)){
                \Yii::$app->session->setFlash("success","修改成功");
                return $this->redirect("role-list");
            }
        }
        //>>显示表单页面
        return $this->render("roleForm",[
            "roleForm"=>$roleForm,
            "permissions"=>$permissions
        ]);
    }
    //>>附加行为
    public function behaviors(){
        return [
            "rbac"=>[   //权限控制
                "class"=>RbacFilters::className()
            ]
        ];
    }
}