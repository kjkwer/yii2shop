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
use kucha\ueditor\UEditorAction;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller
{
    //>>文章列表
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
    //>>添加文章
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
    //>>删除文章
    public function actionDel(){
        //>>接收数据
        $request = new Request();
        if ($request->isPost){
            $id = $request->post("id");
            //>>查看数据属否存在
            if ($brand = Article::findOne(["id"=>$id])){
                $brand->status = -1;
                $brand->save();
                echo 1;
            }else{
                echo "数据不存在";
            };
        }
    }
    //>>更新文件
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
    //>>绑定文本编辑器的行为
    public function actions()
    {
        return [
            'upload' => [
                'class' => UEditorAction::className(),
                'config' => [
                    "imageUrlPrefix"  => \Yii::getAlias("@web"),//图片访问路径前缀
                    "imagePathFormat" => "/article_intro_images/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot")
                ],
            ]
        ];
    }
}