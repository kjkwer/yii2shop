<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9
 * Time: 10:30
 */

namespace backend\models;


use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $desc;
    public $per;
    public $oldName;
    //>>场景
    const SCENARIO_ADD = "add";
    const SCENARIO_UPD = "upd";

    public function attributeLabels(){
        return [
            'name'=>'角色名称',
            'desc'=>'角色描述',
            'per'=>'添加权限',
        ];
    }
    public function rules(){
        return [
            [["name","desc"],"required"],
            [["per"],"safe"],
            //>>自定义验证
            [["name"],"validateName","on"=>self::SCENARIO_ADD],
            [["name"],"validateUpdName","on"=>self::SCENARIO_UPD],
        ];
    }
    //>>验证添加时的名称(自定义)
    public function validateName(){
        $auth = \Yii::$app->authManager;
        if ($auth->getRole($this->name)){
            $this->addError("name","该权限已存在,请勿重复添加");
        }
    }
    //>>验证修改时的名称
    public function validateUpdName(){
        if ($this->oldName != $this->name){
            $auth = \Yii::$app->authManager;
            if ($auth->getRole($this->name)){
                $this->addError("name","该角色已存在,请勿重复添加");
            }
        }
    }
    //>>添加角色
    public function add(){
        $auth = \Yii::$app->authManager;
        $role = $auth->createRole($this->name);
        $role->description = $this->desc;
        if ($auth->add($role)){
            //>>分配权限
            if ($this->per){
                foreach ($this->per as $permissionName){
                    $permission = $auth->getPermission($permissionName);
                    $auth->addChild($role,$permission);
                }
            }
            return true;
        }
    }
    //>>更新角色
    public function upd($name){
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($name);
        //>>验证通过更新角色信息
        $role->name = $this->name;
        $role->description = $this->desc;
        if ($auth->update($name,$role)){
            //>>更新角色的权限
            $auth->removeChildren($role);//先删除已有权限
            if ($this->per){
                foreach ($this->per as $permissionName){
                    $permission = $auth->getPermission($permissionName);
                    $auth->addChild($role,$permission);
                }
            }
            return true;
        }
    }
}