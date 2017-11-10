<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 15:45
 */

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;

class PermissionForm extends Model
{
    public $route;
    public $desc;
    public $oldRoute;
    //>>场景
    const SCENARIO_ADD = "add";
    const SCENARIO_UPD = "upd";
    public function attributeLabels(){
        return [
            "route"=>"路由",
            "desc"=>"描述"
        ];
    }
    public function rules(){
        return [
            [["route","desc"],"required"],
            //>>自定义验证方式
            [["route"],"validateName","on"=>self::SCENARIO_ADD],
            [["route"],"validateUpdName","on"=>self::SCENARIO_UPD]
        ];
    }
    //>>验证权限是否已存在(自定义验证)
    public function validateName(){
        $auth = \Yii::$app->authManager;
        $model = $auth->getPermission($this->route);
        if ($model){
            $this->addError("route","该权限已存在,请勿重复添加");
        }
    }
    //>>验证修改时属否已存在(自定义验证规则)
    public function validateUpdName(){
        if ($this->oldRoute != $this->route){
            $auth = \Yii::$app->authManager;
            $model = $auth->getPermission($this->route);
            if ($model){
                $this->addError("route","该权限已存在,请勿重复添加");
            }
        }
    }
    //>>添加权限
    public function add(){
        $auth = \Yii::$app->authManager;
        $per = $auth->createPermission($this->route);
        $per->description = $this->desc;
        if ($auth->add($per)){
            return true;
        }
    }
    //>>修改权限
    public function upd($name){
        //>>获得当前权限的对象
        $auth = \Yii::$app->authManager;
        $per = $auth->getPermission($name);
        $per->name = $this->route;
        $per->description = $this->desc;
        if ($auth->update($name,$per)){
            //>>修改成功返回列表页
            return true;
        }
    }
    //>>获取所有路由(从权限表中)
    public static function getAllRoute(){
        $allPermission = \Yii::$app->authManager->getPermissions();
        $permission = ArrayHelper::map($allPermission,"name","name");
        return $permission;
    }
}