<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 15:45
 */

namespace backend\controllers;


use backend\filters\RbacFilters;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleCategoryController extends Controller
{
    //>>显示文章分类列表
    public function actionList(){
        //>>创建模型对象
        $model = new ArticleCategory();
        //>>创建分页工具
        $pager = new Pagination();
        $pager->pageSize=4;
        $pager->totalCount=$model->find()->count();
        $articeCategoryList = $model->find()->andwhere(["!=","status","-1"])->limit($pager->limit)->offset($pager->offset)->all();
        //var_dump($brandList);exit();
        //>>显示页面
        return $this->render("list",[
            "articeCategoryList"=>$articeCategoryList,
            "pager"=>$pager
        ]);
    }
    //>>>添加文章分类
    public function actionAdd(){
        //>>创建模型对象
        $model = new ArticleCategory();
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            //>>post请求
            $model->load($request->post());
            //>>验证接收数据
            if ($model->validate()){
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
    //>>更新
    public function actionUpd($id){
        //>>创建模型对象
        $model = ArticleCategory::findOne($id);
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            //>>post请求
            $model->load($request->post());
            //>>验证接收数据
            if ($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash("success","修改成功");
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
    //>>删除
    public function actionDel(){
        //>>接收数据
        $request = new Request();
        if ($request->isPost){
            $id = $request->post("id");
            //>>查看数据属否存在
            if ($brand = ArticleCategory::findOne(["id"=>$id])){
                $brand->status = -1;
                $brand->save();
                echo 1;
            }else{
                echo "数据不存在";
            };
        }
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