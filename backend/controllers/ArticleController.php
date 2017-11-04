<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 16:30
 */

namespace backend\controllers;


use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller
{
    public function actionList(){
        //>>创建模型对象
        $model = new Article();
        //>>创建分页工具
        $pager = new Pagination();
        $pager->pageSize=4;
        $pager->totalCount=$model->find()->count();
        $articleList = $model->find()->andwhere(["!=","status","-1"])->limit($pager->limit)->offset($pager->offset)->all();
        //var_dump($brandList);exit();
        //>>显示页面
        return $this->render("list",[
            "articleList"=>$articleList,
            "pager"=>$pager
        ]);
    }
    public function actionAdd(){
        //>>创建模型对象
        $artmodel = new Article();
        $artDetailModel = new ArticleDetail();
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            $artmodel->load($request->post());
            $artDetailModel->load($request->post());
            //>>验证数据
            if ($artmodel->validate() && $artDetailModel->validate()){
                $artmodel->create_time = time();
                $artmodel->save();
                $artDetailModel->article_id = $artmodel->id;
                $artDetailModel->save();
                //>>数据添加成功,跳转页面
                \Yii::$app->session->setFlash("success","添加成功");
                return $this->redirect("list");
            }else{
                var_dump($artmodel->getErrors(),$artDetailModel->getErrors());
            }
        }
        //>>显示视图
        return $this->render("form",[
            "artmodel"=>$artmodel,
            "artDetailModel"=>$artDetailModel,
        ]);
    }
    public function actionDel(){
        //>>接收数据
        $request = new Request();
        $id = $request->post("id");
        //>>删除数据
        if ($brand = Article::findOne(["id"=>$id])){
            $brand->status = -1;
            $brand->save();
            echo 1;
        }else{
            echo "数据不存在";
        };
    }
    public function actionUpd($id){
        //>>创建模型对象
        $artmodel = Article::findOne(["id"=>$id]);
        $artDetailModel = ArticleDetail::findOne(["article_id"=>$id]);
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            $artmodel->load($request->post());
            $artDetailModel->load($request->post());
            //>>验证数据
            if ($artmodel->validate() && $artDetailModel->validate()){
                $artmodel->create_time = time();
                $artmodel->save();
                $artDetailModel->article_id = $artmodel->id;
                $artDetailModel->save();
                //>>数据添加成功,跳转页面
                \Yii::$app->session->setFlash("success","添加成功");
                return $this->redirect("list");
            }else{
                var_dump($artmodel->getErrors(),$artDetailModel->getErrors());
            }
        }
        //>>显示页面
        return $this->render("form",[
            "artmodel"=>$artmodel,
            "artDetailModel" =>$artDetailModel
        ]);
    }
}