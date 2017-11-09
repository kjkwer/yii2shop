<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 12:00
 */

namespace backend\controllers;
use backend\filters\RbacFilters;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;


class BrandController extends Controller
{
    public $enableCsrfValidation=false;
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
        if ($request->isPost){
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
    }
    //>>文件上传(上传至七牛)
    public function actionUpload(){
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            $uploadFile = UploadedFile::getInstanceByName("file");
            if ($uploadFile){
                $filename = "/brand_images/".uniqid().".".$uploadFile->extension;
                $uploadFile->saveAs(\Yii::getAlias("@webroot").$filename,0);
                //==================将图片上传至七牛云==================//
                // 需要填写你的 Access Key 和 Secret Key
                $accessKey ="SNQSremMUOQtLuyDcIaXNaBkOrh_RDFauA4oZQhL";
                $secretKey = "nuYj3xEKNW82B40s46pfiIWySSOsci7wL8fCsfFv";
                $bucket = "yii2shop";
                // 构建鉴权对象
                $auth = new Auth($accessKey, $secretKey);
                // 生成上传 Token
                $token = $auth->uploadToken($bucket);
                // 要上传文件的本地路径
                $filePath = \Yii::getAlias("@webroot").$filename;
                // 上传到七牛后保存的文件名
                $key = $filename;
                // 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new UploadManager();
                // 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
                //echo "\n====> putFile result: \n";
                if ($err !== null) {
                    echo json_encode(["success"=>false,"alt"=>"图片上传失败"]);
                } else {
                    echo json_encode(["success"=>true,"src"=>"http://oyxh3lkhn.bkt.clouddn.com"."/".$filename]);
                }
            }
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