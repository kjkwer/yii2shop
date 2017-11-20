<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6
 * Time: 11:26
 */

namespace backend\controllers;


use backend\filters\RbacFilters;
use backend\models\Goods;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use kucha\ueditor\UEditorAction;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsController extends Controller
{
    public $enableCsrfValidation=false;
    //>>商品列表
    public function actionList(){
        //>>创建模型对象
        $goodsModel = Goods::find()->where(["!=","status",0]);
        $goodsSearchForm = new GoodsSearchForm();
        $request = new Request();
        //>>设置搜索查询的信息
        //$data = $request->get("GoodsSearchForm");
        $name = $request->get("name");
        $sn = $request->get("sn");
        $minPrice = $request->get("minPrice");
        $maxPrice = $request->get("maxPrice");
        //var_dump($request->get());exit();
        if ($name){
            $goodsModel->andWhere(["like","name",$name]);
        }
        if ($sn){
            $goodsModel->andWhere(["like","sn",$sn]);
        }
        if ($minPrice){
            $goodsModel->andWhere([">=","shop_price",$minPrice]);
        }
        if ($maxPrice){
            $goodsModel->andWhere(["<=","shop_price",$maxPrice]);
        }
        //>>创建分页工具
        $pager = new Pagination();
        $pager->totalCount = $goodsModel->count();
        $pager->pageSize = 10;
        //查询当前页的数据
        $goodsList = $goodsModel->orderBy("sn desc")->limit($pager->limit)->offset($pager->offset)->all();
        //>>显示视图
        $goodsSearchForm->name=$name;
        $goodsSearchForm->sn=$sn;
        $goodsSearchForm->minPrice=$minPrice;
        $goodsSearchForm->maxPrice=$maxPrice;
        return $this->render("list",[
            "goodsList"=>$goodsList,
            "pager"=>$pager,
            "goodsSearchForm"=>$goodsSearchForm,
            "name"=>$name,
            "sn"=>$sn,
            "minPrice"=>$minPrice,
            "maxPrice"=>$maxPrice,
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
                $goodsModel->status = 1;
                $goodsModel->sn = date("Ymd").sprintf("%05d",$num);
                $goodsModel->create_time = time();
                $goodsModel->save();
                $goodsIntroModel->goods_id = $goodsModel->id;
                $goodsIntroModel->save();
                $goodsIntroModel = GoodsDayCount::findOne(["day"=>$day]);
                $goodsIntroModel->count = $num;
                $goodsIntroModel->save();
                //>>保存成功,跳转页面
                \Yii::$app->session->setFlash("success","添加成功");
                return $this->redirect(Url::to(["images-list","id"=>$goodsModel->id]));
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
            "goodsIntroModel"=>$goodsIntroModel,
        ]);
    }
    //>>更新商品
    public function actionUpd($id){
        //>>创建模型对象
        $goodsModel = Goods::findOne(["id"=>$id]);
        $goodsIntroModel = GoodsIntro::findOne(["goods_id"=>$id]);
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            $goodsModel->load($request->post());
            $goodsIntroModel->load($request->post());
            if ($goodsModel->validate() && $goodsIntroModel->validate()){
                $goodsModel->save();
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
        //>>显示视图页面
        return $this->render("form",[
            "goodsModel"=>$goodsModel,
            "goodsIntroModel"=>$goodsIntroModel
        ]);
    }
    //>>删除商品
    public function actionDel(){
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            //>>接收数据
            $id = $request->post("id");
            //>>删除数据(回收站)
            $model = Goods::findOne(["id"=>$id]);
            if ($model){
                //>>商品存在
                $model->status = 0;
                $model->save();
                return 1;
            }else{
                echo "商品不存在";
            }
        }
    }
    //>>Logo图片上传
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
    //>>预览商品信息
    public function actionPreview($id){
        //>>建立模型对象
        $goodsModel = Goods::findOne(["id"=>$id]);
        $goodsIntroModel = GoodsIntro::findOne(["goods_id"=>$id]);
        //>>显示视图
        return $this->render("preview",[
            "goodsModel"=>$goodsModel,
            "goodsIntroModel"=>$goodsIntroModel
        ]);
    }
    //>>相册列表
    public function actionImagesList($id){
        //>>建立模型对象
        $goodsGalleryList = GoodsGallery::findAll(["goods_id"=>$id]);
        //var_dump($goodsGalleryList);exit();
        //>>显示视图页面
        return $this->render("photoAlbum",[
            "goodsGalleryList"=>$goodsGalleryList,
            "id"=>$id
        ]);
    }
    //>>添加相册照片
    public function actionAddImages($id){
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            $uploadFile = UploadedFile::getInstanceByName("file");
            if ($uploadFile){
                $filename = "/goods_preview/".$id."_".uniqid().".".$uploadFile->extension;
                //var_dump($filename);exit();
                $uploadFile->saveAs(\Yii::getAlias("@webroot").$filename,0);
                //>>创建相册模型对象,保存数据
                $model = new GoodsGallery();
                $model->goods_id = $id;
                $model->path = $filename;
                $model->save();
                //>>响应数据给浏览器
                echo json_encode(["success"=>true,"src"=>$filename]);
            }
        }
    }
    //>>删除相册的照片
    public function actionDeleImages(){
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            //>>获取数据
            $src = $request->post("src");
            //>>删除数据
            GoodsGallery::findOne(["path"=>$src])->delete();
            //>>响应浏览器
            echo 1;
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