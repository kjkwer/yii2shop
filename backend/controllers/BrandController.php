<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 12:00
 */

namespace backend\controllers;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class BrandController extends Controller
{
    //>>显示品牌列表
    public function actionList(){
        //>>创建模型对象
        $model = new Brand();
        //>>创建分页工具
        $pager = new Pagination();
        $pager->pageSize=4;
        $pager->totalCount=$model->find()->count();
        $brandList = $model->find()->andwhere(["!=","status","-1"])->limit($pager->limit)->offset($pager->offset)->all();
        //var_dump($brandList);exit();
        //>>显示页面
        return $this->render("list",[
            "brandList"=>$brandList,
            "pager"=>$pager
        ]);
    }
    //>>添加品牌
    public function actionAdd(){
        //>>创建模型对象
        $model = new Brand();
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            //>>post请求
            $model->load($request->post());
            //>>验证接收数据
            if ($model->validate()){
                $model->logo = "/images/default.jpg";
                $model->save();
                \Yii::$app->session->setFlash("success","添加成功");
                return $this->redirect("list");
            }else{
                var_dump($model->getErrors());
            }
        }
        //>>显示视图
        return $this->render("form",[
            "model"=>$model
        ]);
    }
    //>>修改品牌
    public function actionUpd($id){
        //>>创建模型对象
        $model = Brand::findOne(["id"=>$id]);
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            //>>post请求
            $model->load($request->post());
            //>>验证接收数据
            if ($model->validate()){
                $model->logo = "/images/default.jpg";
                $model->save();
                \Yii::$app->session->setFlash("success","修改成功");
                return $this->redirect("list");
            }else{
                var_dump($model->getErrors());
            }
        }
        //>>显示视图页面
        return $this->render("form",[
            "model"=>$model
        ]);
    }
    //>>删除品牌
    public function actionDel(){
        //>>接收数据
        $request = new Request();
        $id = $request->post("id");
        //>>删除数据
        if ($brand = Brand::findOne(["id"=>$id])){
            $brand->status = -1;
            $brand->save();
            echo 1;
        }else{
            echo "数据不存在";
        };
    }
    //>>回收站
    public function actionRecycle(){
        //>>创建模型对象
        $model = new Brand();
        //>>创建分页工具
        $pager = new Pagination();
        $pager->pageSize=4;
        $pager->totalCount=$model->find()->count();
        $brandList = $model->find()->andwhere(["=","status","-1"])->limit($pager->limit)->offset($pager->offset)->all();
        //var_dump($brandList);exit();
        //>>显示页面
        return $this->render("recycle",[
            "brandList"=>$brandList,
            "pager"=>$pager
        ]);
    }
}