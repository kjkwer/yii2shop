<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/5
 * Time: 16:32
 */

namespace backend\controllers;


use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class GoodsCategoryController extends Controller
{
    //>>显示商品目录列表
    public function actionList(){
        //>>创建模型对象
        $model = new GoodsCategory();
        //>>创建分页工具
        $pager = new Pagination();
        $pager->totalCount = $model->find()->count();
        $pager->pageSize = 4;
        //>>获取当前也内容
        $goodsCategoryList = $model->find()->limit($pager->limit)->offset($pager->offset)->all();
        //>>显示视图
        return $this->render("list",[
            "goodsCategoryList" => $goodsCategoryList,
            "pager"=>$pager
        ]);
    }
    //>>添加商品目录
    public function actionAdd(){
        //>>创建模型对象
        $model = new GoodsCategory();
        $model->parent_id = 0;  //设置默认父节点为0,即默认创建根节点
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            //>>验证数据
            if ($model->validate()){
                //>>保存数据
                if ($model->parent_id==0){
                    $model->makeRoot();
                }else{
                    $parent = GoodsCategory::findOne(["id"=>$model->parent_id]);
                    $model->prependTo($parent);
                }
                return $this->redirect("list");
            }
        }
        //>>显示添加表单
        return $this->render("form",[
            "model"=>$model
        ]);
    }
    //>>更新商品目录
    public function actionUpd($id){
        //>>创建模型对象
        $model = GoodsCategory::findOne(["id"=>$id]);
        //>>判断请求方式
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            //>>验证数据
            if ($model->validate()){
                //>>保存数据
                if ($model->parent_id==0){
                    $model->makeRoot();
                }else{
                    $parent = GoodsCategory::findOne(["id"=>$model->parent_id]);
                    $model->prependTo($parent);
                }
                return $this->redirect("list");
            }
        }
        //>>显示添加表单
        return $this->render("form",[
            "model"=>$model
        ]);
    }
    //>>删除商品目录
    public function actionDel(){
        //>>接收判断请求方式
        $request = new Request();
        if ($request->isPost){
            $id = $request->post("id");
            //>>查询该目录下是否存在子目录
            if (GoodsCategory::findOne(["parent_id"=>$id])){
                //>>存在子目录
                echo "存在子目录不能被删除";
            }else{
                GoodsCategory::findOne(["id"=>$id])->delete();
                echo 1;
            }
        }
    }
}