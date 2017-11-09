<?php
namespace backend\filters;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use yii\base\ActionFilter;
use yii\web\HttpException;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9
 * Time: 16:19
 */
class RbacFilters extends ActionFilter
{
    public function beforeAction($action){

        //>>判断用户权限
        if (!\Yii::$app->user->can($action->uniqueId)){
            //var_dump($action->uniqueId);exit();
            //>>没有权限.判断用户是否登录
            if (\Yii::$app->user->isGuest){
                //>>没有登录则跳转至登录页面
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }else{
                //>>若以登陆,则提示权限不足
                throw new HttpException(403,"权限不足");
                return false;
            }
        }
        return parent::beforeAction($action);
    }
}