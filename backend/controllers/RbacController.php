<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 15:13
 */

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use Codeception\Lib\Connector\Yii1;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
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
            if ($permissionForm->validate()){
                //>>判断该权限是否已存在
                if (!\Yii::$app->authManager->getPermission($permissionForm->route)){
                    //>>验证通过保存权限
                    $auth = \Yii::$app->authManager;
                    $per = $auth->createPermission($permissionForm->route);
                    $per->description = $permissionForm->desc;
                    $auth->add($per);
                    //>>保存成功,跳转页面
                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect("permission-list");
                }else{
                    $permissionForm->addError("route","该权限已存在,请勿重复添加");
                }
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
        //>>设置回显参数
        $permissionForm->route = $per->name;
        $permissionForm->desc = $per->description;
        //>>接收请求数据
        $request = new Request();
        if ($request->post()){
            $permissionForm->load($request->post());
            if ($permissionForm->validate()){
                //var_dump($permissionForm);exit();
                $per->name = $permissionForm->route;
                $per->description = $permissionForm->desc;
                if ($auth->update($name,$per)){
                    //>>修改成功返回列表页
                    \Yii::$app->session->setFlash("success","修改成功");
                    return $this->redirect("permission-list");
                }
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
            if ($roleForm->validate()){
                //>>判断该角色是否已存在
                if (!$auth->getRole($roleForm->name)){
                    //>>验证数据通过,添加角色
                    $role = $auth->createRole($roleForm->name);
                    $role->description = $roleForm->desc;
                    $auth->add($role);
                    //>>分配权限
                    if ($roleForm->per){
                        foreach ($roleForm->per as $permissionName){
                            $permission = $auth->getPermission($permissionName);
                            $auth->addChild($role,$permission);
                        }
                    }
                    //>>保存信息成功跳转页面
                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect("role-list");
                }else{
                    //>>已存在
                    $roleForm->addError("name","该角色已存在,请勿重复添加");
                }
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
        $rolePermissions = $auth->getPermissionsByRole($name);
        //>>设置回显参数
        $roleForm->name = $role->name;
        $roleForm->desc = $role->description;
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
            if ($roleForm->validate()){
                //>>验证通过更新角色信息
                $role->name = $roleForm->name;
                $role->description = $roleForm->desc;
                $auth->update($name,$role);
                //>>更新角色的权限
                $auth->removeChildren($role);
                if ($roleForm->per){
                    foreach ($roleForm->per as $permissionName){
                        $permission = $auth->getPermission($permissionName);
                        $auth->addChild($role,$permission);
                    }
                }
                //>>修改成功,跳转至列表页
                //>>保存信息成功跳转页面
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
}