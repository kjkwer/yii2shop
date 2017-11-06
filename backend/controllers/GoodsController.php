<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6
 * Time: 11:26
 */

namespace backend\controllers;


use backend\models\Goods;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use kucha\ueditor\UEditorAction;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsController extends Controller
{
    public $enableCsrfValidation=false;
    //>>商品列表
    public function actionList(){
        //>>创建模型对象
        $goodsModel = new Goods();
        //>>创建分页工具
        $pager = new Pagination();
        $pager->totalCount = $goodsModel->find()->count();
        $pager->pageSize = 4;
        //>>获取每页的数据
        $goodsList = $goodsModel->find()->orderBy(["id"=>"desc"])->limit($pager->limit)->offset($pager->offset)->all();
        //>>显示视图
        return $this->render("list",[
            "goodsList"=>$goodsList,
            "pager"=>$pager
        ]);
    }
    //>>添加商品
    public function actionAdd(){
        //>>创建模型对象
        $goodsModel = new Goods();
        $goodsIntroModel = new GoodsIntro();
        $goodsDayCount = new GoodsDayCount();
        //>>设置sn号
        $day = date("Y-m-d");
        if ($goodsDayCount->findOne(["day"=>$day])){
            $num = GoodsDayCount::findOne(["day"=>$day])->count+1;
        }else{
            $goodsDayCount->day = $day;
            $goodsDayCount->count = 0;
            $goodsDayCount->save();
            $num = 1;
        }
        $goodsModel->sn = date("Ymd").sprintf("%05d",$num);
        //var_dump($goodsModel->sn);exit();
        //>>设置默认的添加路径
        $goodsModel->goods_category_id = 0;
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            $goodsModel->load($request->post());
            $goodsIntroModel->load($request->post());
            //var_dump($goodsModel);
            //var_dump($goodsIntroModel);
            //>>验证数据
            if ($goodsModel->validate() && $goodsIntroModel->validate()){
                //>>验证通过,保存数据
                $goodsModel->create_time = time();
                $goodsModel->save();
                $goodsIntroModel->goods_id = $goodsModel->id;
                $goodsIntroModel->save();
                $goodsIntroModel = GoodsDayCount::findOne(["day"=>$day]);
                $goodsIntroModel->count = $num;
                $goodsIntroModel->save();
                //>>保存成功,跳转页面
                \Yii::$app->session->setFlash("success","添加成功");
                return $this->redirect("list");
            }else{
                //>>验证失败,打印错误信息
                var_dump($goodsModel->getErrors());
                var_dump($goodsIntroModel->getErrors());
                exit();
            }
        }
        //>>显示视图
        return $this->render("form",[
            "goodsModel"=>$goodsModel,
            "goodsIntroModel"=>$goodsIntroModel
        ]);
    }
    //>>更新商品
    public function actionUpd(){
        echo "upd";
    }
    //>>删除商品
    public function actionDel(){
        echo "del";
    }
    //>>图片上传
    public function actionUploads(){
        //echo 111;exit();
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            $uploadFile = UploadedFile::getInstanceByName("file");
            if ($uploadFile){
                $filename = "/goods_logos/".uniqid().".".$uploadFile->extension;
                //var_dump($filename);exit();
                $uploadFile->saveAs(\Yii::getAlias("@webroot").$filename,0);
                echo json_encode(["success"=>true,"src"=>$filename]);
            }
        }
    }
    //>>绑定文本编辑器的行为
    public function actions()
    {
        return [
            'upload' => [
                'class' => UEditorAction::className(),
                'config' => [
                    "imageUrlPrefix"  => \Yii::getAlias("@web"),//图片访问路径前缀
                    "imagePathFormat" => "/goods_intro_images/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot")
                ],
            ]
        ];
    }
}