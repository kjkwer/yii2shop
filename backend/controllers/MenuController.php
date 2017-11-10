<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 11:30
 */

namespace backend\controllers;


use backend\models\Menu;
use yii\web\Controller;
use yii\web\Request;

class MenuController extends Controller
{
    //>>菜单列表
    public function actionList(){
        //>>创建模型对象
        $menu = new Menu();
        //>>获取菜单页面所有数据
        $allMenus = $menu->find()->orderBy(["tree"=>"desc","lft"=>"asc","rgt"=>"asc"])->all();
        //>>显示页面
        return $this->render("list",[
            "allMenus"=>$allMenus
        ]);
    }
    //>>添加菜单
    public function actionAdd(){
        //>>创建模型对象
        $menu = new Menu();
        //>>接收表单数据
        $request = new Request();
        if ($request->isPost){
            $menu->load($request->post());
            if ($menu->validate() && $menu->add()){
                //>>添加成功,跳转页面
                \Yii::$app->session->setFlash("success","添加成功");
                return $this->redirect("list");
            }else{
                var_dump($menu->getErrors());
                exit();
            }
        }
        //>>显示页面
        return $this->render("form",[
            "menu"=>$menu,
        ]);
    }
    //>>修改菜单
    public function actionUpd($id){
        //>>创建模型对象
        $menu = Menu::findOne(["id"=>$id]);
        //>>接收请求参数
        $request = new Request();
        if ($request->isPost){
            $menu->load($request->post());
            if ($menu->validate() && $menu->upd()){
                //>>修改成功
                \Yii::$app->session->setFlash("success","修改成功");
                return $this->redirect("list");
            }else{
                var_dump($menu->getErrors());
            }
        }
        //>>显示页面
        return $this->render("form",[
            "menu"=>$menu
        ]);
    }
    //>>删除菜单
    public function actionDel(){
        //>>接收参数
        $id = \Yii::$app->request->post("id");
        //>>创建模型对象
        $menu = Menu::findOne(["id"=>$id]);
        if ($menu){
            if ($menu->isLeaf()){
                if ($menu->parent_id==0){
                    $menu->deleteWithChildren();
                }else{
                    $menu->delete();
                }
                echo 1;
            }else{
                echo "存在子菜单,不能被删除";
            }
        }else{
            echo "数据不存在";
        }
    }
    public function actionTest(){
        //>>创建模型对象
        var_dump(Menu::test());
    }
}